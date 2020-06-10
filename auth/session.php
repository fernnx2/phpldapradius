<?php
//session_start()
function sessionStart($user,$password,$config){
	//session_start();
	$_SESSION['user']=$user;
	$_SESSION['password']=$password;
	$_SESSION['config']=$config;
	setcookie('user',$user,time()+60*60*60);
	setcookie('password',$password,time()+60*60);
	echo "Sesison iniciada";
	

}
function sessionStartCookie($user,$password,$config){
	//session_start();
	$_SESSION['user']=$user;
	$_SESSION['password']=$password;
	$_SESSION['config']=$config;
	echo "session iniciada por cookie";
}


//if($_POST['user']==$user && $_POST['password']==$password){
//	$_SESSION['login'] = $user;
//	setcookie('user', $_POST['user'], time()+30);
	//echo "Bienvenido usuario :  " . $_SESSION['login'] . " ---- IP 172.17.0.4 ----";
//	header("location:dashboard.php");
//}
//else{
//	header("location:index.php");
//}

?>

