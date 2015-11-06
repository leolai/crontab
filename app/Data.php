<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
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
	
	/**
	 * 今天的
	 */
	public static function today(){
		$today = Carbon::today();
		return self::where('created_at', '>=', $today->timestamp)->paginate(20);
	}
	
	/**
	 * 查询过去一小时
	 */
	public static function lasthour(){
		$lasthour = (new Carbon('last hour'))->timestamp;
		return self::where('created_at', '>=', $lasthour)->paginate(20);
	}
	
	/**
	 * 查询所有
	 */
	public static function alls(){
		return self::orderBy('id', 'DESC')->paginate(20);
	}
}
