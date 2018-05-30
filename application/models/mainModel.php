<?

class MainModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function insertLotto($no,$param)
    {
        $data = array(
            'lno'=>$no,
            'n1'=>$param[0],
            'n2'=>$param[1],
            'n3'=>$param[2],
            'n4'=>$param[3],
            'n5'=>$param[4],
            'n6'=>$param[5],
        );
        $cnt = $this->getNoCnt($no);
        if ($cnt <= 0) {
            return $this->db->insert('lotto', $data);
        } else {
            return 0;
        }
    }

    private function getNoCnt($no)
    {
        return $this->db
        ->where('lno', $no)
        ->count_all_results('lotto');
    }

    public function getNos()
    {
        $re = $this->db->select('lno')->get('lotto')->result_array();
        $arr = array();
        foreach ($re as $k=>$v) {
            $arr[$k] = $v['lno'];
        }
        return $arr;
    }

    public function insertRecommend($param)
    {
        return $this->db->insert('lotto_recommend', $param);
    }

    public function getLotNo()
    {
        $re = $this->db->select_max('lno')->get('lotto')->result();
        return intval($re[0]->lno);
    }

    public function getRecommend($lno)
    {
        return $this->db
            ->select('n1,n2,n3,n4,n5,n6')
            ->where('lno', $lno)
            ->get('lotto_recommend')
            ->result_array();
    }

    public function getLotResult($lno)
    {
        return $this->db
            ->select('n1,n2,n3,n4,n5,n6')
            ->where('lno', $lno)
            ->get('lotto')
            ->result_array();
    }

    public function getResultList($limit)
    {
        return $this->db
            ->select('n1,n2,n3,n4,n5,n6')
            ->order_by('lno', 'desc')
            ->limit($limit)
            ->get('lotto')
            ->result_array();
    }

    public function deleteRecommend($kai)
    {
        return $this->db
            ->where('lno', $kai)
            ->delete('lotto_recommend');
    }

    public function getRecommendList($kai)
    {
        return $this->db
            ->where('lno', $kai)
            ->get('lotto_recommend')
            ->result_array();
    }
}