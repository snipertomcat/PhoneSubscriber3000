<?php

namespace Apithy;

use Apithy\Utilities\Master\Master;
use Illuminate\Database\Eloquent\Model;

class Taxonomy extends Model
{
    protected $table = 'taxonomy';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'company_id' => 'integer',
        'title' => 'string',
        'color' => 'string',
        'type' => 'string'
    ];

    protected $fillable = ['title', 'type'];

    /**
     * Lista de colores que se asignan de forma aleatoria
     * al guardar un Taxonomy
     */
    const COLORS = [
        '#B6C5DC',
        '#E1F3FF',
        '#CA5E61',
        '#BFC7B1',
        '#106CFB',
        '#42B3FF',
        '#FF5E63',
        '#FF824C',
        '#FFBE56',
        '#C6E98C',
        '#59D694',
        '#6FAAFB',
        '#8FD2FE',
        '#FE9EA2',
        '#FEB396',
        '#FFD79D',
        '#FFE89E',
        '#DEF1BC',
        '#9DE5C0'
    ];

    const TYPE = [
        'company_area',
        'company_position',
        'certifications',
        'teams',
        'custom',
        'tag',
        'ability'
    ];

    /**
     * Reglas que se aplican al guardar un tag
     *
     * @var array
     */
    public static $insertRules = [
        'title' => 'required|string|max:191',
        'type' => 'required|in:company_area,company_position,certifications,teams,custom,tag,ability'
    ];

    // RELATIONS

    /**
     * Relación a experiencias
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function experiences()
    {
        return $this->belongsToMany(Experience::class, 'taxonomy_experiences');
    }

    /**
     * Relación a sesiones
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function sessions()
    {
        return $this->belongsToMany(Session::class, 'taxonomy_sessions');
    }

    /**
     * Relacion a compañia
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Relación a usuarios
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'taxonomy_users');
    }

    // SCOPES

    /**
     * Busqueda por nombre.
     *
     * @param $query
     * @param $search
     * @return mixed
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('title', 'like', "%{$search}%");
    }

    /**
     * Ordenamiento por title
     *
     * @param $query
     * @param string $order
     * @return mixed
     */
    public function scopeOrderByTitle($query, $order = 'asc')
    {
        return $query->orderBy('title', $order);
    }

    /**
     * Filtrado por Areas
     *
     * @param $query
     * @return mixed
     */
    public function scopeCompanyArea($query)
    {
        return $query->where('type', 'company_area');
    }

    /**
     * filtrado por posiciones
     *
     * @param $query
     * @return mixed
     */
    public function scopeCompanyPosition($query)
    {
        return $query->where('type', 'company_position');
    }

    /**
     * Filtrado por certificaciones
     *
     * @param $query
     * @return mixed
     */
    public function scopeCertifications($query)
    {
        return $query->where('type', 'certifications');
    }

    /**
     * Filtrado por equipos
     *
     * @param $query
     * @return mixed
     */
    public function scopeTeams($query)
    {
        return $query->where('type', 'teams');
    }

    /**
     * Filtrado por default (sin clasificar)
     *
     * @param $query
     * @return mixed
     */
    public function scopeCustom($query)
    {
        return $query->where('type', 'custom');
    }

    /**
     * Filtrado por ability
     *
     * @param $query
     * @return mixed
     */
    public function scopeAbility($query)
    {
        return $query->where('type', 'ability');
    }

    /**
     * Filtrado por tags
     *
     * @param $query
     * @return mixed
     */
    public function scopeTags($query)
    {
        return $query->where('type', 'tag');
    }

    // FUNCTIONS

    public function getColor()
    {
        $size = count(self::COLORS) - 1;
        $index = random_int(0, $size);
        return self::COLORS[$index];
    }

    public static function inColorList()
    {
        return implode(',', self::COLORS);
    }

    public static function inTypeList()
    {
        return implode(',', self::TYPE);
    }

    // SETTERS

    public function setTitleAttribute($title)
    {
        $this->attributes['title'] = Master::trimmed($title, true);
    }
}
