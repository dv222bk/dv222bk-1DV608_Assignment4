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
	private static $sessionName = 'LoginView::SessionName';
	private static $sessionPassword = 'LoginView::SessionPassword';
	private static $sessionUserAgent = 'LoginView::SessionUserAgent';
	private $user;

	public function __construct(\model\User $user) {
		session_start();
		session_regenerate_id();
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
		
		// if the user tries to log in (and isn't already logged in)
		if($this->getLoginAttempt() && !$this->sessionsExists() && !$this->cookiesExists()) {
			// create a status message
			if(trim($this->getRequestUserName()) == '') {
				$message = 'Username is missing';
			} else if (trim($this->getRequestPassword()) == '') {
				$message = 'Password is missing';
			} else if (!$this->user->getLoginStatus()) {
				$message = 'Wrong name or password';
			} else {
				$message = 'Welcome';
			}
			
			// if the user sucessfully logged in
			if($this->user->getLoginStatus()) {
				
				// if the user wants to be logged in for a long time, create a cookie
				if($this->getKeepLoggedIn()){
					$message .= ' and you will be remembered';
					$this->setCookies($this->getRequestUserName(), $this->getRequestPassword(), time() + 86400);
				}
				// set session
				$this->setSessions($this->getRequestUserName(), $this->getRequestPassword());
			}
		}
		
		// if the user tries to logout (and isn't already logged out)
		if($this->getLogoutAttempt() && $this->sessionsExists()) {
			$message = "Bye bye!";
			$this->clearCookies();
			$this->clearSessions();
		}
		
		// if a cookie exists, but the user isn't logged in, the cookie contains wrong information.
		if($this->cookiesExists() && !$this->user->getLoginStatus()) {
			$message = "Wrong information in cookies";
			$this->clearCookies();
			$this->clearSessions();
		// else, if a cookie exists but a session does not, the user logged in with a cookie
		} else if ($this->cookiesExists() && !$this->sessionsExists()) {
			$message = "Welcome back with cookie";
			$this->setSessions($_COOKIE[self::$cookieName], $_COOKIE[self::$cookiePassword]);
		}
		
		// create response depending on the users login status
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
	
	/**
	 * Check if the user is logged in this session and get the username
	 * @return the username if the user is logged in, empty string otherwise
	 */
	public function getSessionName() {
		if(isset($_SESSION[self::$sessionName])) {
			return $_SESSION[self::$sessionName];
		}
		return '';
	}
	
	/**
	 * Check if the user is logged in this session and get the hashed password
	 * @return the hashed password if the user is logged in, empty string otherwise
	 */
	public function getSessionPassword() {
		if(isset($_SESSION[self::$sessionPassword])) {
			return $_SESSION[self::$sessionPassword];
		}
		return '';
	}
	
	/**
	 * Check if the user is logged in with cookies and get the username
	 * @return the username if the user is logged in, empty string otherwise
	 */
	public function getCookieName() {
		if(isset($_COOKIE[self::$cookieName])) {
			return $_COOKIE[self::$cookieName];
		}
		return '';
	}
	
	/**
	 * Check if the user is logged in with cookies and get the hashed password
	 * @return the hashed password if the user is logged in, empty string otherwise
	 */
	public function getCookiePassword() {
		if(isset($_COOKIE[self::$cookiePassword])) {
			return $_COOKIE[self::$cookiePassword];
		}
		return '';
	}
	
	/**
	 * Checks if the user is logged in using session
	 * @return true if the user is logged in with sessions, false otherwise
	 */
	public function sessionsExists() {
		if(isset($_SESSION[self::$sessionName]) && isset($_SESSION[self::$sessionPassword]) && isset($_SESSION[self::$sessionUserAgent])) {
			return true;
		}
		return false;
	}
	
	/**
	 * Checks if the user is using the same user agent this request as the last one.
	 * Prevents session hijacks
	 * @return true if the user is using the same user agent, false otherwise
	 */
	public function checkSessionUserAgent() {
		if(isset($_SESSION[self::$sessionUserAgent]) && $_SESSION[self::$sessionUserAgent] == $_SERVER["HTTP_USER_AGENT"]){
			return true;
		}
		return false;
	}
	
	/**
	 * Checks if the user is logged in using cookies
	 * @return true if the user is logged in with cookies, false otherwise
	 */
	public function cookiesExists() {
		if(isset($_COOKIE[self::$cookieName]) && isset($_COOKIE[self::$cookiePassword])) {
			return true;
		}
		return false;
	}
	
	/**
	 * Sets the sessions used for keeping the user logged in
	 * @param $userName, String username
	 * @param $password, String hashed password
	 */
	public function setSessions($userName, $password) {
		$_SESSION[self::$sessionName] = $userName;
		$_SESSION[self::$sessionPassword] = $password;
		$_SESSION[self::$sessionUserAgent] = $_SERVER["HTTP_USER_AGENT"];
	}
	
	/**
	 * Sets the cookies used for keeping the user logged in
	 * @param $userName, String username
	 * @param $password, String hashed password
	 * @param $time, int seconds since unix epoch, how long the cookie should exist
	 */
	public function setCookies($userName, $password, $time) {
		setcookie(self::$cookieName, $userName, $time);
		setcookie(self::$cookiePassword, $password, $time);
	}
	
	/**
	 * Clears the cookies used for keeping the user logged in
	 */
	public function clearCookies() {
		// Make sure the cookie is deleted by first overwriting the value and time it to an earlier date...
		setcookie(self::$cookieName, '', time() - 4600);
		setcookie(self::$cookiePassword, '', time() - 4600);
		// And then unset it.
		unset($_COOKIE[self::$cookieName]);
		unset($_COOKIE[self::$cookiePassword]);
	}
	
	/**
	 * Clears the sessions used for keeping the user logged in
	 */
	public function clearSessions() {
		unset($_SESSION[self::$sessionName]);
		unset($_SESSION[self::$sessionPassword]);
		unset($_SESSION[self::$sessionUserAgent]);
	}
}