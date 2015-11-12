<? class Authenticate extends CI_Model {
	
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	public function authenticate($username, $password) {
		return "Username: ".$username."Password: ".$password;
		/*$this->password = $_POST['password'];
		$this->salt = mt_rand();
		$this->pwhash = sha1($salt.$password);
		$this->username= $_POST['username'];
		return $this->db->select('SELECT salt, password_hash FROM '.dbprefix.'authentication WHERE username = :username', array(':username'=>$username));
	}
	
}
?>