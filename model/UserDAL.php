<?php

namespace model;

class UserDAL {
	private static $fileName = "data/users.txt";
	
	public function getUserNames() {
		if(file_exists(self::$fileName)) {
			$userNames = file(self::$fileName, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
			
			// Remove all passwords
			for ($i = 1; $i < count($userNames); $i += 2) {
				array_splice($userNames, $i, 1);
			}
			
			return $userNames;
		}
	}
	
	/**
	 * Checks if the entered username exists in the 'database'.
	 * @return true if it username exists, false otherwise
	 */
	public function userNameExists($userNameToFind) {
		$userNames = $this->getUserNames();
		
		for ($i = 0; $i < count($userNames); $i += 1) {
			if($userNames[$i] == $userNameToFind) {
				return true;
			}
		}
		return false;
	}
	
	public function addUser($userName, $password) {
		$file = fopen(self::$fileName, 'a');
		
		fwrite($file, $userName.PHP_EOL);
		fwrite($file, $password.PHP_EOL);
		
		fclose($file);
	}
	
	public function getPassword($userName) {
		if(file_exists(self::$fileName)) {
			$userData = file(self::$fileName, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
			
			for ($i = 0; $i < count($userData); $i += 2) {
				if($userData[$i] == $userName) {
					return $userData[$i + 1];
				}
			}
		}
	}
}
