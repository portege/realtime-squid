<?php
include_once('sqstat.class.php');
include_once('config.php');

$squidclass=new squidstat();

foreach ($block_ip as $ips){
	$peer[$ips]=0;
}

//die(print_r($peer));
if(!$squidclass->connect($squidhost,$squidport)) {
        $squidclass->showError();
        exit(1);
}
$data=$squidclass->makeQuery($cachemgr_passwd);
if($data==false){
        $squidclass->showError();
        exit(2);
}

foreach ($data['con'] as $d=>$i){
	$e = explode('.',$i['peer']);
	$ip=$e[0].'.'.$e[1].'.'.$e[2];
	@$peer[$ip]=$peer[$ip]+$i['bytes'];
}

$str = array();
foreach ($peer as $ip=>$data){
	$str[] = $ip.'^'.$data;
}
//sort($str);
//echo implode('--',$str);
//print_r($peer);
//print_r($str);
echo (int)$peer[$_GET['i']];
?>
