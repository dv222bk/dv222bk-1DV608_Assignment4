<?php
//INCLUDE THE FILES NEEDED...
require_once('model/User.php');
require_once('model/NewUser.php');

require_once('controller/LoginController.php');
require_once('controller/RegisterController.php');

require_once('view/LoginView.php');
require_once('view/RegisterView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');

//CREATE OBJECTS OF THE VIEWS
$user = new \model\User();
$newUser = new \model\NewUser();
$v = new \view\LoginView($user);
$dtv = new \view\DateTimeView();
$lv = new \view\LayoutView();
$rv = new \view\RegisterView($newUser);
$con = new \controller\LoginController($v, $user);
$rcon = new \controller\RegisterController($rv, $newUser);

if(!$v->getLogoutAttempt()) {
	$con->loginUser();
} else {
	$con->logoutUser();
}

$lv->render($v->isUserLoggedIn(), $v, $dtv, $rv);

