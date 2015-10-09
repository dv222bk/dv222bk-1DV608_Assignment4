<?php
//INCLUDE THE FILES NEEDED...
require_once('model/User.php');
require_once('model/UserDAL.php');

require_once('controller/LoginController.php');
require_once('controller/RegisterController.php');

require_once('view/LoginView.php');
require_once('view/RegisterView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');

//CREATE OBJECTS OF THE VIEWS
$userDAL = new \model\UserDAL();
$user = new \model\User($userDAL);
$v = new \view\LoginView($user);
$dtv = new \view\DateTimeView();
$lv = new \view\LayoutView();
$rv = new \view\RegisterView();
$con = new \controller\LoginController($v, $user);
$rcon = new \controller\RegisterController($rv, $userDAL);

if(!$v->getLogoutAttempt()) {
	$con->loginUser();
} else {
	$con->logoutUser();
}

if($rv->getRegisterAttempt()) {
	$rcon->registerUser();
}

if(isset($_GET['register'])) {
	$lv->render($con->isUserLoggedIn(), $rv, $dtv);
} else {
	$lv->render($con->isUserLoggedIn(), $v, $dtv);
}

