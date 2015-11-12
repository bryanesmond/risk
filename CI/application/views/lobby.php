

<h1>Welcome! Create or join a game here.</h1>
<?php

$this->load->helper('form');
$form_attributes = array('class' => 'create', 'id' => 'create');
echo form_open('lobbycon/createGame', $form_attributes);
$create_data = array('name' => 'create', 'id' => 'create', 'value' => 'Create New Game');
echo form_submit($create_data);
echo form_close();
echo "<br>";

	//PRINT GAMES LIST
	if ($games->num_rows() > 0)
	{
	   foreach ($games->result() as $row)
		{
			echo "<b>Game ID: </b>".$row->game_id." ";
			echo "<b>Creator: </b>".$row->creator_id." ";
			echo "<b>Status: </b>".$row->status." ";
			$join_game_attributes = array('class' => 'join', 'id' => 'join');
			echo form_open('lobbycon/joinGame', $join_game_attributes);
			$game_data = array(
              'game_id'   => $row->game_id
            );
			echo form_hidden($game_data);
			$join_data = array('name' => 'join', 'id' => 'join', 'value' => 'Join Game');
			echo form_submit($join_data);
			echo form_close();
			
		}
	}
	else {
		echo "No Games Created Yet";
	}

	echo "<br><br>";
	$logout_attributes = array('class' => 'logout', 'id' => 'logout');
	echo form_open('welcome/logout', $logout_attributes);
	$logout_data = array('name' => 'logout', 'id' => 'logout', 'value' => 'Logout');
	echo form_submit($logout_data);
	echo form_close();
	
?>
