<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends \Eloquent
{
    //范围查询
	public function scopeWithOnly($query, $relation, Array $columns)
	{
		return $query->with([$relation => function ($query) use ($columns){
			$query->select($columns);
		}]);
	}
}
