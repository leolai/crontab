<?php
/**
 * 列表
 */
return [
    'sites' => [
    		'http://u.oa.com/train/index.php',
    ],
	//匹配列表项
	'listPattern'=>'|<li.*>[\s\S]+<span\sclass="date">(\d+-\d+-\d+)<\/span><a\starget="_blank"\shref="course\.php\?id=\d+">(.*)<\/a>|U',
	//
	'contentPattern'=>'',
];
