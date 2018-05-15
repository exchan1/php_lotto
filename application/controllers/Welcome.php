<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller 
{

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
