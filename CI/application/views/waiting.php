<?php

//PRINT GAME DETAILS
if ($games->num_rows == 0) {
	redirect('/lobbycon/index', 'refresh');
}
else {
	foreach ($games->result() as $row) {
		if ($action == 'create') {
			echo $username." Created Game.<br><br>";
		}
		/*else if ($action == 'join') {
			echo $username." Joined Game.<br><br>";
		}*/
			echo "<b>Game ID: </b>".$row->game_id."<br>";
			echo "<b>Creator: </b>".$row->creator_id."<br>";
			echo "<b>Status: </b>".$row->status."<br>";
	
			if ($color == '1') {
				$this->load->helper('form');
				echo "<br><br>";
				$start_game_attributes = array('class' => 'start', 'id' => 'start');
					echo form_open('lobbycon/startGame', $start_game_attributes);
					$game_data = array(
						  'game_id'   => $row->game_id
					);
					echo form_hidden($game_data);
					
					$start_data = array('name' => 'start', 'id' => 'start', 'value' => 'Start Game');
					echo form_submit($start_data);
					
					echo form_close();
					
					/*new form vvv
					
					echo form_open('lobbycon/refreshPage');
					
					$refresh_data = array('name' => 'refresh', 'id' => 'refresh', 'value' => 'Refresh');
					echo form_submit($refresh_data);
					
					echo form_close();*/
						
					
			}

	}
			//Print Members of Game, Update using Ajax
			echo "<br><b>Members of Game</b><br>";
			
			foreach ($players->result() as $row) {
				echo "User: ".$row->username;
				echo " Color: ".$row->color."<br>";
			}
}

/*$this->load->helper('form');
	echo "<br><br>";
	$logout_attributes = array('class' => 'logout', 'id' => 'logout');
	echo form_open('welcome/logout', $logout_attributes);
	$logout_data = array('name' => 'logout', 'id' => 'logout', 'value' => 'Logout');
	echo form_submit($logout_data);
	echo form_close();
*/
?>

<!DOCTYPE html>
<html>
	<head>

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
		<link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet">
		
		<!--jQuery-->

		<!--Bootstrap-->
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootswatch/3.1.1/cosmo/bootstrap.min.css">
		<!--<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootswatch/3.1.1/journal/bootstrap.min.css">-->
		<!--<link href="/twitter-bootstrap/twitter-bootstrap-v2/docs/assets/css/bootstrap.css" rel="stylesheet">-->
		<!--<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-theme.min.css">-->
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
		<!--Bootstrap-->
	<!--<link rel="stylesheet" type="text/css" href="icon.css">-->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript">
            
		var controller = 'lobbycon';
	    var base_url = 'babbage.cs.missouri.edu/~ljtgy5/swe-fs14gd/CI/index.php';
	    
       function ajax_refresh(){
       		document.write("sup");
            try {
				$.ajax({
					'url' : 'babbage.cs.missouri.edu/~ljtgy5/swe-fs14gd/CI/index.php/lobbycon/pageRefresh',
					'type' : 'POST', //the way you want to send data to your URL
					'success' : function(data){ //probably this request will return anything, it'll be put in var "data"
						var container = $('#container'); //jquery selector (get element by id)
						if(data){
							container.html(data);
							document.write("worked!");
						}else{
							document.write("Hello World!");
						}
					}
					});
				}
       		}
        	catch (error) {
            	alert("failed attempt");
        	}
	</script>
	</head>
	<body>
		<div id="container">
		
		</div>
		<button id="refresh" onclick="ajax_refresh();">Refresh</input>
	</body>
</html>