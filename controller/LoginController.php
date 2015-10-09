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
			try {
				if($this->view->sessionsExists()) {
					if($this->view->checkSessionUserAgent()) { // check if the session user is in fact the real session owner
						$this->model->login($this->view->getSessionName(), $this->view->getSessionPassword());
					} else {
						$this->model->login('', ''); // force fail login
					}
				} else if ($this->view->cookiesExists()) {
					$this->model->login($this->view->getCookieName(), $this->view->getCookiePassword());
				} else {
					if($this->view->getLoginAttempt()) {
						if($this->view->getRequestPassword() == '') {
							$this->model->login($this->view->getRequestUserName(), ''); // login with empty password so that a exception is generated
						}
						$this->model->login($this->view->getRequestUserName(), $this->model->encryptPassword($this->view->getRequestPassword()));
					}
				}
			}
			catch (\Exception $e) {
				$this->view->saveError($e->getMessage());
			}
		}
		
		/**
		 * Logout the user
		 */
		public function logoutUser() {
			$this->model->logout();
		}
		
		/**
		 * Check if the user is logged in
		 * @return true if the user is logged in, false otherwise
		 */
		public function isUserLoggedIn() {
			return $this->model->getLoginStatus();
		}
	}
?>