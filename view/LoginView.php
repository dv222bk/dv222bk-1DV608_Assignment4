<?php

class LoginView {
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';
	private $loggedIn = false;

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
			$userName = $this->getRequestUserName();
			$password = $this->getRequestPassword();
			
			if($userName == null) {
				$message = 'Username is missing';
			} else if ($password == null) {
				$message = 'Password is missing';
			} else if ($userName != 'Admin' || $password != 'Password') {
				$message = 'Wrong name or password';
			} else {
				$message = "Welcome";
			}
		}
		
		if($this->getLogoutAttempt()) {
			$message = "Bye bye!";
		}
		
		if($message == "Welcome") {
			$response = $this->generateLogoutButtonHTML($message);
			$this->loggedIn = true;
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
	private function getRequestUserName() {
		if(isset($_POST[self::$name])) {
			return $_POST[self::$name];
		} else {
			return null;
		}
	}
	
	/**
	 * Get the user entered password
	 * @return the entered password, or null if no password is entered
	 */
	private function getRequestPassword() {
		if(isset($_POST[self::$password])) {
			return $_POST[self::$password];
		} else {
			return null;
		}
	}
	
	/**
	 * Check if a login attempt is made
	 * @return true if the user tried to login, false otherwise
	 */
	private function getLoginAttempt() {
		return isset($_POST[self::$login]);
	}
	
	/**
	 * Check if a logout attempt is made
	 * @return true if the user tried to logout, false otherwise
	 */
	private function getLogoutAttempt() {
		return isset($_POST[self::$logout]);
	}
	
	/**
	 * Check if the user wants to stay logged in
	 * @return true if the user wants to stay logged in, false otherwise
	 */
	 private function getKeepLoggedIn() {
	 	return isset($_POST[self::$keep]);
	 }
	
	/**
	 * Check if the user is logged in
	 * @return true if the user is logged in, false otherwise
	 */
	public function isUserLoggedIn() {
		return $this->loggedIn;
	}
}