<?php 

namespace model;

class User {
	private static $userName = "Admin";
	private static $password = "Password";
	private $loginUserName;
	private $loginPassword;
	private $isLoggedIn = false;
	
	/**
	 * Takes login information, saves it, and checks if it is correct
	 * @param $userName, String username
	 * @param $password, String password
	 */
	public function login($userName, $password) {
		$this->loginUserName = $userName;
		$this->loginPassword = $password;
		
		if($userName == self::$userName && password_verify(self::$password, $password)) {
			$this->isLoggedIn = true;
		} else {
			$this->isLoggedIn = false;
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
		$this->loginUserName = null;
		$this->loginPassword = null;
	}
	
	/**
	 * Creates a hashed password from a string
	 * @param password, String to be hashed
	 * @return the hashed password
	 */
	public function encryptPassword($password) {
		return password_hash($password, PASSWORD_DEFAULT);
	}
}
?>