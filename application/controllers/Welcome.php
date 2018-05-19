<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller 
{

    /**
     * 로또 파싱 & 분석 프로그램 Beta Version
     * 
     * @author : dkkim <exchan1@gmail.com>
     * @todo : Main Controller 접근 기능 수정 필요함.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('Snoopy'));
        $this->load->model(array('MainModel'));
    }

    public function _remap($method, $args = array())
    {
        if (strrpos($method, "_ajax") !== false) { $this->{"{$method}"}(); return; }
        if (strrpos($method, "_exec") !== false) { $this->{"{$method}"}(); return; }
        if (strrpos($method, "_popup") !== false) { $this->{"{$method}"}(); return; }
        $this->{"{$method}"}();
    }


    public function index()
    {
        $mode = $this->input->get('mode');
        if (!empty($mode)) {
            $this->{"{$mode}"}();
        } else {
            $this->main();
        }
    }

    public function main()
    {
        $kai        = $this->input->get_post('kai');
        $kai        = (empty($kai)) ? 1 : $kai;
        $html       = $this->getHtml($kai);
        $no         = $this->getNo($html);
        $lottoNo    = $this->getLotto($html);
        $data       = array(
            'data'=>$no
            ,'lotto'=>$lottoNo
            ,'class' => __CLASS__
        );

        $this->MainModel->insertLotto($kai, $lottoNo);
        $this->load->view('main', $data);
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

    private function autolottoNo()
    {
        $html       = $this->getHtml(1);
        $no         = $this->getNo($html);
        $nums       = $this->MainModel->getNos();
        $in_no      = array();
        $msg        = "*자동 로또 번호 등록 안내* \n\n";
        $msg        .= "*".date("Y.m.d h:i")."*\n";
        foreach ($no as $k=>$v) {
            if (!in_array($v, $nums)) {
                $this->insertNo($v);
                array_push($in_no, $v);
            }
        }

        if (!empty($in_no)) {
            $msg .= ">>> 아래 회차 등록\n\n";
            $msg .= implode(',', $in_no);
            $this->slack($msg);
        } else {
            $this->slack($msg.'>>> 모든 회차가 등록 되어 있음.');
        }
    }

    private function insertNo($kai)
    {
        $html       = $this->getHtml($kai);
        $lottoNo    = $this->getLotto($html);
        $this->MainModel->insertLotto($kai, $lottoNo);
    }

    private function debug($data)
    {
        print "<div style='background:#000000;color:#00ff00;padding:10px;text-align:left'><xmp style=\"font:8pt 'Courier New'\">";
        print_r($data);
        print "</xmp></div>";
    }

    private function autolotto()
    {
        $msg = $this->input->post('msg');
        $msg = (empty($msg)) ? "*자동 로또번호 추천 안내* \n\n" : $msg;
        $msg = $this->recommend($msg);
        $re['result'] = $this->slack($msg);
        header('Content-Type: application/json');
        echo json_encode($re);
    }

    private function recommend($msg)
    {
        $msg .= ":smile: :smile:";
        $msg .= "*".date("Y.m.d h:i")."*\n";
        return $msg;
    }

    private function bomb()
    {
        $getUrl = "http://www.lottobomb.com/main/home";
        $html = $this->snoopy->fetch($getUrl);
        $pattern = '/data-to="(.*?)"\s*/';
        $numbers = array();
        preg_match_all($pattern, $this->snoopy->results, $out, PREG_SET_ORDER);
        $i = 0;
        $str = '';
        foreach ($out as $k => $v) {
            $str .= (($i>0) ? ',':'').$v[1];
            if (5==$i) {
                array_push($numbers, $str);
                $i = 0;
                $str = '';
            } else {
                $i++;
            }
        }
        $msg = "==========\n\n";
        $msg .= "*`lottobomb` 추천*\n";
        $msg .= "*".date("Y.m.d h:i")."*\n";
        $msg .= implode("\n", $numbers);
        $this->slack($msg);
    }









    public function slacktest()
    {
        $msg = $this->input->post('msg');
        $msg = (empty($msg)) ? 'empty message!!' : $msg;
        $re['result'] = $this->slack($msg);
        header('Content-Type: application/json');
        echo json_encode($re);
    }

    private function slack($message, $room = "lottobot", $icon = ":longbox:")
    {
        $room = ($room) ? $room : "lottobot";
        $data = "payload=" . json_encode(array(
            "channel"       =>  "#{$room}",
            "text"          =>  $message,
            "icon_emoji"    =>  $icon
        ));
        $ch = curl_init("https://hooks.slack.com/services/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");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}


// http://exchan1.woobi.co.kr/?mode=autolotto