<?php 

namespace model;

class User {
	private $loginUserName;
	private $loginPassword;
	private $isLoggedIn = false;
	private $userDAL;
	
	public function __construct(UserDAL $userDAL) {
		$this->userDAL = $userDAL;
	}
	
	/**
	 * Takes login information, saves it, and checks if it is correct
	 * @param $userName, String username
	 * @param $password, String password
	 */
	public function login($userName, $password) {
		$this->isLoggedIn = false; // reset the login status, in case the user is already marked as logged in
		
		$this->loginUserName = $userName;
		$this->loginPassword = $password;
		
		if(trim($userName) == '') {
			throw new \Exception('Username is missing');
		} else if (trim($password) == '') {
			throw new \Exception('Password is missing');
		}
		
		if($this->userDAL->userNameExists($userName) && password_verify($this->userDAL->getPassword($userName), $password)) {
			$this->isLoggedIn = true;
		} else {
			throw new \Exception('Wrong name or password');
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