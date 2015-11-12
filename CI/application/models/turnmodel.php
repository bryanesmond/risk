<?php class Turnmodel extends CI_Model {

	
	function __construct()
    {
        parent::__construct();
        #connect to database via config file
        $this->load->database();
    }
	#function to get the specfic game being play by its id
	#will need to get creator of id as well to select the correct id
	#model function
	#function to confirm game id
	public function getGameId($gameId)
	{
		$query = $this->db->get_where('games', $gameId, 1);
		return $query->result();
		#another option of getting the same result
		#return $this->db->query('Select game_id FROM '.dbprefix.' games WHERE creator_id = :id');
	}
	#function to get players in a specific game by there id
	#model function
	public function getPlayerById($gameId)
	{
		$this->db->select('user');
		$query = $this->db->get_where('players', array('game_id' => $gameId);
// 		$username = $this->db->query('Select username FROM '.dbprefix.' players');
		return $query->result();
	}
	#function to get the total number of players for the game
	#will need to add error checking for the 3-6 player limit
	#potentially model function
	public function getNumPlayers($gameId)
	{
		$this->db->select('username')->from('players')->where('game_id' = $gameId);
		$query = $this->db->get();
		$i = $query->num_rows();
		#$result = this->db->query('Select * FROM '.dbprefix.' players');
		#$i = pg_num_rows($result);
		if ($i <= 6 || $> = 3){
			return $i;
		}
		else{
			return 0;
		}
	}
	
	//function to get the number of units a specific players has in a specific game
	public function getNumUnits($userName, $gameId)
	{		
		$where = "'owner' = $userName AND 'game_id' = $gameId";
		$this->db->select_sum('num_units')->from('territory_status')->where($where);
		$query = $this->db->get();
		return $query->result();
	}
	
	//function to get territories owned by a specfic player in a specific game
	public function getTerritories($userName, $gameID)
	{
		$where = "'owner' = $userName AND 'game_id' = $gameID";
		$this->db->select('territory')->from('territory_status')->where($where);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0){
			return $query->result();
		}
	}
		else {
			return 0;
		}
		
	}
	//function to get continent if owned
	public function getContinent($userName, $gameID)
	{
		$where = "'owner' = $userName AND 'game_id' = $gameID";
		$this->db->select('continent')->from('continent_status')->where($where);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}
		else {
			return 0;
		
		}
	}
	//function to update units on territory
	public function updateUnits($gameId, $userName, $territory)
	{
		$where = "'game_id' = $gameId AND 'owner' = $userName";
		$this->db->update('territory_status', $territory, array('where' => $where));
	}
	//function to update territory if there is a new owner after result of attack
	public function updateTerritories($gameId, $userName, $territory, $newOwner)
	{
    	$where = "game_id = '$gameId' AND owner = '$userName'";
    	$data = array(
               'territory' => $territory,
               'num_units' => $addUnits.
               'owener' => $newOwner
            );
		$this->db->where(array('where' => $where));
        $this->db->update('territory_status', $data);
	}
	//function to update a continent's owner if needed
	public function updateContinent($gameId, $userName, $continent)
	{
		//need to determine the method for retriving the name of the territory being attack, fortified, etc. in the game
    	$where = "game_id = '$gameId' AND owner = '$userName'";
        $this->db->update('continent_status', $continent, array('where' => $where));
	}
	//function to return the owner of a specific territory
	public function getTerritoryOwner($gameId, $territory)
	{
		$where = "game_id = 'game_id' AND territory = 'territory'";
		$this->db->select('owner')->from('territory_status')->where($where);
		$query = $this->db->get();
		return $query->result();
	}
	
}
