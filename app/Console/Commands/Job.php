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

    		$subject = date('Y-m-d H:i').'-(修正链接)最新工作列表';
    		$message->subject($subject);
    		$message->from('phpython@163.com', 'phpython');
    		$message->to($sendto, 'SimonYu')->cc($cc);
    	});
    	
    	Log::info('执行了一次操作！');
    	exit(0);
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
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		$headers = [];
		$headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
		$headers[] = 'Accept-Language: zh-CN,zh;q=0.8';
        $headers[] = 'Accept-Encoding:gzip, deflate, sdch';
        $headers[] = 'Cache-Control: no-cache';
        $headers[] = 'Connection:keep-alive';
        $headers[] = 'Cookie:guid=14476841497497720052; guide=1; 51job=cenglish%3D0; search=jobarea%7E%60040000%7C%21ord_field%7E%600%7C%21list_type%7E%600%7C%21recentSearch0%7E%602%A1%FB%A1%FA040000%2C00%A1%FB%A1%FA000000%A1%FB%A1%FA0000%A1%FB%A1%FA00%A1%FB%A1%FA9%A1%FB%A1%FA99%A1%FB%A1%FA99%A1%FB%A1%FA99%A1%FB%A1%FA99%A1%FB%A1%FA99%A1%FB%A1%FA99%A1%FB%A1%FAIC+%B4%A5%C3%FE%A1%FB%A1%FA2%A1%FB%A1%FA%A1%FB%A1%FA-1%A1%FB%A1%FA1447684849%A1%FB%A1%FA0%A1%FB%A1%FA%7C%21recentSearch1%7E%602%A1%FB%A1%FA040000%2C00%A1%FB%A1%FA000000%A1%FB%A1%FA0000%A1%FB%A1%FA00%A1%FB%A1%FA9%A1%FB%A1%FA99%A1%FB%A1%FA99%A1%FB%A1%FA99%A1%FB%A1%FA99%A1%FB%A1%FA99%A1%FB%A1%FA99%A1%FB%A1%FA+PM%A1%FB%A1%FA0%A1%FB%A1%FA%A1%FB%A1%FA-1%A1%FB%A1%FA1447684799%A1%FB%A1%FA0%A1%FB%A1%FA%7C%21recentSearch2%7E%602%A1%FB%A1%FA040000%2C00%A1%FB%A1%FA000000%A1%FB%A1%FA0000%A1%FB%A1%FA00%A1%FB%A1%FA9%A1%FB%A1%FA99%A1%FB%A1%FA99%A1%FB%A1%FA99%A1%FB%A1%FA99%A1%FB%A1%FA99%A1%FB%A1%FA99%A1%FB%A1%FATP%A1%FB%A1%FA0%A1%FB%A1%FA%A1%FB%A1%FA-1%A1%FB%A1%FA1447684565%A1%FB%A1%FA0%A1%FB%A1%FA%7C%21recentSearch3%7E%602%A1%FB%A1%FA040000%2C00%A1%FB%A1%FA000000%A1%FB%A1%FA0000%A1%FB%A1%FA00%A1%FB%A1%FA9%A1%FB%A1%FA99%A1%FB%A1%FA99%A1%FB%A1%FA99%A1%FB%A1%FA99%A1%FB%A1%FA99%A1%FB%A1%FA99%A1%FB%A1%FATP++IC+%CF%EE%C4%BF%A1%FB%A1%FA2%A1%FB%A1%FA%A1%FB%A1%FA-1%A1%FB%A1%FA1447684833%A1%FB%A1%FA0%A1%FB%A1%FA%7C%21recentSearch4%7E%602%A1%FB%A1%FA040000%2C00%A1%FB%A1%FA000000%A1%FB%A1%FA0000%A1%FB%A1%FA00%A1%FB%A1%FA9%A1%FB%A1%FA99%A1%FB%A1%FA99%A1%FB%A1%FA99%A1%FB%A1%FA99%A1%FB%A1%FA99%A1%FB%A1%FA99%A1%FB%A1%FAa%A1%FB%A1%FA0%A1%FB%A1%FA%A1%FB%A1%FA-1%A1%FB%A1%FA1447686662%A1%FB%A1%FA0%A1%FB%A1%FA%7C%21';
        $headers[] = 'Cache-Control: no-cache';
		$headers[] = 'Pragma:no-cache';
        $headers[] = 'Upgrade-Insecure-Requests:1';
		$headers[] = 'Content-Type: charset=utf-8';
		$headers[] = 'Host: search.51job.com';
		$headers[] = 'Referer: http://search.51job.com/jobsearch/search_result.php?fromJs=1&jobarea=040000%2C00&district=000000&funtype=0000&industrytype=00&issuedate=9&providesalary=99&keyword=IC%20%E8%A7%A6%E6%91%B8&keywordtype=2&curr_page=4&lang=c&stype=2&postchannel=0000&workyear=99&cotype=99&degreefrom=99&jobterm=99&companysize=99&lonlat=0%2C0&radius=-1&ord_field=0&list_type=0&fromType=14&dibiaoid=0&confirmdate=9';
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
        /////////测试抓取效果
        //Log::warning('curl res'.$res);
        //exit();
        ////////
		curl_close($ch);
		return $res;
    }
    
    /**
     * 从HTML中解析出所有的数据
     * 正则匹配到的结果：
     * 1 =>
    array (
    0 => 'http://jobs.51job.com/shenzhen-nsq/53473657.html',
    ),
    2 =>
    array (
    0 => '单片机工程师',
    ),
    3 =>
    array (
    0 => 'http://jobs.51job.com/shenzhen-nsq/co1994273.html',
    ),
    4 =>
    array (
    0 => '深圳市艾博德科技股份有限公司',
    ),
    5 =>
    array (
    0 => '深圳-南山区',
    ),
    6 =>
    array (
    0 => '15-20万/年',
    ),
    7 =>
    array (
    0 => '11-06',
    ),
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
    			$datetime = Carbon::createFromFormat('m-d', $res[7][$i])->toDateTimeString();
    			$ret[] = [
                    trim($res[1][$i]),//url
                    $this->clearStr($res[2][$i]),//岗位名称
                    trim($res[3][$i]),//公司url
                    $this->clearStr($res[4][$i]),//公司名称
                    $this->clearStr($res[5][$i]),//工作地点
                    $datetime,
                    $this->clearStr($res[6][$i]),//薪资
                    $this->clearStr($res[7][$i]),//时间
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
    			if($this->filterTitle($info[1]) === false) continue;//过滤不符合的标题
    			if(!Data::where('title', '=', $info[1])->where('remark2', $info[3])->exists()){//不存在则插入
   					$model = new Data();
   					$model->title = $info[1];//岗位名称
                    $model->url = $info[0];
   					$model->remark1 = $info[2];//公司链接
                    $model->remark2 = $info[3];//公司
                    $model->remark3 = $info[4];//工作地点
                    $model->remark4 = $info[5];//发布时间
                    $model->content = $info[6];//薪资
                    $model->desc = $info[7];//发布时间 raw
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
    
    /**
     * 过滤不符合条件的标题
     * @param string $title
     * @return boolean
     */
    public function filterTitle($title = ''){
    	if(!$title) return false;
    	 $flag = true;
    	foreach(Config::get('site51.filterTitle') as $keyword){
    		if(strpos($title, $keyword) !== false){
    			$flag = false;
    			break;
    		}
    	}
    	 
    	return $flag;
    }
}
