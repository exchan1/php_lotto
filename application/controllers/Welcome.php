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

     private $_point = 0.31;

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
            $this->slackSend($msg);
        } else {
            $this->slackSend($msg.'>>> 모든 회차가 등록 되어 있음.');
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

    private function insertRecommend($numbers, $nextLno)
    {
        foreach ($numbers as $k=>$v) {
            $tmp = explode(',', $v);
            $param = array(
                'lno' => $nextLno
                ,'n1' => $tmp[0]
                ,'n2' => $tmp[1]
                ,'n3' => $tmp[2]
                ,'n4' => $tmp[3]
                ,'n5' => $tmp[4]
                ,'n6' => $tmp[5]
            );
            $this->MainModel->insertRecommend($param);
        }
    }

    private function lottoresult()
    {
        $lno = $this->MainModel->getLotNo();
        $rec = $this->MainModel->getRecommend($lno);
        $res = $this->MainModel->getLotResult($lno);
        $res = $res[0];
        $numbers = array();
        foreach ($rec as $k=>$v) {
            $n1 = array_search($res['n1'], $v);
            $n2 = array_search($res['n2'], $v);
            $n3 = array_search($res['n3'], $v);
            $n4 = array_search($res['n4'], $v);
            $n5 = array_search($res['n5'], $v);
            $n6 = array_search($res['n6'], $v);
            if ($n1) $rec[$k][$n1] = '`'.$v[$n1].'`';
            if ($n2) $rec[$k][$n2] = '`'.$v[$n2].'`';
            if ($n3) $rec[$k][$n3] = '`'.$v[$n3].'`';
            if ($n4) $rec[$k][$n4] = '`'.$v[$n4].'`';
            if ($n5) $rec[$k][$n5] = '`'.$v[$n5].'`';
            if ($n6) $rec[$k][$n6] = '`'.$v[$n6].'`';

            array_push($numbers, implode(',', $rec[$k]));
        }
        $msg = "==========\n\n";
        $msg .= "*`lottobomb` {$lno}회차 당첨안내*\n";
        $msg .= "*".date("Y.m.d h:i")."*\n";
        $msg .= "*당첨번호 : ".implode(',', $res)."*\n";
        $msg .= implode("\n", $numbers);
        $this->slackSend($msg);
    }

    private function autolotto()
    {
        $nextLno = $this->MainModel->getLotNo()+1;
        $msg = $this->input->post('msg');
        $msg = (empty($msg)) ? "*자동 로또번호 추천 안내 {$nextLno}회차* \n\n" : $msg;
        $msg = $this->recommend($msg, $nextLno);
        $re = array();
        $re['msg'] = $msg;
        $re['result'] = $this->slackSend($msg);
        header('Content-Type: application/json');
        echo json_encode($re);
    }

    private function getArrayList($nextLno)
    {
        $re['list'] = $this->MainModel->getResultList($this->_point*100);
        $nums = array();
        $sums = array();
        foreach (range(1, 45) as $r) {
            $nums[$r]=0;
        }
        foreach ($re['list'] as $k => $v) {
            $nums[$v['n1']] = $nums[$v['n1']]+$point;
            $nums[$v['n2']] = $nums[$v['n2']]+$point;
            $nums[$v['n3']] = $nums[$v['n3']]+$point;
            $nums[$v['n4']] = $nums[$v['n4']]+$point;
            $nums[$v['n5']] = $nums[$v['n5']]+$point;
            $nums[$v['n6']] = $nums[$v['n6']]+$point;
            array_push($sums, ($v['n1']+$v['n2']+$v['n3']+$v['n4']+$v['n5']+$v['n6']));
            $point = $point - 0.01;
        }
        rsort($sums);
        array_splice($sums, 10);
        $re['sums'] = $sums;
        $re['nums'] = $nums;
        return $re;
    }

    private function recommend($msg, $nextLno)
    {
        $msg .= ":smile: *".date("Y.m.d h:i")."* :smile:\n";
        $point = $this->_point;
        $arr = $this->getArrayList($nextLno);
        $list = $arr['list'];
        $nums = $arr['nums'];
        $sums = $arr['sums'];
        $keys = array();
        $numbers = array();
        
        $nums_avg = array_sum($nums) / count($nums);
        foreach ($nums as $k => $v) {
            if ($nums_avg <= $v) array_push($keys, $k);
        }
        $i = 0;
        for (;;) {
            $rand = array_rand($keys, 6);
            $tmp = array();
            foreach ($rand as $v) {
                array_push($tmp, $keys[$v]);
            }
            if (in_array(array_sum($tmp), $sums)) {
                $s = array_sum($tmp);
                $msg .= implode(',', $tmp)." : ({$s})\n";
                array_push($numbers, implode(',', $tmp));
                $i++;
                if ($i==3) break;
            }
        }
        /*
        $this->debug($sums);
        $this->debug($keys);
        $this->debug($nums);
        $this->debug($numbers);
        */
        $this->insertRecommend($numbers, $nextLno);
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
        $nextLno = $this->MainModel->getLotNo()+1;
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
        $msg .= "*`lottobomb` {$nextLno}회차 추천*\n";
        $msg .= "*".date("Y.m.d h:i")."*\n";
        $msg .= implode("\n", $numbers);
        $this->slackSend($msg);
        $this->insertRecommend($numbers, $nextLno);
    }

    public function lottodel()
    {
        $kai        = $this->input->get_post('kai');
        if (!empty($kai)) {
            $this->MainModel->deleteRecommend($kai);
        }
    }

    private function recommendList()
    {
        $kai        = $this->input->get_post('kai');
        $re = $this->MainModel->getRecommendList($kai);
        header('Content-Type: application/json');
        echo json_encode($re);
    }
















    public function slacktest()
    {
        $msg = $this->input->post('msg');
        $msg = (empty($msg)) ? 'empty message!!' : $msg;
        $re['result'] = $this->slackSend($msg);
        header('Content-Type: application/json');
        echo json_encode($re);
    }

    private function slackSend($message, $room = "lottobot", $icon = ":longbox:")
    {
        $room = ($room) ? $room : "lottobot";
        $data = "payload=" . json_encode(array(
            "channel"       =>  "#{$room}",
            "text"          =>  $message,
            "icon_emoji"    =>  $icon
        ));
        $ch = curl_init(config_item('slackHook'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}


// http://exchan1.woobi.co.kr/?mode=autolotto