<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Attack extends CI_controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Attackmodel');
	}
	
	function attackInitiated($attk_terr_id, $def_terr_id, $attk_player_id, $def_player_id, $num_attk_units, $num_def_units){
		//does the number of attacking and defending units need to be passed from the view or returned from the DB
		//???????vvvvvvvvv???????
		//$this->load->model('Attackmodel');
		$data['attk_total'] = $this->Attackmodel->returnTotal($attk_terr_id);//returns number of units on both territories
		$data['def_total'] = $this->Attackmodel->returnTotal($def_terr_id);
		
		if($num_attk_units<2)
		{
			return -1;
		}
		
		if($num_attk_units >= $num_def_units){
    		$num_comparisons = $num_def_units; //if statement to find whether there are more attackers or defenders
    	}else{							 		//necessary to find number of comparisons so you dont compare too many times
    		$num_comparisons = $num_attk_units;
    	}
		
		$wins = attackRoll($num_attk_units, $num_def_units, $num_comparisons);//simulates rolls then returns number of attacker wins
		
		$num = 0;
		$num = $num_comparisons - $wins; //num is equal to the number of losses
										 //this will eventually be subtracted from the attacking territory's units
		
		if($wins == $data['def_total'])//if the number of wins equals the total defending units then the territory is captured
		{
			$num++;	//adding one to num to account for the unit left behind on the attacking territory
			$this->Attackmodel->defendingTerritoryCaptured($attk_player_id, $def_terr_id, ($num_attk_units - $num);
		}else{
			$this->Attackmodel->updateTerritory(($num_attk_units - $num),$attk_terr_id);
			$this->Attackmodel->updateTerritory(($num_def_units - $wins),$def_terr_id);
		}
	}
	
	function attackRoll($num_attk, $num_def, $num_comparisons){//number of attacking units, defending units, and territory ids
	
		$num_attk-1;
	
		if($num_attk>3)
		{
			$num_attk=3;
		}
		if($num_def>2)
		{
			$num_def=2;
		}
	
    	$attacker_arr = array(0 => mt_rand(1,6));
    	$defender_arr = array(0 => mt_rand(1,6));
    	
    	for($x=1;$x<$num_attk;$x++)
    	{
    		array_push($attacker_arr, mt_rand(1,6));
    	}
    	for($x=1;$x<$num_def;$x++)				//for loops fill the arrays with correct number of rolls 
    	{
    		array_push($defender_arr, mt_rand(1,6));
    	}
    	
    	$attacker_arr = bubbleSort($attacker_arr);//sort both arrays from biggest to smallest roll
    	$defender_arr = bubbleSort($defender_arr);
    	
    	
    	$results=0;
    	
    	for($x=0;$x<$num_comparisons;$x++)
		{
			if($attacker_arr[$x] > $defender_arr[$x])
			{
				$results++;		//compares and stores results of the rolls
			}					//$results is the number of wins by the attacker
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
	
	

}