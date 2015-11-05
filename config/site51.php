<?php
/**
 * 列表
 */
return [
    'sites' => [
    		'http://search.51job.com/list/040000%252C00,%2B,%2B,%2B,%2B,%2B,TP,1,%2B.html?lang=c&stype=1&image_x=0&image_y=0&specialarea=00',
    ],
	//匹配列表项
	'listPattern'=>'|<tr\s+class="tr0"[.\s\S]+<td\s+class="td1">.*href=(?#链接)"(.*\.html)".*class="jobname".*>(?#job name)(.*)<\/a>.*\/td>[\s\S]+<td\s+class="td2"><a\s+href=(?#公司链接)"(.*\.html)".*coname".*>(?#公司名)(.*)<\/a>.*\/td>[\s\S]+<td\s+class="td3"><span\s+id="map_jobarea.*>(?#company city)(.*)<\/span><\/td>[\s\S]+<td\s+class="td4"><span\s+id="map_fbrq.*>(?#pubtime)(.*)<\/span><\/td>[\s\S.]+<tr\s+class="tr1"[.\s\S]+<td\s+colspan="4".*>(?#经验学历要求)([.\s\S]+)<\/td>[.\s\S]+<tr\s+class="tr2"[\s\S]+<td\s+colspan="4".*>(?#工作简介)([\s\S]+)<\/td>[\s\S]+<tr\s+class="tr3">|U',
	//
	'contentPattern'=>'',
];
