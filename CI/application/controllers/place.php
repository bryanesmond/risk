<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Place extends CI_controller {

	function __construct(){
		parent::__construct();
		$this->load->model('Addunits');
	}
	
	function new_units($player_id, $game_id){
		$totalUnits = $this->Addunits->totalNumUnits($player_id, $game_id);
		$additionalUnits = floor(($totalUnits/3));
		if($additionalUnits<3){
			$additionalUnits=3;
		}
		
		
	}
	
	function add_unit($territory_units, $territory_id){
		return $territory_units + 1;	//function to add a unit to a territory
										//simple but allows the view to easily call as many times as needed
	}
	
	function done($territories_array){	//the done function is passed a multidimensional array	
		$num = count($territories_array);
		
		for($x=0;$x<$num;$x++)
		{
			$this->Addunits->updateNumUnits($territories_array[$x][0], $territories_array[$x][1]);
		}
	}
}