<?php

namespace view;

class RegisterView {
	private static $register = 'RegisterView::Register';
	private static $name = 'RegisterView::UserName';
	private static $password = 'RegisterView::Password';
	private static $passwordRepeat = 'RegisterView::PasswordRepeat';
	private static $messageId = 'RegisterView::Message';
	private $user;
	private $customMessage;
	
	public function __construct(\model\User $user) {
		$this->user = $user;
	}
	
	public function response() {
		$message = '';
		
		if($this->getRegisterAttempt()) {
			if (strlen(trim($this->getRequestUserName())) < 3) {
				$message .= 'Username has too few characters, at least 3 characters.<br />';
			}
			
			if (strlen(trim($this->getRequestPassword())) < 6) {
				$message .= 'Password has too few characters, at least 6 characters.<br />';
			}
		}
		
		if(isset($this->customMessage)) {
			$message = $this->customMessage;
		}
		
		$response = $this->generateRegisterFormHTML($message);
		
		return $response;
	}
	
	public function setCustomMessage($message) {
		$this->customMessage = $message;
	}
	
	private function generateRegisterFormHTML($message) {
		return '
			<form method="post" > 
				<fieldset>
					<legend>Register a new user - Write username and password </legend>
					<p id="' . self::$messageId . '">' . $message . '</p>
					
					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . $this->getRequestUserName() . '" />

					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />
					
					<label for="' . self::$passwordRepeat . '">Repeat password :</label>
					<input type="password" id="' . self::$passwordRepeat . '" name="' . self::$passwordRepeat . '" />
					
					<input type="submit" name="' . self::$register . '" value="Register" />
				</fieldset>
			</form>
		';
	}

	public function registrationSuccess() {
		$_SESSION['registrated'] = trim($_POST[self::$name]);
		unset($_GET['register']);
	}

	public function getRequestUserName() {
		if(isset($_POST[self::$name])) {
			return $_POST[self::$name];
		} else {
			return '';
		}
	}
	
	public function setRequestUserName($userName) {
		$_POST[self::$name] = $userName;
	}
	
	public function getRequestPassword() {
		if(isset($_POST[self::$password])) {
			return $_POST[self::$password];
		} else {
			return '';
		}
	}
	
	public function getRequestPasswordRepeat() {
		if(isset($_POST[self::$passwordRepeat])) {
			return $_POST[self::$passwordRepeat];
		} else {
			return '';
		}
	}
	
	public function getRegisterAttempt() {
		return isset($_POST[self::$register]);
	}
}