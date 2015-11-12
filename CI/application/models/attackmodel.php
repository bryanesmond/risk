<?php class Attackmodel extends CI_Model{

	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	public function returnTotal($terr_id){
		$this->db->select('num_units');
		$this->db->where('territory_id', $terr_id);
		
		return $this->db->get('territory_status');
	}
	
	public function updateTerritory($new_total, $terr_id)
	{
		$data = array(
						'num_units' => $new_total
					);
	
		$this->db->where('territory_id', $terr_id);
		$this->db->update('territory_status', $data);
		
	}
	
	public function defendingTerritoryCaptured($attacker_id, $def_terr_id, $num_units_moved)
	{
		$data = array(
							'owner' => $attacker_id,
							'num_units' => $num_units_moved
						);
		
		$this->db->where('territory_id', $def_terr_id);
		$this->db->update('territory_status', $data);
	}

}