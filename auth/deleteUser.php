<?php
session_start();

if(isset($_SESSION['user'])){
	 $ldapconn = ldap_connect($_SESSION['config']['urlLdapWrite']) or die("Could not connect to LDAP server.");
	ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
	
	if($ldapconn){
	$ldapbind = ldap_bind($ldapconn, $_SESSION['config']['usernameConsultaLdap'], $_SESSION['config']['passwordConsultaLdap']) or die("Error trying to bind: ".ldap_error($ldapconn));
	ldap_delete($ldapconn,"uid=".$_GET['uid'].",".$_SESSION['config']['baseSearch']) or die("Could not delete user!" . ldap_error($ldapconn));

}
	ldap_close($ldapconn);
	header("location:dashboard.php");
}
else{
	header("location:dashboard.php");
}

?>

