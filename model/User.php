<?php 

namespace model;

class User {
	private static $userName = "Admin";
	private static $password = "Password";
	private $loginUserName;
	private $loginPassword;
	private $isLoggedIn = false;
	
	public function __construct() {	
	
	}
	
	/**
	 * Takes login information, saves it, and checks if it is correct
	 * @param $userName, String username
	 * @param $password, String password
	 */
	public function login($userName, $password) {
		$this->loginUserName = $userName;
		$this->loginPassword = $password;
		
		if($userName == self::$userName && $password == self::$password) {
			$this->isLoggedIn = true;
		} else {
			$this->isLoggedIn = false;
		}
	}
	
	/**
	 * Checks if the saved login information is correct
	 * @return status message
	 */
	public function getLoginMessage() {
		if(trim($this->loginUserName) == null) {
			return 'Username is missing';
		} else if (trim($this->loginPassword) == null) {
			return 'Password is missing';
		} else if ($this->loginUserName != self::$userName || $this->loginPassword != self::$password) {
			return 'Wrong name or password';
		} else {
			return "Welcome";
		}
	}
	
	/**
	 * Returns the loginstatus
	 * @return true if the user is logged in, false otherwise
	 */
	public function getLoginStatus() {
		return $this->isLoggedIn;
	}
	
	/**
	 * Logsout the user
	 */
	public function logout() {
		$this->isLoggedIn = false;
	}
}
?>