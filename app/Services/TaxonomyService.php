<?php

namespace Apithy\Services;

use Apithy\Company;
use Apithy\Experience;
use Apithy\Http\Resources\Taxonomy\TaxonomyResource;
use Apithy\Http\Resources\User\SimpleUserResource;
use Apithy\Http\Resources\User\UserTaxonomyResource;
use Apithy\Session;
use Apithy\Taxonomy;
use Apithy\User;
use Apithy\Utilities\Master\Master;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Throwable;

class TaxonomyService
{
    private $request;
    private $company_id;
    const COMPANY_AREA = 'company_area';
    const COMPANY_POSITION = 'company_position';
    const ABILITY = 'ability';
    const CERTIFICATION = 'certifications';
    const TEAM = 'teams';
    const CUSTOM = 'custom';
    const TAG = 'tag';

    /**
     * TaxonomyService constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function setCompanyId($id = null)
    {
        $this->company_id = $id;
    }

    public function getCompanyId()
    {
        return $this->company_id;
    }

    public function getAuthCompanyId()
    {
        if ($this->request->user()->isSuper()) {
            if ($this->request->filled('company_id')) {
                return $this->request->input('company_id');
            } elseif (isset($this->company_id)) {
                return $this->getCompanyId();
            }
        }
        return $this->request->user()->company->first()->id;
    }

    /**
     * Lista de colores permitidos
     *
     * @param bool $implode
     * @return array|string
     */
    public function colors($implode = false)
    {
        return $implode ? Taxonomy::inColorList() : Taxonomy::COLORS;
    }

    /**
     * Lista de tipos permitidos
     *
     * @param bool $implode
     * @return array|string
     */
    public function type($implode = false)
    {
        return $implode ? Taxonomy::inTypeList() : Taxonomy::TYPE;
    }

    /**
     * Crear un nuevo taxonomy
     *
     * @param $title
     * @param $type
     * @param bool $response
     * @return TaxonomyResource|bool|JsonResponse
     * @throws Throwable
     */
    public function storeTaxonomy($title, $type, $response = false)
    {
        try {
            $taxonomy = Taxonomy::where([
                ['title', $title],
                ['type', $type],
                ['company_id', $this->getAuthCompanyId()]
            ])
                ->first();
            if (!isset($taxonomy)) {
                $taxonomy = new Taxonomy();
                $taxonomy->company_id = $this->getAuthCompanyId();
                $taxonomy->title = $title;
                $taxonomy->type = $type;
                $taxonomy->color = $taxonomy->getColor();
                $taxonomy->saveOrFail();
            }
            return $response ? TaxonomyResource::make($taxonomy) : $taxonomy->id;
        } catch (Exception $ex) {
            // return Master::exceptionResponse('taxonomy', ['error' => $ex->getMessage()]);
        }
        return $response ? Master::errorResponse('taxonomy') : false;
    }

    /**
     * Edita un taxonomy
     *
     * @return JsonResponse
     */
    public function updateTaxonomy()
    {
        try {
            $taxonomy = Taxonomy::where([
                ['id', $this->request->input('id')],
                ['type', $this->request->input('type')],
                ['company_id', $this->getAuthCompanyId()]
            ])
                ->firstOrFail();
            $taxonomy->title = $this->request->input('title');
            $taxonomy->color = $this->request->input('color');
            $taxonomy->saveOrFail();
            return Master::successResponse();
        } catch (\Exception $ex) {
            // return Master::exceptionResponse('taxonomy_update', ['e' => $ex->getMessage()]);
        }
        return Master::errorResponse('taxonomy_update', 'update');
    }

    /**
     * Elimina un taxonomy
     *
     * @param $id
     * @return JsonResponse
     */
    public function deleteTaxonomy($id)
    {
        try {
            $taxonomy = Taxonomy::where([
                ['id', $id],
                ['company_id', $this->getAuthCompanyId()]
            ])
                ->firstOrFail();
            $taxonomy->users()->sync([]);
            $taxonomy->delete();
            return Master::successResponse();
        } catch (\Exception $ex) {
            // return Master::exceptionResponse('taxonomy_delete', ['e' => $ex->getMessage()]);
        }
        return Master::errorResponse('taxonomy_delete', 'delete');
    }

    private function syncUserFilter($users, $type)
    {
        $deSync = $users->filter(function ($item, $key) use ($type) {
            if (!empty($item['taxonomy'])) {
                foreach ($item['taxonomy'] as $key => $tax) {
                    return $tax['type'] === $type;
                }
            }
            return false;
        });
        $deSync = $deSync->map(function ($item) {
            $tax = collect($item['taxonomy']);
            return [
                'user_id' => $item['id'],
                'taxonomy_id' => $tax->pluck('id')->first()
            ];
        });
        return $deSync;
    }

