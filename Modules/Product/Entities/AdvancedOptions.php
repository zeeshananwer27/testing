<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;

class AdvancedOptions extends Model
{
	protected $table = 'advanced_options';

	protected $primaryKey = 'id';

	protected $fillable = ['product_id', 'label', 'price', 'advanced_color', 'quantity', 'fee', 'purchase_limit'];

}
