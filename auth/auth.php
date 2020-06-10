<?php

require("session.php");
session_start();
function auth($user,$password,$type){

$config['version'] = '3.0';
$config['urlLdap'] = 'ldap://URLREAD';
$config['urlLdapWrite'] = 'ldap://URLWRITE';
$config['baseLdap'] = 'dc=paneschucos,dc=occ,dc=ues,dc=edu,dc=sv';
$config['usernameConsultaLdap'] = 'cn='.$user.',dc=paneschucos,dc=occ,dc=ues,dc=edu,dc=sv';
$config['passwordConsultaLdap'] = $password;
$config['baseSearch'] = 'ou=usuarios,dc=paneschucos,dc=occ,dc=ues,dc=edu,dc=sv';

$ldapconn = ldap_connect($config['urlLdap']) or die("Could not connect to LDAP server.");
ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
 
if ($ldapconn) {
    // realizando la autenticacion
    $ldapbind = ldap_bind($ldapconn, $config['usernameConsultaLdap'], $config['passwordConsultaLdap']) or 
die("<h2>Error authenticate  :".ldap_error($ldapconn)."</h2>"."</br> <a class='btn waves-effect waves-light s-50' href='../index.php'>Back to Login</a>");

    // verificacion del enlace
    if ($ldapbind) {
	if($type=='cookie'){
	sessionStartCookie($user,$password,$config);
	}
	else{
	sessionStart($user,$password,$config);
	}
    }
    ldap_close($ldapconn);

	header("location:dashboard.php");
}
}


?>
