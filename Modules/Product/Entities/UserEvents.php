<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;

class UserEvents extends Model
{
	protected $table = 'user_events';

	protected $primaryKey = 'user_id';

	protected $fillable = ['user_id','product_id'];

}
