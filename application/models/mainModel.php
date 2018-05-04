<?

/*CREATE TABLE `no` (
  `no` int(11) NOT NULL,
  `n1` int(11) DEFAULT NULL,
  `n2` int(11) DEFAULT NULL,
  `n3` int(11) DEFAULT NULL,
  `n4` int(11) DEFAULT NULL,
  `n5` int(11) DEFAULT NULL,
  `n6` int(11) DEFAULT NULL,
  PRIMARY KEY (`no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8*/

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
            'no'=>$no,
            'n1'=>$param[0],
            'n2'=>$param[1],
            'n3'=>$param[2],
            'n4'=>$param[3],
            'n5'=>$param[4],
            'n6'=>$param[5],
        );
        return $this->db->insert('no', $data);
    }
}
