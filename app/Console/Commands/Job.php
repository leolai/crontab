<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Mail;
use Log;
use Carbon\Carbon;
use App\Data;
use Config;
class Job extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '前程无忧工作';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	//取数据
    	$data = $this->getNews();
    	if(!$data || empty($data)){
    		Log::debug('没有新的信息');
    		return;
    	}
    	
    	Mail::send('emails.joblist', ['data'=>$data], function($message)
    	{
            $sendto = Config::get('site51.sendto');
            $cc = Config::get('site51.cc');

    		$subject = date('Y-m-d H:i').'-最新工作列表';
    		$message->subject($subject);
    		$message->from('phpython@163.com', 'phpython');
    		$message->to($sendto, 'SimonYu')->cc($cc);
    	});
    	
    	Log::info('执行了一次操作！');
    }
    
    /**
     * 获取最新的信息并返回结果供发送
     * 
     * @return array
     */
    public function getNews(){
    	$urls = Config::get('site51.sites');
    	if(!$urls || empty($urls)){
    	 	Log::warning('配置文件不存在！');
    	 	return false;
    	}
    	
    	$ret = [];//返回的结果数组
    	foreach ($urls as $url){
    		if(!$url){
    			Log::warning('站点配置错误');
    			continue;
    		}
    		//获取网页html内容
    		$res = $this->fetchWebRes(trim($url));
    		if(!$res) continue;
    		$res = iconv("gbk", "UTF-8", $res);//转码
    		//Log::warning($res);return false;
    		//从网页html内容中提取信息
    		$pureRes = $this->parseHtml($res);
    		
    		if(!$pureRes){
    			Log::warning('未解析到数据');
    			continue;
    		}
    		
    		$ret = array_merge($ret, $pureRes);
    	}
    	Log::warning('开始入库');
    	$ret = $this->store($ret);//入库 并且检查是否有重复 返回无重复的数据
    	
    	return $ret;
    }
    
    /**
     * 获取网页资源
     * @param string $url
     * @return mixed array|boolean
     */
    public function fetchWebRes($url = ''){
    	if(!$url) return false;
    	
    	$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		$headers = [];
		$headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
		$headers[] = 'Accept-Language: zh-CN,zh;q=0.8';
		$headers[] = 'Cache-Control: no-cache';
		$headers[] = 'Content-Type: charset=utf-8';
		$headers[] = 'Host: www.51job.com';
		$headers[] = 'Referer: http://www.51job.com/index.html';
		$headers[] = 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:28.0) Gecko/20100101 Firefox/28.0';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		
		$res = curl_exec($ch);
    	
		if(curl_errno($ch)){
			Log::warning('网页资源请求失败！ '.curl_error($ch));
			$res = '';
		}
		
		if(!$res || strlen($res) < 10){
			Log::warning('curl res'.$res);
			Log::warning('curl信息' . json_encode(curl_getinfo($ch), JSON_UNESCAPED_UNICODE));
			$res = '';
		}
		
		curl_close($ch);
		return $res;
    }
    
    /**
     * 从HTML中解析出所有的数据
     * 正则匹配到的结果：
     * Array
(
    [0] => Array
        (
            [0] => <span>2015-10-28《文字变形-玩字》</span>
            [1] => span>2015-10-29fa</span>
        )
    [1] => Array
        (
            [0] => 2015-10-28
            [1] => 2015-10-29
        )
    [2] => Array
        (
            [0] => 《文字变形-玩字》
            [1] => fa
        )
)
     * @param string $str
     * 
     * @return array
     * 格式为[[标题,时间],...]
     */
    public function parseHtml($str = ''){
    	if(!$str) return false;
    	
    	$pattern = Config::get('site51.listPattern');
    	if(!$pattern){
    		Log::warning('pattern不存在！');
    		return [];
    	}
    	
    	preg_match_all($pattern, $str, $res);

    	if($res && !empty($res)){
    		$ret = [];
    		$count = count($res[0]);//所有匹配到的行
    		for($i=0; $i<$count; $i++){
    			$datetime = Carbon::createFromFormat('Y-m-d', $res[6][$i])->toDateTimeString();
    			$ret[] = [
                    trim($res[1][$i]),//url
                    $this->clearStr($res[2][$i]),//岗位名称
                    trim($res[3][$i]),//公司url
                    $this->clearStr($res[4][$i]),//公司名称
                    $this->clearStr($res[5][$i]),//工作地点
                    $datetime,
                    $this->clearStr($res[7][$i]),//要求
                    $this->clearStr($res[8][$i]),//JD
                ];
    		}
    		return $ret;
    	}
    	return [];
    }
    
    /**
    *清除空格
    **/
    private function clearStr($str){
        $str = strip_tags($str);
        $qian=array(" ","　","\t","\n","\r","&nbsp;");
        return str_replace($qian, '', $str);   
    }

    /**
     * 存储入库
     * @param array $data
     * @return array
     */
    public function store($datas = []){
    	$ret = [];
    	foreach ($datas as $info){//站点=>[[title,time],]
    		try {
    			if(!Data::where('title', '=', $info[0])->where('remark2', $info[3])->exists()){//不存在则插入
   					$model = new Data();
   					$model->title = $info[1];//岗位名称
                    $model->url = $info[0];
   					$model->remark1 = $info[2];//公司链接
                    $model->remark2 = $info[3];//公司
                    $model->remark3 = $info[4];//工作地点
                    $model->remark4 = $info[5];//发布时间
                    $model->content = $info[6];//要求
                    $model->desc = $info[7];//jd
   					$model->save();
   					$ret[] = $info;
    			}
    				 
    		}catch(\Exception $e){
    			Log::error('数据库查询OR写入失败！'.$e->getTraceAsString());
    			continue;
    		}
    	}
    	return $ret;
    }
    
    /**
     * 发送邮件
     * @param array $data
     * @return void
     */
    public function sendMail($datas = []){
    	return true;
    }
    
    /**
     * 发送微信
     * @param array $data
     * @todo
     * @return void
     */
    public function sendWeixin($datas = []){
    	
    }
}
