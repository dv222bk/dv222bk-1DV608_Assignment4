<?php
    
namespace controller;

class RegisterController {
	
	private $view;
	private $model;
	
	public function __construct(\view\RegisterView $view, \model\NewUser $model) {
		$this->view = $view;
		$this->model = $model;
	}
	
	public function registerUser() {
		
	}
}