    /**
     * Sincroniza un taxonomy con una lista de usuarios (agregar/eliminar)
     *
     * @return JsonResponse
     */
    public function syncTaxonomyUsers()
    {
        try {
            // Se optiene el arreglo de usuarios y se convierte en collection
            $users = collect($this->request->input('users'));
            // Tipo de taxonomy
            $type = $this->request->input('type');
            // option - 1 = remover, 2 = agregar
            $option = intval($this->request->input('option'));

            // Optener modelo de taxonomy
            $taxonomy = Taxonomy::where([
                ['id', $this->request->input('id')],
                ['company_id', $this->getAuthCompanyId()]
            ])
                ->firstOrFail();

            // Se remueven los usuarios
            if ($option === 1) {
                $taxonomy->users()->detach($users->pluck('id'));
            } else {
                // Se extraen los usuarios que tengan area/posicion
                if ($type === 'company_area' || $type === 'company_position') {
                    $deSync = $this->syncUserFilter($users, $type);
                    // si previamente pertenecia a otra area/posicion
                    // se eliminan de esta
                    foreach ($deSync as $key => $item) {
                        $tax = Taxonomy::where([
                            ['id', $item['taxonomy_id']],
                            ['company_id', $this->getAuthCompanyId()]
                        ])
                            ->firstOrFail();
                        $tax->users()->detach($item['user_id']);
                    }
                }
                // Se agregan los nuevos usuarios al taxonomy
                $taxonomy->users()->attach($users->pluck('id'));
            }
            return Master::successResponse();
        } catch (Exception $ex) {
            /*return Master::exceptionResponse(
                'taxonomy_sync_users',
                ['e' => $ex->getMessage()]
            );*/
        }
        return Master::errorResponse('taxonomy_sync_users');
    }

    /**
     * Preparar almacen de nuevos taxonomy
     *
     * @param $data
     * @param $type
     * @return array|\Illuminate\Support\Collection
     * @throws Throwable
     */
    public function setTaxonomy($data, $type)
    {
        if (empty($data)) {
            return $data;
        }
        $data = collect($data);
        $ids = $data->filter(function ($value, $key) {
            return is_numeric($value);
        });
        $strings = $data->filter(function ($value, $key) {
            return !is_numeric($value);
        });
        foreach ($strings as $string) {
            $id = $this->storeTaxonomy($string, $type);
            if ($id) {
                $ids->push($id);
            }
        }
        return $ids->toArray();
    }

    /**
     * Obtiene el listado completo de taxonomy
     *
     * @param bool $paginate
     * @return JsonResponse
     */
    public function getByTaxonomy($paginate = false)
    {
        return Master::successResponse([
            'taxonomy_areas' => $this->getTaxonomyAreas($paginate),
            'taxonomy_abilities' => $this->getTaxonomyAbility($paginate),
            'taxonomy_teams' => $this->getTaxonomyTeams($paginate),
            'taxonomy_positions' => $this->getTaxonomyPositions($paginate),
            'taxonomy_certifications' => $this->getTaxonomyCertifications($paginate),
            'taxonomy_customs' => $this->getTaxonomyCustom($paginate)
        ]);
    }

    /**
     * Lista de taxonomy filtrados por tags
     *
     * @param bool $paginate
     * @return AnonymousResourceCollection
     */
    public function getTaxonomyTags($paginate = false)
    {
        $taxonomy = Taxonomy::where('company_id', $this->getAuthCompanyId())
            ->Tags();
        if ($this->request->filled('search')) {
            $taxonomy->search($this->request->input('search'));
        }
        $taxonomy->OrderByTitle();
        return TaxonomyResource::collection($paginate ? $taxonomy->paginate(15) : $taxonomy->get());
    }

    /**
     * Lista de taxonomy filtrados por ability
     *
     * @param bool $paginate
     * @return AnonymousResourceCollection
     */
    public function getTaxonomyAbility($paginate = false)
    {
        $taxonomy = Taxonomy::where('company_id', $this->getAuthCompanyId())
            ->Ability();
        if ($this->request->filled('search')) {
            $taxonomy->search($this->request->input('search'));
        }
        $taxonomy->OrderByTitle();
        return TaxonomyResource::collection($paginate ? $taxonomy->paginate(15) : $taxonomy->get());
    }

    /**
     * Lista de taxonomy filtrados por area
     *
     * @param bool $paginate
     * @return AnonymousResourceCollection
     */
    public function getTaxonomyAreas($paginate = false)
    {
        $taxonomy = Taxonomy::where('company_id', $this->getAuthCompanyId())
            ->CompanyArea();
        if ($this->request->filled('search')) {
            $taxonomy->search($this->request->input('search'));
        }
        $taxonomy->OrderByTitle();
        return TaxonomyResource::collection($paginate ? $taxonomy->paginate(15) : $taxonomy->get());
    }

