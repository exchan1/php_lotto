<?

class EngModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function insertBigvoca($param)
    {
        $this->db->insert('bigvoca', $param);
        return $this->db->insert_id();
    }

    public function getBigvoca($param)
    {
        return $this->db
            ->order_by('idx', 'desc')
            ->limit(20)
            ->get('bigvoca')->result_array();
    }

    public function getBigvocaQuiz()
    {
        return $this->db
            ->order_by('rand()')
            ->limit(10)
            ->get('bigvoca')->result_array();
    }

    public function deleteBigvoca($param)
    {
        return $this->db
            ->where('idx', $param['idx'])
            ->delete('bigvoca');
    }

    public function updateBigvoca($param, $idx)
    {
        return $this->db
            ->where('idx', $idx)
            ->update('bigvoca', $param);
    }

}