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
		
		try {
			if(strlen($userName) > 2) {
				if(strlen($password) > 5) {
					if(!$this->model->userNameExists($userName)) {
						if($userName == strip_tags($userName)) {
							if($password == $passwordRepeat) {
								$this->model->addUser($userName, $password);
								$this->view->registrationSuccess();
							} else {
								throw new \Exception('Passwords do not match.');
							}
						} else {
							$this->view->setRequestUserName(strip_tags($userName));
							throw new \Exception('Username contains invalid characters.');
						}
					} else {
						throw new \Exception('User exists, pick another username.');
					}
				}
			}
		}
		catch (\Exception $e) {
			$this->view->saveError($e->getMessage());
		}
	}
}
