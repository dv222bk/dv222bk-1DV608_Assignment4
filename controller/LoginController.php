<?php
    
    namespace controller;
	
	class LoginController{
		
		private $view;
		private $model;
		
		public function __construct(\view\LoginView $view, \model\User $model) {
			$this->view = $view;
			$this->model = $model;
		}
		
		public function loginUser() {
			$this->model->login($this->view->getRequestUserName(), $this->view->getRequestPassword());
		}
		
		public function logoutUser() {
			$this->model->logout();
		}
	}
?>