<?php
    
    namespace controller;
	
	class LoginController{
		
		private $view;
		private $model;
		
		public function __construct(\view\LoginView $view, \model\User $model) {
			$this->view = $view;
			$this->model = $model;
		}
		
		/**
		 * Login the user
		 */
		public function loginUser() {
			if($this->view->sessionsExists()) {
				if($this->view->checkSessionUserAgent()) {
					$this->model->login($this->view->getSessionName(), $this->view->getSessionPassword());
				} else {
					$this->model->login('', '');
				}
			} else if ($this->view->cookiesExists()) {
				$this->model->login($this->view->getCookieName(), $this->view->getCookiePassword());
			} else {
				$this->model->login($this->view->getRequestUserName(), $this->view->getRequestPassword());
			}
		}
		
		/**
		 * Logout the user
		 */
		public function logoutUser() {
			$this->model->logout();
		}
	}
?>