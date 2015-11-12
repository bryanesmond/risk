//logout view

<h1>Log In</h1>
<?
	$this->load->helper('form');
	echo "<br><br>";
	$logout_attributes = array('class' => 'logout', 'id' => 'logout');
	echo form_open('welcome/logout', $logout_attributes);
	$logout_data = array('name' => 'logout', 'id' => 'logout', 'value' => 'Logout');
	echo form_submit($logout_data);
	echo form_close();
			
?>