    /**
     * Busqueda de usuarios pertenecientes a un taxonomy
     *
     * @return AnonymousResourceCollection
     */
    public function getUserTaxonomy()
    {
        $user = User::whereHas('company', function ($query) {
            $query->where('id', $this->getAuthCompanyId());
        });

        if ($this->request->filled('search')) {
            $user->where(function ($query) {
                $query->where('name', 'like', "%{$this->request->input('search')}%")
                    ->orWhere('surname', 'like', "%{$this->request->input('search')}%");
            });
        }

        if ($this->request->input('option') == 1) {
            $user->whereHas('taxonomy', function ($query) {
                $query->where([
                    ['id', $this->request->input('id')],
                    ['company_id', $this->getAuthCompanyId()]
                ]);
            });
        } else {
            $user->whereDoesntHave('taxonomy', function ($query) {
                $query->where([
                    ['id', $this->request->input('id')],
                    ['company_id', $this->getAuthCompanyId()]
                ]);
            });
        }

        if ($this->request->filled('experience')) {
            $user->whereDoesntHave('licences', function ($query) {
                $query->where('experience_id', $this->request->input('experience'));
            });
            return SimpleUserResource::collection($user->get());
        }

        // $user->orderBy('name', 'asc');

        return UserTaxonomyResource::collection($user->paginate($this->request->input('per_page', 15)));
    }

    /**
     * Lista de taxonomy filtrados por positions
     *
     * @param bool $paginate
     * @return AnonymousResourceCollection
     */
    public function getTaxonomyPositions($paginate = false)
    {
        $taxonomy = Taxonomy::where('company_id', $this->getAuthCompanyId())
            ->CompanyPosition();
        if ($this->request->filled('search')) {
            $taxonomy->search($this->request->input('search'));
        }
        $taxonomy->OrderByTitle();
        return TaxonomyResource::collection($paginate ? $taxonomy->paginate(15) : $taxonomy->get());
    }

    /**
     * Lista de taxonomy filtrados por teams
     *
     * @param bool $paginate
     * @return AnonymousResourceCollection
     */
    public function getTaxonomyTeams($paginate = false)
    {
        $taxonomy = Taxonomy::where('company_id', $this->getAuthCompanyId())
            ->Teams();
        if ($this->request->filled('search')) {
            $taxonomy->search($this->request->input('search'));
        }
        $taxonomy->OrderByTitle();
        return TaxonomyResource::collection($paginate ? $taxonomy->paginate(15) : $taxonomy->get());
    }

    /**
     * Lista de taxonomy filtrados por certifications
     *
     * @param bool $paginate
     * @return AnonymousResourceCollection
     */
    public function getTaxonomyCertifications($paginate = false)
    {
        $taxonomy = Taxonomy::where('company_id', $this->getAuthCompanyId())
            ->Certifications();
        if ($this->request->filled('search')) {
            $taxonomy->search($this->request->input('search'));
        }
        $taxonomy->OrderByTitle();
        return TaxonomyResource::collection($paginate ? $taxonomy->paginate(15) : $taxonomy->get());
    }

    /**
     * Lista de taxonomy filtrados por custom
     *
     * @param bool $paginate
     * @return AnonymousResourceCollection
     */
    public function getTaxonomyCustom($paginate = false)
    {
        $taxonomy = Taxonomy::where('company_id', $this->getAuthCompanyId())
            ->Custom();
        if ($this->request->filled('search')) {
            $taxonomy->search($this->request->input('search'));
        }
        $taxonomy->OrderByTitle();
        return TaxonomyResource::collection($paginate ? $taxonomy->paginate(15) : $taxonomy->get());
    }

