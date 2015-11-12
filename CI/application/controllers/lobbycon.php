<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lobbycon extends CI_Controller{
      //http://babbage.cs.missouri.edu/~emovh8/swe-fs14gd/CI/index.php/lobbycon/index 
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
		$this->load->library('encrypt');
		$this->load->helper('security');
		$this->load->helper('url');
		$this->load->model('Lobbydata');


	}

	 public function index() { 	
	 	$loggedin = $this->session->userdata('loggedin');
	 	
	 	if ($loggedin == TRUE) {
	 		//echo "logged in, lobby page";
			//Get all games 
			//$this->db->select('game_id, creator_id, status');		
			$query['games'] = $this->Lobbydata->getAllGames();

			//Load View, send game info
			$this->load->view('lobby', $query);
		}
		else {
			redirect('/welcome/index', 'refresh');
		}
			
    }
        

	//VIEW Directs to Waiting Page?
	public function createGame(){
		$loggedin = $this->session->userdata('loggedin');
	 	
	 	if ($loggedin == TRUE) {
	 		
			$username = $this->session->userdata('username');
			
			//CREATE GAME in DB	
			//$create_query = array('creator_id' => $username, 'status' => "waiting");
			//$this->db->insert('games', $create_query);

			$this->Lobbydata->createGame($username);
		
			//GET GAME IDS FOR USER
			//$this->db->select('game_id, creator_id, status');
			//$this->db->where('creator_id', $username);
			$query['games'] = $this->Lobbydata->searchGames($username);
		
			//Iterate through all results and set game_id 
			foreach ($query['games']->result() as $row)
			{
					$game_id = $row->game_id;		
			}
			/* AFTER IT SETS GAME_ID TO MOST RECENTLY CREATED GAME BY USER, 
				Insert Game Info and Color 1 into Players Table 
				(color 1 because player created game)
			*/
			//$updateField = array('game_id' => $game_id, 'color' => '1', 'username' => $username);
			//$this->db->insert('players', $updateField);	

			$this->Lobbydata->updatePlayerGame($username, '1', $game_id);
			
        	$this->session->set_flashdata(array(
        					'action' => 'create',
                            'game_id'      => $game_id,
                            'creator_id'        => $username,
                            'color' => '1',
                            'status' => 'waiting'
                    ));
			
			redirect('/lobbycon/waitLob', 'refresh');
		}
		else {
			///redirect('/welcome/index', 'refresh');
		}	
	}
	
	public function refreshPage(){
			$this->db->select('username, color');
			$this->db->where('game_id', $game_id);
			$query['players'] = $this->db->get('players');
			
			//Send game data and player data to waiting view
			$this->load->view('waiting', $query);	
		
	
	}

	public function joinGame(){
		$loggedin = $this->session->userdata('loggedin');
	 	$username = $this->session->userdata('username');
	 	
	 	if ($loggedin == TRUE) {
			//GET GAME ID FROM HIDDEN FIELD IN JOIN GAME FORM
			$game_id = $_POST['game_id'];
			
			
			//GET COLORS ALREADY IN USE
			//$this->db->select('color');
			//$this->db->where('game_id', $game_id);
			$query = $this->Lobbydata->getPlayers($game_id);
					
			$color = $query->num_rows() + 1;
			
			//If too many players already joined game
			if ($color > 6) {
				echo "No More Players Allowed to Join This Game";
				$this->load->helper('form');
				echo "<br>";
				$back_game_attributes = array('class' => 'backtoindex', 'id' => 'backtoindex');
					echo form_open('lobbycon/index', $back_game_attributes);
				$back_data = array('name' => 'backtoindex', 'id' => 'backtoindex', 'value' => 'Go Back');
				echo form_submit($back_data);
				echo form_close();
			}
			//Join game for player
			else {
				//echo "Joining Game ".$game_id."<br>";
				//echo "Color: ".$color."<br>";
				
				$this->session->set_flashdata(array(
							'action' => 'join',
                            'game_id'      => $game_id,
                            'username'        => $username,
                            'color' => $color,
                            'status' => 'waiting'
                    ));
			
				//$updateField = array('game_id' => $game_id, 'color' => $color, 'username' => $username);
				//$this->db->insert('players', $updateField);

				$this->Lobbydata->updatePlayerGame($username, $color, $game_id);

				redirect('/lobbycon/waitLob', 'refresh');
			}
		}
		else {
			//redirect('/welcome/index', 'refresh');
		}
	}


	public function startGame() {
		$game_id = $_POST['game_id'];
		
			
		//Update status of Game based on game ID
		//$status_update = array(
             //  'status' => 'active'
           // );
		//$this->db->where('game_id', $game_id);
		//$this->db->update('games', $status_update);

		$this->Lobbydata->updateStatus($game_id); 
		
		echo "Database updated with status active.<br>";
		
		//Select players of game by game ID
		//$this->db->select('game_id, username, color');
		//$this->db->where('game_id', $game_id);
		$query['players'] = $this->Lobbydata->getPlayers($game_id);
		
		
		//Create array for players in game
		$data['playerTurnList'] = array();
		$i = 0;
		

		foreach ($query['players']->result() as $row) {
			
			$data['playerTurnList'][$i] = array('username' => $row->username, 'color' => $row->color);
			$data['game_id'] = $row->game_id;
			$i++;
		}

		//print_r($data['playerTurnList']);
		$numPlayers = count($data['playerTurnList']);
		//echo $numPlayers;
		
		//set territories in database for each player
		$territories = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42);
			
			shuffle($territories);
			
			if($numPlayers == 3){
				$num_units = 35;
			}
			else if($numPlayers == 4){
				$num_units = 30;
			}
			else if($numPlayers == 5){
				$num_units = 25;
			}
			else if($numPlayers == 6){
				$num_units = 20;
			}
			else {
				echo "less than 3 players in game";
			}
			
			
			for($x = 1; $x <= $numPlayers; $x++){
				$num = $x - 1;
				
				do{
					//$territory_update = array(
               		//	'game_id' => $game_id,
               		//	'territory' => $territories[$num],
               		//	'owner' => $x,
               		//	'num_units' => '1'
            		//);
            		
    				//$this->db->insert('territory_status', $territory_update);
					
					$this->Lobbydata->insertTerritories($game_id, $territories[$num], $x);


					$num+=$numPlayers;
					
				}while($num<42);
			}
			
			
			//$this->db->select('game_id, territory, owner, num_units');		
			$data['territory_status'] = $this->Lobbydata->getTerritories();
		
			/*foreach ($data['territory_status']->result() as $row) {
				echo $row->game_id;
				echo " ".$row->territory;
				echo " ".$row->owner;
				echo " ".$row->num_units;
			}*/
			
			
		//Redirect to main game page and send player and game info
		$this->load->view('riskGameBoard', $data);
	}

	public function waitLob() {
		//Needs to update using ajax to print new players in game
		//Cannot start game without 3 or more players
		
		$username = $this->session->userdata('username');
		$action = $this->session->flashdata('action');
		$game_id = $this->session->flashdata('game_id');
		$color = $this->session->flashdata('color');
		
		//GET GAME INFO
		//$this->db->select('game_id, creator_id, status');
		//$this->db->where('game_id', $game_id);
		$query['games'] = $this->Lobbydata->getOneGame($game_id);

		//Set color and player username
		$query['color'] = $color;
		$query['username'] = $username;
		$query['action'] = $action;

		//get players of game and send to view
		//Select players of game by game ID
		//$this->db->select('username, color');
		//$this->db->where('game_id', $game_id);
		$query['players'] = $this->Lobbydata->getPlayers($game_id);
		
		//Send game data and player data to waiting view
		$this->load->view('waiting', $query);
		
		/*<script language="javascript" type="text/javascript">
			function loadlink(){
				$('#links').load('test.php',function () {
					 $(this).unwrap();
				});
			}

			loadlink(); // This will run on page load
			setInterval(function(){
   				loadlink() // this will run after every 5 seconds
			}, 5000);
        </script>*/
		
	}		


}
?>

