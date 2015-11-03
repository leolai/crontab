<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
	protected $table = 'datas';
	//protected $fillable = ['title'];
	/**
	 * 查询是否有同名的存在 存在返回true不存在返回false
	 * @param string $title
	 * @return boolean
	 */
    public static function existTitle($title = ''){
    	$model = new self();
    	if($model->where('title', '=', $title)->exists()){
    		return true;
    	}
    	
    	return false;
	}
}
