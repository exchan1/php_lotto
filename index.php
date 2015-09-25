<?php
include 'class.snoopy.php';
header("Content-Type: text/html; charset=UTF-8");
echo($_GET['kai'].'회차<br />');

$snoopy = new snoopy;
$getUrl = "http://www.645lotto.net/lotto645Confirm.do?method=byWin&drwNo=".$_GET['kai'];
$snoopy->fetch($getUrl);

$pattern='/<option value="[0-9]*"\s*>(.*?)<\/option>/';
preg_match_all($pattern,$snoopy->results,$out);

$select = '';
echo('<select onchange="location.href=\'?kai=\'+this.value">');
echo('<option value="">선택</option>');
for( $i=0 ; $i < sizeof($out[0]) ; $i++ ){
	echo($out[0][$i]);
}
echo('</select>');

echo('<p>');
$pattern='/img src="\/img\/common\/ball_[0-9]*.png/';
preg_match_all($pattern,$snoopy->results,$out);
for($i=0;$i<=6;$i++){
	$num[$i]=str_replace(".png","",str_replace('img src="/img/common/ball_',"",$out[0][$i]));
	echo(($i==6) ? '보너스 : ' : '');
	echo($num[$i].'<br />');
}
echo('</p>');
?>