    /**
     * Sincronizar de taxonomy con users
     *
     * @param null $id
     * @param bool $response
     * @return bool|JsonResponse|AnonymousResourceCollection
     * @throws Throwable
     */
    public function saveUserTaxonomy($id = null, $response = false)
    {
        try {
            $user_id = isset($id) ? $id : $this->request->input('id');
            $user_id = User::findOrFail($user_id);
            // Asignar taxonomy, si esta viene vacia, se pregunta por el "clear" de lo contrario toma las del modelo

            // Taxonomy_area
            if ($this->request->filled('taxonomy_areas') || $this->request->input('empty_area', false)) {
                $areas = $this->setTaxonomy($this->request->input('taxonomy_areas', []), self::COMPANY_AREA);
            } else {
                $areas = $user_id->taxonomyArea->pluck('id');
            }

            // Taxonomy_ability
            if ($this->request->filled('taxonomy_abilities') || $this->request->input('empty_ability', false)) {
                $abilities = $this->setTaxonomy($this->request->input('taxonomy_abilities', []), self::ABILITY);
            } else {
                $abilities = $user_id->taxonomyAbility->pluck('id');
            }

            // Taxonomy_team
            if ($this->request->filled('taxonomy_teams') || $this->request->input('empty_team', false)) {
                $teams = $this->setTaxonomy($this->request->input('taxonomy_teams', []), self::TEAM);
            } else {
                $teams = $user_id->taxonomyTeams->pluck('id');
            }

            // Taxonomy_position
            if ($this->request->filled('taxonomy_positions') || $this->request->input('empty_position', false)) {
                $positions = $this->setTaxonomy(
                    $this->request->input('taxonomy_positions', []),
                    self::COMPANY_POSITION
                );
            } else {
                $positions = $user_id->taxonomyPosition->pluck('id');
            }

            // Taxonomy_certification
            if ($this->request->filled('taxonomy_certifications')
                || $this->request->input('empty_certification', false)
            ) {
                $certifications = $this->setTaxonomy(
                    $this->request->input('taxonomy_certifications', []),
                    self::CERTIFICATION
                );
            } else {
                $certifications = $user_id->taxonomyCertifications->pluck('id');
            }

            // Taxonomy_custom
            if ($this->request->filled('taxonomy_customs') || $this->request->input('empty_custom', false)) {
                $customs = $this->setTaxonomy($this->request->input('taxonomy_customs', []), self::CUSTOM);
            } else {
                $customs = $user_id->taxonomyCustoms->pluck('id');
            }

            $ids = array_merge($areas, $abilities, $teams, $positions, $certifications, $customs);
            $user_id->taxonomy()->sync($ids);
            return $response ? TaxonomyResource::collection($user_id->taxonomy) : true;
        } catch (Exception $ex) {
            // return Master::exceptionResponse('session_taxonomy', ['error' => $ex->getMessage()]);
        }
        return $response ? Master::errorResponse('sync_user_taxonomy', 'update') : false;
    }

    /**
     * Sincronizar de taxonomy con sessions
     *
     * @param null $id
     * @param bool $response
     * @return bool|JsonResponse|AnonymousResourceCollection
     * @throws Throwable
     */
    public function saveSessionTaxonomy($id = null, $response = false)
    {
        try {
            $session_id = isset($id) ? $id : $this->request->input('id');
            $session = Session::findOrFail($session_id);
            // Asignar taxonomy, si esta viene vacia, se pregunta por el "clear" de lo contrario toma las del modelo

            // taxonomy_ability
            if ($this->request->filled('abilities') || $this->request->input('empty_ability', false)) {
                $abilities = $this->setTaxonomy($this->request->input('abilities', []), self::ABILITY);
            } else {
                $abilities = $session->taxonomyAbility->pluck('id');
            }

            // taxonomy_tag
            if ($this->request->filled('tags') || $this->request->input('empty_tags', false)) {
                $tags = $this->setTaxonomy($this->request->input('tags', []), self::TAG);
            } else {
                $tags = $session->taxonomyTags->pluck('id');
            }
            $ids = array_merge($abilities, $tags);
            $session->taxonomy()->sync($ids);
            return $response ? TaxonomyResource::collection($session->taxonomy) : true;
        } catch (Exception $ex) {
            // return Master::exceptionResponse('session_taxonomy', ['error' => $ex->getMessage()]);
        }
        return $response ? Master::errorResponse('sync_session_taxonomy', 'update') : false;
    }

    /**
     * Sincronizar de taxonomy con experiencias
     *
     * @param null $id
     * @param bool $response
     * @return bool|JsonResponse|AnonymousResourceCollection
     * @throws Throwable
     */
    public function saveExperienceTaxonomy($id = null, $response = false)
    {
        try {
            $experience_id = isset($id) ? $id : $this->request->input('id');
            $experience = Experience::findOrFail($experience_id);
            // Asignar taxonomy, si esta viene vacia, se pregunta por el "clear" de lo contrario toma las del modelo

            // taxonomy_ability
            if ($this->request->filled('abilities') || $this->request->input('empty_ability', false)) {
                $abilities = $this->setTaxonomy($this->request->input('abilities', []), self::ABILITY);
            } else {
                $abilities = $experience->taxonomyAbility->pluck('id');
            }
            // taxonomy_tag
            if ($this->request->filled('tags') || $this->request->input('empty_tags', false)) {
                $tags = $this->setTaxonomy($this->request->input('tags', []), self::TAG);
            } else {
                $tags = $experience->taxonomyTags->pluck('id');
            }
            $ids = array_merge($abilities, $tags);
            $experience->taxonomy()->sync($ids);
            return $response ? TaxonomyResource::collection($experience->taxonomy) : true;
        } catch (Exception $ex) {
            // return Master::exceptionResponse('experience_taxonomy', ['error' => $ex->getMessage()]);
        }
        return $response ? Master::errorResponse('sync_experience_taxonomy', 'update') : false;
    }
}
