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
        $kai        = $this->input->get_post('kai');
        $kai        = (empty($kai)) ? 1 : $kai;
        $html       = $this->getHtml($kai);
        $no         = $this->getNo($html);
        $lottoNo    = $this->getLotto($html);

        $this->MainModel->insertLotto($kai, $lottoNo);
        $this->load->view('main', array('data'=>$no, 'lotto'=>$lottoNo));
    }

    public function getLottoNoAuto()
    {
        $this->output->set_content_type('text/plain', 'UTF-8');
        $html       = $this->getHtml(1);
        $no         = $this->getNo($html);
        //$this->debug($no);
        header('Content-Type: application/json');
        echo json_encode($no);
    }

    private function debug($data)
    {
        print "<div style='background:#000000;color:#00ff00;padding:10px;text-align:left'><xmp style=\"font:8pt 'Courier New'\">";
        print_r($data);
        print "</xmp></div>";
    }

    private function getHtml($kai)
    {
        $getUrl = "http://www.645lotto.net/gameResult.do?method=byWin&drwNo=".$kai;
        return $this->snoopy->fetch($getUrl);
    }

    private function getLotto($html)
    {
        $pattern = '/img src="\/img\/common_new\/ball_[0-9]*.png/';
        preg_match_all($pattern, $this->snoopy->results, $out);
        $arrNo = array();
        for ($i=0;$i<=6;$i++) {
            $num[$i] = str_replace(".png", "", str_replace('img src="/img/common_new/ball_', "", $out[0][$i]));
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
        $pattern = '/<option value="(.*?)"\s*>(.*?)<\/option>/';
        preg_match_all($pattern, $this->snoopy->results, $out, PREG_SET_ORDER);
        $arrNo = array();
        $select = '';
        $size = sizeof($out);
        for ($i=0 ; $i < $size ; $i++ ) {
            $arrNo[$i] = $out[$i][1];
        }
        return $arrNo;
    }
}
