<?php class Addunits extends CI_Model{

	public function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	function updateNumUnits($territory_id, $num_units){
		$data = array('num_units' => $num_units);
		$this->db->where('territory_id', $territory_id);
		$this->db->update('territory_status', $data);
	}
	
	function totalNumUnits($userName, $gameId){
		$where = "'owner' = $userName AND 'game_id' = $gameId";
		$this->db->select_sum('num_units')->from('territory_status')->where($where);
		$query = $this->db->get();
		return $query
		
	}

}