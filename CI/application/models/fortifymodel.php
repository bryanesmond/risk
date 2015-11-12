<?php class Forifymodel extends CI_Model{

	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	public function update_units($numUnits, $userName, $gameID)
	{
		$array = array('owner' => $userName, 'game_id' => $gameID)

		$this->db->where($array);

		$this->db->update('territory_status', array('num_units' => $numUnits));
	}
}
