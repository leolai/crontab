<?php
/**
 * 列表
 */
return [
    'sites' => [
    		//TP
    		'http://search.51job.com/list/040000%252C00,%2B,%2B,%2B,%2B,%2B,TP,1,%2B.html?lang=c&stype=1&image_x=0&image_y=0&specialarea=00',
    		//触摸屏 PM
    		'http://search.51job.com/jobsearch/search_result.php?fromJs=1&jobarea=040000%2C00&funtype=0000&industrytype=00&keyword=%E8%A7%A6%E6%91%B8%E5%B1%8F%20PM&keywordtype=2&lang=c&stype=1&postchannel=0000&fromType=23',
    		//触摸屏 销售
    		'http://search.51job.com/jobsearch/search_result.php?fromJs=1&jobarea=040000%2C00&funtype=0000&industrytype=00&keyword=%E8%A7%A6%E6%91%B8%E5%B1%8F%20%E9%94%80%E5%94%AE&keywordtype=2&lang=c&stype=1&postchannel=0000&fromType=1',
    		//电子 项目 管理 PM
    		'http://search.51job.com/jobsearch/search_result.php?fromJs=1&jobarea=040000%2C00&funtype=0000&industrytype=00&keyword=%E7%94%B5%E5%AD%90%20%E9%A1%B9%E7%9B%AE%20%E7%AE%A1%E7%90%86%20PM&keywordtype=2&lang=c&stype=1&postchannel=0000&fromType=1',
    		//PM
    		'http://search.51job.com/jobsearch/search_result.php?fromJs=1&jobarea=040000%2C00&funtype=0000&industrytype=00&keyword=PM&keywordtype=1&lang=c&stype=1&postchannel=0000&fromType=1',
    		'http://search.51job.com/jobsearch/search_result.php?fromJs=1&jobarea=040000%2C00&district=000000&funtype=0000&industrytype=00&issuedate=9&providesalary=99&keyword=PM&keywordtype=1&curr_page=2&lang=c&stype=1&postchannel=0000&workyear=99&cotype=99&degreefrom=99&jobterm=01&companysize=99&lonlat=0%2C0&radius=-1&ord_field=0&list_type=0&fromType=14&dibiaoid=-1',
    		'http://search.51job.com/jobsearch/search_result.php?fromJs=1&jobarea=040000%2C00&district=000000&funtype=0000&industrytype=00&issuedate=9&providesalary=99&keyword=PM&keywordtype=1&curr_page=3&lang=c&stype=1&postchannel=0000&workyear=99&cotype=99&degreefrom=99&jobterm=01&companysize=99&lonlat=0%2C0&radius=-1&ord_field=0&list_type=0&fromType=14&dibiaoid=-1',
    		//TP  IC 项目
    		'http://search.51job.com/jobsearch/search_result.php?fromJs=1&jobarea=040000%2C00&funtype=0000&industrytype=00&keyword=TP%20%20IC%20%E9%A1%B9%E7%9B%AE&keywordtype=2&lang=c&stype=1&postchannel=0000&fromType=1',
    		//IC 触摸
    		'http://search.51job.com/jobsearch/search_result.php?fromJs=1&jobarea=040000%2C00&funtype=0000&industrytype=00&keyword=IC%20%E8%A7%A6%E6%91%B8&keywordtype=2&lang=c&stype=1&postchannel=0000&fromType=1',
    		'http://search.51job.com/jobsearch/search_result.php?fromJs=1&jobarea=040000%2C00&district=000000&funtype=0000&industrytype=00&issuedate=9&providesalary=99&keyword=IC%20%E8%A7%A6%E6%91%B8&keywordtype=2&curr_page=2&lang=c&stype=1&postchannel=0000&workyear=99&cotype=99&degreefrom=99&jobterm=01&companysize=99&lonlat=0%2C0&radius=-1&ord_field=0&list_type=0&fromType=14&dibiaoid=-1',
    		'http://search.51job.com/jobsearch/search_result.php?fromJs=1&jobarea=040000%2C00&district=000000&funtype=0000&industrytype=00&issuedate=9&providesalary=99&keyword=IC%20%E8%A7%A6%E6%91%B8&keywordtype=2&curr_page=3&lang=c&stype=1&postchannel=0000&workyear=99&cotype=99&degreefrom=99&jobterm=01&companysize=99&lonlat=0%2C0&radius=-1&ord_field=0&list_type=0&fromType=14&dibiaoid=-1',
    ],
	//匹配列表项
	'listPattern'=>'|<tr\s+class="tr0"[.\s\S]+<td\s+class="td1">.*href=(?#链接)"(.*\.html)".*class="jobname".*>(?#job name)(.*)<\/a>.*\/td>[\s\S]+<td\s+class="td2"><a\s+href=(?#公司链接)"(.*\.html)".*coname".*>(?#公司名)(.*)<\/a>.*\/td>[\s\S]+<td\s+class="td3"><span\s+id="map_jobarea.*>(?#company city)(.*)<\/span><\/td>[\s\S]+<td\s+class="td4"><span\s+id="map_fbrq.*>(?#pubtime)(.*)<\/span><\/td>[\s\S.]+<tr\s+class="tr1"[.\s\S]+<td\s+colspan="4".*>(?#经验学历要求)([.\s\S]+)<\/td>[.\s\S]+<tr\s+class="tr2"[\s\S]+<td\s+colspan="4".*>(?#工作简介)([\s\S]+)<\/td>[\s\S]+<tr\s+class="tr3">|U',
	//
	'contentPattern'=>'',
	'sendto'=>'377389068@qq.com',
	'cc'=>'phpython@163.com',
];
