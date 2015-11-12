
<h1>Log In</h1>
<!--code igniter handling args/form open and base_url-->
<?  $this->load->helper('form');
	$form_attributes = array('class' => 'login', 'id' => 'login');
	echo form_open('welcome/index', $form_attributes);
	$user_data = array('name' => 'username',
						'id' => 'username',
						'placeholder' => 'Username'
						);
	$password_data = array('name' => 'password',
							'id' => 'password',
							'placeholder' => 'Password'
							);
	$submit_data = array('name' => 'submit',
						'id' => 'submit',
						'value' => 'Login'
						);
	echo form_input($user_data);
	echo form_password($password_data);
	echo form_submit($submit_data);
	echo form_close();
	echo '<br /><br />';
	
	$register_attributes = array('class' => 'register', 'id' => 'register');
	echo form_open('welcome/register', $register_attributes);
	$register_data = array('name' => 'submit', 
							'id' => 'submit',
							'value' => 'Register a New Username Here');
	echo form_submit($register_data);
	echo form_close();
?>
	
