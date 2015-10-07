<?php

namespace view;

class LayoutView {
  
	public function render($isLoggedIn, $v, DateTimeView $dtv) {
		echo '<!DOCTYPE html>
		  <html>
		    <head>
		      <meta charset="utf-8">
		      <title>Login Example</title>
		    </head>
		    <body>
		      <h1>Assignment 4</h1>
		      ' . $this->getMenu() . '
		      ' . $this->renderIsLoggedIn($isLoggedIn) . '
		      
		      <div class="container">
		          ' . $this->getContent($v) . '
		          
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
	
	private function getContent($v) {
		if(get_class($v) == 'view\LoginView' || get_class($v) == 'view\RegisterView') {
			return $v->response();
		}
	}
}
