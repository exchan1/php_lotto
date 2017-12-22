<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	 public function __construct(){
		parent::__construct();
		//$this->load->library('Snoopy');
		//$this->load->model('MainModel');
	}

	public function index()
	{
		//$this->load->view('welcome_message');
		//	$this->load->view('main');
		$kai = (isset($_GET['kai'])) ? $_GET['kai'] : 1;

		$html = $this->getHtml($kai);
		//$no = $this->getNo($html);
		//$lottoNo = $this->getLotto($html);

		/*$toCsv = file_get_contents(APPPATH.'lotto.csv');
		$toCsv .= "\n".$kai.",";
		$toCsv .= implode(",", $lottoNo);
		file_put_contents(APPPATH.'lotto.csv', $toCsv);*/

		//$this->MainModel->insertLotto($no,$lottoNo);
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
}
