<?
/*
 * 로또 분석 프로그램
 * 2015.09.24 - 최초 프로그램 작성 >> 기존의 당첨된 로또번호를 웹에서 가져와서 화면출력
 */

$kai = (isset($_POST['kai'])) ? $_POST['kai'] : 1;
$save = (isset($_POST['save'])) ? $_POST['save'] : 0;
include './application/libraries/snoopy.php';
header("Content-Type: text/html; charset=UTF-8");

$snoopy = new snoopy;
$getUrl = "http://www.645lotto.net/gameResult.do?method=byWin&drwNo=".$kai;
$snoopy->fetch($getUrl);

$lottoNo = array();

if($kai) {
	$pattern='/img src="\/img\/common_new\/ball_[0-9]*.png/';
	preg_match_all($pattern,$snoopy->results,$out);
	for($i=0;$i<=6;$i++){
		$num[$i]=str_replace(".png","",str_replace('img src="/img/common_new/ball_',"",$out[0][$i]));
		if($i==6) {
			$lottoNo[$i] = $num[$i];
		} else {
			$lottoNo[$i] = $num[$i];
		}
	}
}

if($kai && $save!='false') {
	$toCsv = file_get_contents('lotto.csv');
	$toCsv .= "\n".$kai.",";
	$toCsv .= implode(",", $lottoNo);
	file_put_contents('lotto.csv', $toCsv);
}


class Lotto {
}

/*
$con = mysql_connect('localhost','exchan1','e38317');
mysql_select_db('exchan1', $con);
$re = mysql_query("select * from lotto",$con);

var_dump($re);

$sqlResult = mysql_fetch_array($re);
*/

// 공통연결부분
$host = "localhost";
$user = "exchan1";
$pw = "e38317";
$db = "exchan1";
$sqlResult = array();
$my_db = new mysqli($host,$user,$pw,$db);
mysqli_query($my_db,"set names utf8");
if ( mysqli_connect_errno() ) {
	echo mysqli_connect_error();
exit;
}

// 씸플 SELECT
$rs = mysqli_query($my_db, "select * from lotto");
while($data = mysqli_fetch_array($rs)){
	$sqlResult['lno'] = $data['lno'];
	$sqlResult['n1'] = $data['n1'];
	$sqlResult['n2'] = $data['n2'];
	$sqlResult['n3'] = $data['n3'];
	$sqlResult['n4'] = $data['n4'];
	$sqlResult['n5'] = $data['n5'];
	$sqlResult['n6'] = $data['n6'];
	$sqlResult['nb'] = $data['nb'];
}

$rs = mysqli_query($my_db, "select count(*) as cnt from lotto where lno={$kai}");
$data = mysqli_fetch_array($rs);
if($data['cnt']<=0){
	mysqli_query($my_db,"INSERT INTO lotto VALUES (
        '{$kai}',
        '{$lottoNo[0]}',
        '{$lottoNo[1]}',
        '{$lottoNo[2]}',
        '{$lottoNo[3]}',
        '{$lottoNo[4]}',
        '{$lottoNo[5]}',
        '{$lottoNo[6]}'
	)");
}

/* 접속 닫기 */
$my_db->close();

$arr = array(
	'result'=>true,
	'lno'=>$lottoNo,
	'save'=>$save,
	'sql'=>$sqlResult,
);
echo json_encode($arr);
?>