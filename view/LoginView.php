<?php

namespace view;

class LoginView {
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';
	private $user;

	public function __construct(\model\User $user) {		
		$this->user = $user;
	}

	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response() {
		$message = '';
		
		if($this->getLoginAttempt()) {
			if(trim($this->getRequestUserName()) == '') {
				$message = 'Username is missing';
			} else if (trim($this->getRequestPassword()) == '') {
				$message = 'Password is missing';
			} else if (!$this->user->getLoginStatus()) {
				$message = 'Wrong name or password';
			} else {
				$message = 'Welcome';
			}
			
			if($this->user->getLoginStatus()) {
				if($this->getKeepLoggedIn()){
					$time = time() + 86400;
					$message .= ' and you will be remembered';
				} else {
					$time = 0;
				}
				setcookie(self::$cookieName, $this->getRequestUserName(), $time);
				setcookie(self::$cookiePassword, $this->getRequestPassword(), $time);
			}
		}
		
		if($this->getLogoutAttempt()) {
			$message = "Bye bye!";
			setcookie(self::$cookieName, '', time() - 3600);
			setcookie(self::$cookiePassword, '', time() - 3600);
		}
		
		if($this->user->getLoginStatus()) {
			$response = $this->generateLogoutButtonHTML($message);
		} else {
			$response = $this->generateLoginFormHTML($message);
		}
		return $response;
	}

	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLogoutButtonHTML($message) {
		return '
			<form  method="post" >
				<p id="' . self::$messageId . '">' . $message .'</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
	}
	
	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLoginFormHTML($message) {
		return '
			<form method="post" > 
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>
					
					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . $this->getRequestUserName() . '" />

					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />

					<label for="' . self::$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />
					
					<input type="submit" name="' . self::$login . '" value="login" />
				</fieldset>
			</form>
		';
	}
	
	/**
	 * Get the user entered username
	 * @return the entered username, or null if no username is entered
	 */
	public function getRequestUserName() {
		if(isset($_COOKIE[self::$cookieName])) {
			return $_COOKIE[self::$cookieName];
		} else if(isset($_POST[self::$name])) {
			return $_POST[self::$name];
		} else {
			return '';
		}
	}
	
	/**
	 * Get the user entered password
	 * @return the entered password, or null if no password is entered
	 */
	public function getRequestPassword() {
		if(isset($_COOKIE[self::$cookiePassword])) {
			return $_COOKIE[self::$cookiePassword];
		} else if(isset($_POST[self::$password]) && $_POST[self::$password] != '') {
			return $this->user->encryptPassword($_POST[self::$password]);
		} else {
			return '';
		}
	}
	
	/**
	 * Check if a login attempt is made
	 * @return true if the user tried to login, false otherwise
	 */
	public function getLoginAttempt() {
		return isset($_POST[self::$login]);
	}
	
	/**
	 * Check if a logout attempt is made
	 * @return true if the user tried to logout, false otherwise
	 */
	public function getLogoutAttempt() {
		return isset($_POST[self::$logout]);
	}
	
	/**
	 * Check if the user wants to stay logged in
	 * @return true if the user wants to stay logged in, false otherwise
	 */
	 public function getKeepLoggedIn() {
	 	return isset($_POST[self::$keep]);
	 }
	
	/**
	 * Check if the user is logged in
	 * @return true if the user is logged in, false otherwise
	 */
	public function isUserLoggedIn() {
		return $this->user->getLoginStatus();
	}
}