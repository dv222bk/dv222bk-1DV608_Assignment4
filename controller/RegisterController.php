<?php
    
namespace controller;

class RegisterController {
	
	private $view;
	private $model;
	
	public function __construct(\view\RegisterView $view, \model\UserDAL $model) {
		$this->view = $view;
		$this->model = $model;
	}
	
	public function registerUser() {
		$userName = trim($this->view->getRequestUserName());
		$password = trim($this->view->getRequestPassword());
		$passwordRepeat = trim($this->view->getRequestPasswordRepeat());
		
		if(strlen($userName) > 2) {
			if(strlen($password) > 5) {
				if(!$this->model->userNameExists($userName)) {
					if($userName == strip_tags($userName)) {
						if($password == $passwordRepeat) {
							$this->model->addUser($userName, $password);
							$this->view->registrationSuccess();
						} else {
							$this->view->setCustomMessage("Passwords do not match.");
						}
					} else {
						$this->view->setCustomMessage("Username contains invalid characters.");
						$this->view->setRequestUserName(strip_tags($userName));
					}
				} else {
					$this->view->setCustomMessage("User exists, pick another username.");
				}
			}
		}
	}
}
