<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	* Index Page for this controller.
	*
	* Maps to the following URL
	* http://example.com/index.php/welcome
	*- or -  
	* http://example.com/index.php/welcome/index
	*- or -
	* Since this controller is set as the default controller in
	* config/routes.php, it's displayed at http://example.com/
	*
	* So any other public methods not prefixed with an underscore will
	* map to /index.php/welcome/<method_name>
	* @see http://codeigniter.com/user_guide/general/urls.html
	*
	* $this->load->model('course')
	* $method = isset($_POST['method']) ? $_POST['method'] : "";
	* $args = isset($_POST['args']) ? $_POST['args'] : "";
	* $response = $this->course->$method($args);
	* json_encode($response)
	*/
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
		$this->load->library('encrypt');
		$this->load->helper('security');
		$this->load->helper('url');
		$this->load->model('Login');


	}
	
	public function index()
	{
		$loggedin = $this->session->userdata('loggedin');
		$loggedin = FALSE;
		if ($loggedin == FALSE) {
			$this->load->view('index');
			if(isset($_POST['username']) && isset($_POST['password'])) 
			{
				//echo 'username/password set!<br />';
				//echo $_POST['username'];
				//echo '<br />';
				//echo $_POST['password'];
	
				$username = $_POST['username'];
				$password = $_POST['password'];
	
				$data = $this->Login->login($username, $password);
	
				//$this->db->select('salt, password_hash');
				//$this->db->from('authentication');
				//$this->db->where('username', $username);
				//$this->db->where('password_hash', $this->encrypt->sha1($password));
				//$query = $this->db->get('authentication', 1);
			
				//echo $query->num_rows();
				echo $data;

						if ($data == 1)
						{
							//login work
							$this->session->set_userdata(
								array(
								'username'      => $username,
								'loggedin'        => TRUE
								));
								
							/*echo "log in work";
							echo "<br><br>";
							$redirect_attributes = array('class' => 'redirect', 'id' => 'redirect');
							echo form_open('lobbycon/index', $redirect_attributes);
							$redirect_data = array('name' => 'redirect', 'id' => 'redirect', 'value' => 'Redirect to Lobby');
							echo form_submit($redirect_data);
							echo form_close();*/
							redirect('/lobbycon/index', 'refresh');
						
						}
						else {
							//login didn't work
							echo " login did not work";
						}
					
			}
			else {
				echo 'Please Enter a Username and Password';
			}
		}
		else {
			redirect('/lobbycon/index', 'refresh');
		}
		

	}
	

	public function register() 
	{
		$loggedin = $this->session->userdata('loggedin');
		
		if ($loggedin == FALSE) {
				$this->load->view('registration');
				if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['confirm_pass'])) 
				{

					$username = ($_POST['username']);
					$password = ($_POST['password']);
					$confirm_pass = ($_POST['confirm_pass']);
		
					if($password != $confirm_pass)
						die ('Passwords do not match');
					if($username == NULL || $password == NULL || $confirm_pass == NULL)
						die('All fields required');
					//hash and salt password
					$salt = mt_rand();
					$pwSalt = $salt.$password;
					$hash = $this->encrypt->sha1($password);

		
					//$register_query = array(
					//	'username' => $username ,
					//	'password_hash' => $hash ,
					//	'pwsalt' => $pwSalt,
					//	'salt' => $salt
					//);
		
					//$this->db->select('username');
					//$this->db->where('username', $username);
					//$query = $this->db->get('authentication', 1);

					$data = $this->Login->check($username);
		

					if ($data != 0) {
						echo "Username already exists. Please try again.";
					}
					else {
						$this->Login->register($username, $hash, $pwSalt, $salt);
						//$dbRet = $this->db->insert('authentication', $register_query);
						redirect('/welcome/index', 'refresh');
					}
				}
		}
		else {
			redirect('/lobbycon/index', 'refresh');
		}
				 
			
	}
	
	public function logout() {
			
				$this->session->sess_destroy();
				redirect('/welcome/index', 'refresh');
	}
	
	
} 

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
