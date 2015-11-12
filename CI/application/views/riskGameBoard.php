<?php
	$i=0;
	$terr = array();
	//print_r($playerTurnList);
	foreach($territory_status->result() as $row){
		$terr[$i] = array('game_id' => $row->game_id,
						'territory' => $row->territory,
						'owner'		=> $row->owner,
						'num_units' => $row->num_units
						);
		$i++;
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
		<link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet">
		<link href="riskGame.css" rel="stylesheet" type="text/css" media="all">
		<script>
			var coord_array = new Array(42);
			var a_canvas;//global in scope because all of these functions need to use these
			var a_context;
			var imageObj = new Image();
			var colors = array("#006600","#0033CC","#A00000","#FF9933","#FFFF33","#9900CC");
			
			var terr_arr = <?php echo json_encode($myPhpArray) ?>;
		
			window.onload = function() {
				try {
					a_canvas = document.getElementById("board");
					a_context = a_canvas.getContext("2d");
					
					imageObj.src = 'http://www.badgehungry.com/blog/wp-content/uploads/2010/05/Risk01.jpg';
					
					alert(terr_arr.toString());
					
					a_context.drawImage(imageObj, 0, 0);
					
					for (var i = 0; i < 42; i++) {
    					coord_array[i] = new Array(3);
  					}
  			
					coord_array[0] = ['Afghanistan',550,220];	//names of the territories along with coordinates
					coord_array[1] = ['Alaska',47,110];			//the coordinates are for use of the canvas element
					coord_array[2] = ['Alberta',120,155];
					coord_array[3] = ['Argentina',220,455];
					coord_array[4] = ['Brazil',255,365];
					coord_array[5] = ['Central Africa',435,408];
					coord_array[6] = ['Central America',135,292];
					coord_array[7] = ['China',615,250];
					coord_array[8] = ['Eastern Africa',465,365];
					coord_array[9] = ['Eastern Australia',720,420];
					coord_array[10] = ['Eastern Canada',222,157];
					coord_array[11] = ['Eastern US',215,220];
					coord_array[12] = ['Egypt',437,328];
					coord_array[13] = ['Great Britain',328,185];
					coord_array[14] = ['Greenland',275,75];
					coord_array[15] = ['Iceland',345,140];
					coord_array[16] = ['India',585,290];
					coord_array[17] = ['Indonesia',655,400];
					coord_array[18] = ['Irkutsk',660,153];
					coord_array[19] = ['Japan',752,200];
					coord_array[20] = ['Kamchatka',740,113];
					coord_array[21] = ['Madagascar',505,480];
					coord_array[22] = ['Middle East',500,310];
					coord_array[23] = ['Mongolia',670,200];
					coord_array[24] = ['New Guinea',715,380];
					coord_array[25] = ['Northern Africa',360,330];
					coord_array[26] = ['Northern Europe',410,200];
					coord_array[27] = ['NW Territory',110,110];
					coord_array[28] = ['Ontario',163,163];
					coord_array[29] = ['Peru',190,390];
					coord_array[30] = ['Russia',485,155];
					coord_array[31] = ['Scandinavia',403,133];
					coord_array[32] = ['Siberia',610,130];
					coord_array[33] = ['Southern Africa',435,460];
					coord_array[34] = ['Southern Europe',425,277];
					coord_array[35] = ['SE Asia',650,303];
					coord_array[36] = ['Ural',565,155];
					coord_array[37] = ['Venezuela',180,340];
					coord_array[38] = ['Western Australia',680,440];
					coord_array[39] = ['Western Europe',355,260];
					coord_array[40] = ['Western US',125,203];
					coord_array[41] = ['Yakutsk',675,90];
					
					a_context.fillStyle = '#000000';
					a_context.font = '12px sans-serif';
					a_context.textBaseline = 'top';
					
					var selection1 = document.getElementById( "first" );//getting the select elements
					var selection2 = document.getElementById( "second" );
				
					var territory1 = selection1.options[selection1.selectedIndex].text;//getting the text of the selection
					var territory2 = selection2.options[selection2.selectedIndex].text;
					
					selectTerritory();//draw the numbers on all the territories

					document.querySelector('#units').innerHTML = '###';
					

				}catch(err) {}
			}
			
			function redraw(){
				for (var i = 0; i < 42; i++) {
    				a_context.fillText('00', coord_array[i][1], coord_array[i][2]);
  				}
			}
			
			function selectTerritory(){
				a_context.clearRect(0,0,815,600);//need to clear the canvas because the previous text does not get erased
				a_context.drawImage(imageObj, 0, 0);//redrawing the image
				//a_context.fillStyle = '#000000'; 
				//a_context.font="40px copperplate";
				//a_context.fillText("Player 1",315,560);
				a_context.textBaseline = 'top';
				
				var selection1 = document.getElementById( "first" );//getting the selection elements
				var selection2 = document.getElementById( "second" );
				
				var territory1 = selection1.options[selection1.selectedIndex].text;//getting the text of the selection elements
				var territory2 = selection2.options[selection2.selectedIndex].text;
				
				a_context.textBaseline = 'top';
				
				for (var i = 0; i < 42; i++) {
				//$territory = $terr[$i]['territory'];
				//$color = $terr[$i]['color']
				
				
    				if(territory1==coord_array[i][0]){
    					a_context.fillStyle = '#FF0000';				//if the territory is the current selection
						a_context.font = 'bold 12px sans-serif';		//then the numbers are written in red
    					a_context.fillText('00', coord_array[i][1], coord_array[i][2]);
    					a_context.fillStyle = '#9900FF';
    					a_context.fillRect(coord_array[i][1]+10,coord_array[i][2],5,5);
    					
    				}else if(territory2==coord_array[i][0]){
    					a_context.fillStyle = '#FF0000';				//if the territory is the other selection
						a_context.font = 'bold 12px sans-serif';		//then the numbers are also written in red
    					a_context.fillText('00', coord_array[i][1], coord_array[i][2]);
    					a_context.fillStyle = '#9900FF';
    					a_context.fillRect(coord_array[i][1]+10,coord_array[i][2],5,5);
    					
    				}else{
    					a_context.fillStyle = '#000000';				//if the territory is not a selection then then numbers
						a_context.font = '12px sans-serif';				//are written in black
						a_context.fillText('00', coord_array[i][1], coord_array[i][2]);
						a_context.fillStyle = '#9900FF';
						a_context.fillRect(coord_array[i][1]+10,coord_array[i][2],5,5);
						
    				}
  				}
			}
			
			function doneButtonClick(btn_id){				//this function disables buttons when the done button is hit
				if(btn_id=="donePlace")
				{
					document.getElementById("add_unit").disabled = true;
					document.getElementById("reset").disabled = true;
					document.getElementById("donePlace").disabled = true;
				}else if(btn_id=="doneAttack")
				{
					document.getElementById("second").disabled = true;
					document.getElementById("init_attack").disabled = true;
					document.getElementById("doneAttack").disabled = true;
				}else if(btn_id=="doneFortify")
				{
					document.getElementById("first").disabled = true;
					document.getElementById("remove_unit").disabled = true;
					document.getElementById("place_unit").disabled = true;
					document.getElementById("doneFortify").disabled = true;
				}
			}
		</script>
		
	</head>
	<body>
		<div id="container">
			<div id="left">
				<canvas id="board" height="600" width="815" ></canvas>
			</div>
				<div id="right">
					<div>
						<p>Main Territory Select</p>
						<select id="first" onchange="selectTerritory();">	<!-- select tag for the territories -->
							<option value="<?php echo $terr?>">Afghanistan</option>			<!-- calls selectTerritory() on change -->
							<option value="1">Alaska</option>
							<option value="2">Alberta</option>
							<option value="3">Argentina</option>
							<option value="4">Brazil</option>
							<option value="5">Central Africa</option>
							<option value="6">Central America</option>
							<option value="7">China</option>
							<option value="8">Eastern Africa</option>
							<option value="9">Eastern Australia</option>
							<option value="10">Eastern Canada</option>
							<option value="11">Eastern US</option>
							<option value="12">Egypt</option>
							<option value="13">Great Britain</option>
							<option value="3">Greenland</option>
							<option value="15">Iceland</option>
							<option value="16">India</option>
							<option value="17">Indonesia</option>
							<option value="18">Irkutsk</option>
							<option value="19">Japan</option>
							<option value="20">Kamchatka</option>
							<option value="21">Madagascar</option>
							<option value="22">Middle East</option>
							<option value="23">Mongolia</option>
							<option value="24">New Guinea</option>
							<option value="25">Northern Africa</option>
							<option value="26">Northern Europe</option>
							<option value="2">NW Territory</option>
							<option value="28">Ontario</option>
							<option value="29">Peru</option>
							<option value="30">Russia</option>
							<option value="31">Scandinavia</option>
							<option value="32">Siberia</option>
							<option value="33">Southern Africa</option>
							<option value="34">Southern Europe</option>
							<option value="35">SE Asia</option>
							<option value="36">Ural</option>
							<option value="37">Venezuela</option>
							<option value="38">Western Australia</option>
							<option value="39">Western Europe</option>
							<option value="40">Western US</option>
							<option value="41">Yakutsk</option>
						</select>
					</div>
					<div id="place">
						<p>Unit Placement Controls</p> <!--  -->
						<div id="units"></div>
						<button id="add_unit" onclick="">Place</button>
						<button id="reset"onclick="resetunits">Reset</button>
						<button id="donePlace" onclick="doneButtonClick(this.id);">Done</button>	<!-- done button calls function on click -->
					</div>
					<div id="attack">
						<p>Attack Controls</p>
						<select id="second" onchange="selectTerritory();">	<!-- selection for territories -->
							<option value="0">Afghanistan</option>			<!-- calls selectTerritory() onchange -->
							<option selected="true" value="1">Alaska</option>
							<option value="2">Alberta</option>
							<option value="3">Argentina</option>
							<option value="4">Brazil</option>
							<option value="5">Central Africa</option>
							<option value="6">Central America</option>
							<option value="7">China</option>
							<option value="8">Eastern Africa</option>
							<option value="9">Eastern Australia</option>
							<option value="10">Eastern Canada</option>
							<option value="11">Eastern US</option>
							<option value="12">Egypt</option>
							<option value="13">Great Britain</option>
							<option value="14">Greenland</option>
							<option value="15">Iceland</option>
							<option value="16">India</option>
							<option value="17">Indonesia</option>
							<option value="18">Irkutsk</option>
							<option value="19">Japan</option>
							<option value="20">Kamchatka</option>
							<option value="21">Madagascar</option>
							<option value="22">Middle East</option>
							<option value="23">Mongolia</option>
							<option value="24">New Guinea</option>
							<option value="25">Northern Africa</option>
							<option value="26">Northern Europe</option>
							<option value="27">NW Territory</option>
							<option value="28">Ontario</option>
							<option value="29">Peru</option>
							<option value="30">Russia</option>
							<option value="31">Scandinavia</option>
							<option value="32">Siberia</option>
							<option value="33">Southern Africa</option>
							<option value="34">Southern Europe</option>
							<option value="35">SE Asia</option>
							<option value="36">Ural</option>
							<option value="37">Venezuela</option>
							<option value="38">Western Australia</option>
							<option value="39">Western Europe</option>
							<option value="40">Western US</option>
							<option value="41">Yakutsk</option>
						</select>
						<button id="init_attack" onclick="">Attack</button>
						<button id="doneAttack" onclick="doneButtonClick(this.id);">Done</button>	<!-- done button calls function on click -->
					</div>
					<div id="fortify">
						<p>Fortify Controls</p>
						<button id="remove_unit" onclick="">Remove Unit</button>
						<button id="place_unit" onclick="">Place Unit</button>
						<button id="doneFortify" onclick="doneButtonClick(this.id);">Done</button>	<!-- done button calls function on click -->
					</div>
				</div>
		</div>
	</body>
</html>