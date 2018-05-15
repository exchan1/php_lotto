<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 로또 파싱 & 분석 프로그램 Beta Version
 * 
 * @author : dkkim <exchan1@gmail.com>
 */
class Main extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('Snoopy'));
        $this->load->model(array('MainModel'));
    }

    public function index()
    {
        $kai        = (isset($_GET['kai'])) ? $this->input->get_post['kai'] : 1;
        $html       = $this->getHtml($kai);
        $no         = $this->getNo($html);
        $lottoNo    = $this->getLotto($html);

        $this->MainModel->insertLotto($no, $lottoNo);
        $this->load->view('main', array('data'=>$no, 'lotto'=>$lottoNo));
    }

    private function getHtml($kai)
    {
        $getUrl = "http://www.645lotto.net/lotto645Confirm.do?method=byWin&drwNo=".$kai;
        return $this->snoopy->fetch($getUrl);
    }

    private function getLotto($html)
    {
        $pattern = '/img src="\/img\/common\/ball_[0-9]*.png/';
        preg_match_all($pattern, $this->snoopy->results, $out);
        $arrNo = array();
        for ($i=0;$i<=6;$i++) {
            $num[$i] = str_replace(".png", "", str_replace('img src="/img/common/ball_', "", $out[0][$i]));
            if ($i==6) {
                $arrNo[$i] = $num[$i];
            } else {
                $arrNo[$i] = $num[$i];
            }
        }
        return $arrNo;
    }

    private function getNo($kai)
    {
        $pattern = '/<option value="[0-9]*"\s*>(.*?)<\/option>/';
        preg_match_all($pattern, $this->snoopy->results, $out);
        $arrNo = array();
        $select = '';
        $size = sizeof($out[0]);
        for ($i=0 ; $i < $size ; $i++ ) {
            $arrNo[$i] = $out[0][$i];
        }
        return $arrNo;
    }
}
