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
		
		if($userName == self::$userName && hash_equals($this->encryptPassword(self::$password), $password)) {
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
	
	public function encryptPassword($password) {
		return crypt($password, '$2a$07$derp$');
	}
}
?>