<?php

namespace Apithy\Services;

use Apithy\Ability;
use Apithy\Experience;
use Apithy\Session;
use Apithy\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

/**
 * Class RegisterService
 * @package Apithy\Services
 */
class SessionsService
{

    private $taxonomy_service;

    public function __construct()
    {
        $this->taxonomy_service = new TaxonomyService(request());
    }

    /**
     * Create a experience.
     *
     * @throws \Exception
     * @param array $data
     * @return \Apithy\User
     */
    public function createSession(Request $request, $experience)
    {
        $data = $request->all();

        unset($data['cover']);

        if (isset($data["status"])) {
            $data["status"] = ($data["status"] == "on") ? Experience::STATUS_PUBLISHED : Experience::STATUS_INACTIVE;
        } else {
            $data["status"] = Experience::STATUS_PUBLISHED;
        }

        if (isset($data["author"])) {
            if (!$data['author']) {
                $data['author'] = Session::AUTHOR_DEFAULT;
            }
        } else {
            $data['author'] = Session::AUTHOR_DEFAULT;
        }

        //Validate if we have a previous Experience Draft created, and load the object and set the new data
        if ($request->get("experience_draft_id", null)) {
            $session = Session::find($request->get("experience_draft_id"));
            $session->fill($data);
        } else {
            $session = new Session($data);
        }

        $session->experience_id = $experience->id;

        if ($session->save()) {
            $file = $request->file('cover');

            if ($file) {
                $original = $file->getFileInfo()->getPathname();

                /* @var \Intervention\Image\Image $image */
                $image = Image::make($original);

                $image->resize(640, null, function ($constraint) {
                    $constraint->aspectRatio();
                });

                $image->save($original);

                $extension = $file->extension();

                $name = sprintf('%s.%s', $experience->id, $extension);

                $path = $file->storeAs('sessions_covers', $name, ['visibility' => 'public']);

                if ($path) {
                    $session->cover = $path;
                    $session->save();
                }
            }
            $this->taxonomy_service->saveSessionTaxonomy($session->id);
            // $this->saveAttribute($data, 'abilities', Ability::class, $session);
            // $this->saveAttribute($data, 'tags', Tag::class, $session);
        }

        return $session->fresh();
    }

    /**
     * Update a experience.
     *
     * @throws \Exception
     * @param array $data
     * @return \Apithy\User
     */
    public function updateSession(Request $request, $session)
    {
        $data = $request->all();

        unset($data['cover']);

        if (isset($data["status"])) {
            $data["status"] = ($data["status"] == "on") ? 1 : 0;
        } else {
            $data["status"] = 1;
        }

        if (isset($data["author"])) {
            if (!$data['author']) {
                $data['author'] = 1;
            }
        } else {
            $data['author'] = 1;
        }

        $session->fill($data);

        if ($session->save()) {
            if (isset($data['companies'])) {
                $session->companies()->sync($data['companies']);
                //Create the Event CompanyexperienceCreated
            }

            $file = $request->file('cover');

            if ($file) {
                $original = $file->getFileInfo()->getPathname();

                /* @var \Intervention\Image\Image $image */
                $image = Image::make($original);

                $image->resize(640, null, function ($constraint) {
                    $constraint->aspectRatio();
                });

                $image->save($original);

                $extension = $file->extension();

                $name = sprintf('%s.%s', $session->id, $extension);

                $path = $file->storeAs('experience_covers', $name, ['visibility' => 'public']);

                if ($path) {
                    $session->cover = $path;
                    $session->save();
                }
            }
            $this->taxonomy_service->saveSessionTaxonomy($session->id);
            // $this->saveAttribute($data, 'abilities', Ability::class, $session);
            // $this->saveAttribute($data, 'tags', Tag::class, $session);
        }

        return $session->fresh();
    }

    /**
     * Create a experience draft.
     *
     * @throws \Exception
     * @param array $data
     * @return \Apithy\User
     */
    public function createSessionDraft($experience)
    {
        $sessionDraft = new Session();
        $sessionDraft->status = Session::STATUS_DRAFT;
        $sessionDraft->author = Auth::user()->id;
        $sessionDraft->experience_id = $experience->id;
        $sessionDraft->save();

        return $sessionDraft;
    }

    /**
     * Save o create an tag or ability
     *
     * @param array $data
     * @param string $target
     * @param class $class
     * @param object $session
     */
    public function saveAttribute($data, $target, $class, $session)
    {
        if (isset($data[$target])) {
            $array_of_items = [];
            foreach ($data[$target] as $key => $item) {
                $exist = $class::where('title', 'like', $item)->first();
                if (!$exist) {
                    $exist = new $class([
                        'title' => $item,
                        'company_id' => $session->experience->getCompanyAuthorAttribute()->id
                    ]);
                    $exist->save();
                }
                if ($exist) {
                    $array_of_items[] = $exist->id;
                }
            }
            if (is_callable(array($session, $target))) {
                $session->{$target}()->sync($array_of_items);
            }
        } else {
            if (is_callable(array($session, $target))) {
                $session->{$target}()->detach();
            }
        }
    }
}
