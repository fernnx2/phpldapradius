<?php
session_start();
require "cert/cert.php";
$cwd = getcwd();
$certificate = generateCertificate($cwd,$_POST['uid']);
if(isset($_SESSION['user']) && $certificate== true){
	 $ldapconn = ldap_connect($_SESSION['config']['urlLdapWrite']) or die("Could not connect to LDAP server.");
	ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);

	if($ldapconn){
	$ldapbind = ldap_bind($ldapconn, $_SESSION['config']['usernameConsultaLdap'], $_SESSION['config']['passwordConsultaLdap']) or die("Error trying to bind: ".ldap_error($ldapconn));
		$info=[];
		//$info['dn']="uid=".$_POST['uid'].",".$_SESSION['config']['baseSearch'];
		//$info['changetype']="add";
		$info['objectClass'][0]="AsteriskSIPUser";
		$info['objectClass'][1]="inetOrgPerson";
		$info['cn']=$_POST['nombre'];
		$info['sn']=$_POST['apellido'];
		$info['uid']=$_POST['uid'];
		$info['AstAccountCallerID']=$_POST['nombre'] . " " . $_POST['apellido'];
		$info['AstAccountContext']="paneschucos-default";
		$info['AstAccountHost']="dynamic";
		$info['AstAccountType']="friend";
		$info['AstAccountRealmedPassword']=$_POST['password'];
		$info['userCertificate;binary'] = file_get_contents($cwd."/cert/certs/".$_POST['uid']."/clientcert.der");
		//echo "cn=admin,".$_SESSION['config']['baseLdap'];
		//print_r($info);
	 ldap_add($ldapconn,"uid=".$_POST['uid'].",".$_SESSION['config']['baseSearch'],$info) or die("Could not add new entry!" . ldap_error($ldapconn));

}
	ldap_close($ldapconn);
	header("location:dashboard.php");
}
else{
	header("location:dashboard.php");
}

?>

