<?
/*
 * 로또 분석 프로그램
 * 2015.09.24 - 최초 프로그램 작성 >> 기존의 당첨된 로또번호를 웹에서 가져와서 화면출력
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

$where = "";
if(isset($_POST['kai'])){
	$where = "where lno=".$_POST['kai'];
}
// 씸플 SELECT
$rs = mysqli_query($my_db, "select * from lotto ".$where." order by lno desc");
$i = 0;
while($data = mysqli_fetch_array($rs)){
	$sqlResult[$i]['lno'] = $data['lno'];
	$sqlResult[$i]['n1'] = $data['n1'];
	$sqlResult[$i]['n2'] = $data['n2'];
	$sqlResult[$i]['n3'] = $data['n3'];
	$sqlResult[$i]['n4'] = $data['n4'];
	$sqlResult[$i]['n5'] = $data['n5'];
	$sqlResult[$i]['n6'] = $data['n6'];
	$sqlResult[$i]['nb'] = $data['nb'];
	$i++;
}

/* 접속 닫기 */
$my_db->close();

$arr = array(
	'sql'=>$sqlResult,
);
echo json_encode($sqlResult);
?>