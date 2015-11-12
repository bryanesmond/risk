<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fortify extends CI_controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Addunits');
	}
	
	function add_units($territory_units, $territory_id){
		
		return $territory_units+1;	//function to add a unit to a territory
	}
	
	function subtract_units($territory_units){
		if($territory_units>0){
			return $territory_units-1;	//function to subtract a unit to a territory
		}else							//returns 0 if there are no units to subtract
		{
			return 0;
		}
	}
	
	function done($territories_array){
		$num = count($territories_array);
		
		for($x=0;$x<$num;$x++)
		{
			$this->Addunits->updateNumUnits($territories_array[$x][0], $territories_array[$x][1]);
		}
	}
}