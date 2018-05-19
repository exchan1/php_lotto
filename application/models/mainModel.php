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
}