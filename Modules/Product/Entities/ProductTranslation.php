<?php

namespace Modules\Product\Entities;

use Modules\Support\Eloquent\TranslationModel;

class ProductTranslation extends TranslationModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'short_description','organizer',  'address', 'city', 'state', 'zip_code', 'lat', 'lng'];
}
