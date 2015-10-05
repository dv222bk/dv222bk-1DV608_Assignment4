<?php

namespace view;

class LayoutView {
  
	public function render($isLoggedIn, LoginView $v, DateTimeView $dtv, RegisterView $rv) {
		echo '<!DOCTYPE html>
		  <html>
		    <head>
		      <meta charset="utf-8">
		      <title>Login Example</title>
		    </head>
		    <body>
		      <h1>Assignment 2</h1>
		      ' . $this->getMenu() . '
		      ' . $this->renderIsLoggedIn($isLoggedIn) . '
		      
		      <div class="container">
		          ' . $this->getContent($v, $rv) . '
		          
		          ' . $dtv->show() . '
		      </div>
		     </body>
		  </html>
		';
	}
  
	private function renderIsLoggedIn($isLoggedIn) {
		if ($isLoggedIn) {
			return '<h2>Logged in</h2>';
		} else {
	  		return '<h2>Not logged in</h2>';
	    }
	}
	
	private function getMenu() {
		if(isset($_GET['register'])) {
			return '<a href="?">Back to login</a>';	
		} else {
			return '<a href="?register=1">Register a new user</a>';
		}
	}
	
	private function getContent(LoginView $v, RegisterView $rv) {
		if(isset($_GET['register'])) {
			return $rv->response();
		} else {
			return $v->response();
		}
	}
}
