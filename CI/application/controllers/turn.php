<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
#controller
#use game board view for the view␊
	class Turn extends CI_Controller {
		
		public function __construct(){
				parent::__construct();
				$this->load->model('Turnmodel');
				$this->load->model('Fortifymodel');
				$this->load->model('Attackmodel')
				$this->load->model('Addunits');
				$this->load->library('session');
		}
		
		public function index()
		{
			$this->load->view('lobby');
			$this->load->view('waiting');
			$this->load->view('riskGameBoard');
			//$this->load->view('');
		}
		//array to hold players for game
		$playerTurnList = array();
		//variable to hold game id globally for indivdual game
		//$gameId = $this->session->userdata('game_id');
		$gameId = $this->input->get_post('game_id', TRUE);
		#function to create an array of the players in the game
		public function createPlayerArray($gameId)
		{
			
			$data['query'] = $this->Turnmodel->getPlayerById($gameId);
			$numPlayers['numPlayers'] = $this->Turnmodel->getNumPlayers($gameId);
			
			if ($numPlayers > 2 || $numPlayers < 7){
				for ($i = 1, $i <= $numPlayers, $i++){
				$playerTurnList[$i] = array(getPlayerById($gameId));
				}
			}
			else {
				#not sure exactly how to send an error message
				echo "Unallowed Number of Players";
			}
		}
		
		//variable to hold number of players in specific game
		$numPlayers = count($playerTurnList);
		
		public function assignTerritories($numPlayers){
			$territories = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42);
			
			shuffle($territories);
			
			if($numPlayers == 3){
				$num_units = 35;
			}
			else if($numPlayers == 4){
				$num_units =30;
			}
			else if($numPlayers == 5){
				$num_units =25;
			}
			else if($numPlayers == 6){
				$num_units =20;
			}
			else{
				return -1;
			}
			
			for($x = 0; $x < $numPlayers; $x++){
				$num = $x
				
				do{
					$num+=$numPlayers;
					
					//$this->Turnmodel->assignTerritory($territories[$num], $x);
					
				}while($num<42);
			}
			return num_units;
		}
		
		public function playTurn(){
			//While loop to iterate through the players during their turns and update stats as needed
			while (count($playerTurnList) > 0) {
				$numPlayers = $this->Turnmodel->getNumPlayers($gameId);
				//$numUnits = assignTerritories($numPlayers);
			
				if(count($playerTurnList) == 1) {
					$data = "Congrats " . $playerTurnList . " Won";
					$this->load->view('riskGameBoard', $data);
				}
				else {
					for ( $i = 0; $i < $numPlayers, $i++) {
						//get current player
						$currentPlayer = $playerTurnList[$i];						
						//get current player stats
						$currentPlayerUnits['query'] = $this->Turnmodel->getNumUnits($currentPlayer, $gameId);
						$currentPlayerTerritories['query'] = $this->Turnmodel->getTerritories($currentPlayer, $gameId);
						$currentPlayerContinent['query'] = $this->Turnmodel->getContinent($currentPlayer, $gameId);
						
						//get input from gameboard view
						$addUnits = $_POST['add_unit'];
						$territory = $_POST['second'];
						
						//add Units
						$data['query'] = $this->Addunits->updateNumUnits($this->Turnmodel->getTerritories($currentPlayer, $gameId), $addUnits);
						$this->load->view('riskGameBoard', $data);
						
						//attack oppponents
						function attackInitiated($territory, $def_terr_id, $currentPlayer, $def_player_id, $num_attk_units, $num_def_units){
						//does the number of attacking and defending units need to be passed from the view or returned from the DB
						//???????vvvvvvvvv???????
						//get territory being attacked owner
						$victim['query'] = $this->Turnmodel->getTerritoryOwner($gameId, $territory); 
						//$this->load->model('attackModel');
						$data['attk_total'] = $this->Attackmodel->returnTotal($attk_terr_id);//returns number of units on both territories
						$data['def_total'] = $this->Attackmodel->returnTotal($def_terr_id);
		
							if($num_attk_units<2){
								return -1;
							}
		
							if($num_attk_units >= $num_def_units){
    							$num_comparisons = $num_def_units; //if statement to find whether there are more attackers or defenders
    						}
    						else{							 		//necessary to find number of comparisons so you dont compare too many times
    							$num_comparisons = $num_attk_units;
    						}
		
							$wins = attackRoll($num_attk_units, $num_def_units, $num_comparisons);//simulates rolls then returns number of attacker wins
		
							$num = 0;
							$num = $num_comparisons - $wins; //num is equal to the number of losses
							//this will eventually be subtracted from the attacking territory's units
		
							if($wins == $data['def_total']){
								//if the number of wins equals the total defending units then the territory is captured
								$num++;	//adding one to num to account for the unit left behind on the attacking territory
								$this->Attackmodel->defendingTerritoryCaptured($attk_player_id, $def_terr_id, ($num_attk_units - $num));
							}
							else{
								$this->Attackmodel->updateTerritory(($num_attk_units - $num),$attk_terr_id);
								$this->Attackmodel->updateTerritory(($num_def_units - $wins),$def_terr_id);
							}
						}
	
						function attackRoll($num_attk, $num_def, $num_comparisons){//number of attacking units, defending units, and territory ids
	
							$num_attk-1;
	
							if($num_attk>3) {
								$num_attk=3;
							}
							if($num_def>2) {
								$num_def=2;
							}
	
  						  	$attacker_arr = array(0 => mt_rand(1,6));
    						$defender_arr = array(0 => mt_rand(1,6));
    	
    						for($x=1;$x<$num_attk;$x++) {
    							array_push($attacker_arr, mt_rand(1,6));
  						  	}
  						  	for($x=1;$x<$num_def;$x++)				//for loops fill the arrays with correct number of rolls 
   						 	{
    							array_push($defender_arr, mt_rand(1,6));
    						}
    	
   						 	$attacker_arr = bubbleSort($attacker_arr);//sort both arrays from biggest to smallest roll
    						$defender_arr = bubbleSort($defender_arr);
    	
    	
    						$results=0;
    	
    						for($x=0;$x<$num_comparisons;$x++) {
								if($attacker_arr[$x] > $defender_arr[$x]) {
									$results++;		//compares and stores results of the rolls
								}				//$results is the number of wins by the attacker
							}	
		
						return $results;
						}
	
						function bubbleSort(array $arr) { //bubble sort found at: http://blog.richardknop.com/2012/06/bubble-sort-algorithm-php-implementation/
    						$sorted = false;
    						while (false === $sorted) {
    					   		$sorted = true;
    					   	 	for ($i = 0; $i < count($arr)-1; ++$i) {
      						      	$current = $arr[$i];
     						       	$next = $arr[$i+1];
       						     	if ($next > $current) {
  						              	$arr[$i] = $next;
   						             	$arr[$i+1] = $current;
   						             	$sorted = false;
      						      	}
        						}
    						}
    						return $arr;
						}
						
						//update state after turn results
						$this->Turnmodel->updateTerritories($currentPlayer, $gameId, $territory);
						$this->Turnmodel->updateContinent($currentPlayer, $gameId, $continent);
					}
				}		
			}
		}	
	}
