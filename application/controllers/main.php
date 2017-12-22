<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->library('Snoopy');
		$this->load->model('MainModel');
	}

	public function index() {
		$kai = (isset($_GET['kai'])) ? $_GET['kai'] : 1;

		$html = $this->getHtml($kai);
		$no = $this->getNo($html);
		$lottoNo = $this->getLotto($html);

		/*$toCsv = file_get_contents(APPPATH.'lotto.csv');
		$toCsv .= "\n".$kai.",";
		$toCsv .= implode(",", $lottoNo);
		file_put_contents(APPPATH.'lotto.csv', $toCsv);*/

		$this->MainModel->insertLotto($no,$lottoNo);
		$this->load->view('main', array('data'=>$no, 'lotto'=>$lottoNo));
	}

	private function getHtml($kai){
		$this->load->library('Snoopy');
		$getUrl = "http://www.645lotto.net/lotto645Confirm.do?method=byWin&drwNo=".$kai;
		return $this->snoopy->fetch($getUrl);
	}

	private function getLotto($html){
		$pattern='/img src="\/img\/common\/ball_[0-9]*.png/';
		preg_match_all($pattern,$this->snoopy->results,$out);
		$arrNo = array();
		for($i=0;$i<=6;$i++){
			$num[$i] = str_replace(".png","",str_replace('img src="/img/common/ball_',"",$out[0][$i]));
			if($i==6) {
				$arrNo[$i] = $num[$i];
			} else {
				$arrNo[$i] = $num[$i];
			}
		}
		return $arrNo;
	}

	private function getNo($kai){
		$pattern='/<option value="[0-9]*"\s*>(.*?)<\/option>/';
		preg_match_all($pattern,$this->snoopy->results,$out);
		$arrNo = array();
		$select = '';
		for( $i=0 ; $i < sizeof($out[0]) ; $i++ ){
			$arrNo[$i] = $out[0][$i];
		}
		return $arrNo;
	}

	/*
	 * 로또 분석 프로그램
	 * 2015.09.24 - 최초 프로그램 작성 >> 기존의 당첨된 로또번호를 웹에서 가져와서 화면출력
	 */
	/*include 'class.snoopy.php';
	header("Content-Type: text/html; charset=UTF-8");

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

	echo(($_GET['kai']) ? '<br />'.$_GET['kai'].'회차<br />' : '');

	if($_GET['kai']){
		echo('<p>');
		$pattern='/img src="\/img\/common\/ball_[0-9]*.png/';
		preg_match_all($pattern,$snoopy->results,$out);
		for($i=0;$i<=6;$i++){
			$num[$i]=str_replace(".png","",str_replace('img src="/img/common/ball_',"",$out[0][$i]));
			echo(($i==6) ? '보너스 : ' : '');
			echo($num[$i].'<br />');
		}
		echo('</p>');
	}*/
}
