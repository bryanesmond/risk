<? class Lobbydata extends CI_Model 
{

	function __construct() 
	{
		parent::__construct();
		$this->load->database();
	}

	public function searchGames($username)
	{
		$this->db->select('game_id, creator_id, status');
		$this->db->where('creator_id', $username);
		return $this->db->get('games');
	}

	public function getAllGames() 
	{
		$this->db->select('game_id, creator_id, status');
		return $this->db->get('games');
	}

	public function getOneGame($game_id)
	{
		$this->db->select('game_id, creator_id, status');
		$this->db->where('game_id', $game_id);
		return $this->db->get('games', 1);
	}

	public function updateStatus($game_id)
	{
		$status_update = array(
               'status' => 'active'
            );
		$this->db->where('game_id', $game_id);
		$this->db->update('games', $status_update);
	}

	public function createGame($playerId)
	{
		$create = array('creator_id' => $playerId, 'status' => "waiting");
		
		$this->db->insert('games', $create);
	}

	public function updatePlayerGame($username, $color, $game_id)
	{
		$updateField = array('game_id' => $game_id, 'color' => $color, 'username' => $username);
			$this->db->insert('players', $updateField);
	}

	public function getPlayers($gameId)
	{
		$this->db->select('game_id, username, color');
		$this->db->where('game_id', $gameId);
		//$this->db->where('username, !$playerId');  How to do "where ___ != ___"??? <----------------------------------------<

		return $this->db->get('players');
	}

	public function insertTerritories($game_id, $territory, $owner)
	{
		$territory_update = array(
               			'game_id' => $game_id,
               			'territory' => $territory,
               			'owner' => $owner,
               			'num_units' => '1'
            		);
            		
    	$this->db->insert('territory_status', $territory_update);
	}

	public function getTerritories()
	{
		$this->db->select('game_id, territory, owner, num_units');

		return $this->db->get('territory_status');
	}	
}	
?>