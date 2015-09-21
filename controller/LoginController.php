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
			if($this->view->sessionsExists()) {
				$this->model->login($this->view->getSessionName(), $this->view->getSessionPassword());
			} else if ($this->view->cookiesExists()) {
				$this->model->login($this->view->getCookieName(), $this->view->getCookiePassword());
			} else {
				$this->model->login($this->view->getRequestUserName(), $this->view->getRequestPassword());
			}
		}
		
		public function logoutUser() {
			$this->model->logout();
		}
	}
?>