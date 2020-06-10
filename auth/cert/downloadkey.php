<?php
session_start();
$cwd=getcwd();
if(isset($_SESSION['user'])){
         $ldapconn = ldap_connect($_SESSION['config']['urlLdapWrite']) or die("Could not connect to LDAP server.");
        ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);

        if($ldapconn){
        $ldapbind = ldap_bind($ldapconn, $_SESSION['config']['usernameConsultaLdap'], $_SESSION['config']['passwordConsultaLdap']) or die("Error trying to bind: ".ldap_error($ldapconn));
       // $search = ldap_search($ldapconn,"uid=".$_GET['uid'].",".$_SESSION['config']['baseSearch']) or die("No se encuentra el usuario!!" . ldap_error($ldapconn));
        $search = ldap_search($ldapconn, $_SESSION['config']['baseSearch'],"uid=".$_GET['uid']) or die("Error in search query: " . ldap_error($ldapconn));
        $data = ldap_get_entries($ldapconn, $search);
        for ($i = 0; $i < count($data); $i++) {             
                if (isset($data[$i]['uid'][0])) {
                $downloadkey = $data[$i]['userpkcs12'][0];
                header("Content-type: text/plain");
                header("Content-Disposition: attachment; filename=clientkey.key");
                echo $downloadkey;
                }
}
}
        ldap_close($ldapconn);
      //  header("location:../dashboard.php");
}
else{
      //  header("location:../dashboard.php");
}

?>
