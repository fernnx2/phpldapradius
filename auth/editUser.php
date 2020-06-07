<?php
include "../header.php";
include "sessioncookie.php";

if(isset($_POST['uid']) && isset($_POST['nombre']) && isset($_POST['apellido'])){
	if(isset($_SESSION['user'])){
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
                $info['AstAccountRealmedPassword']="12345";
                //echo "cn=admin,".$_SESSION['config']['baseLdap'];
                //print_r($info);
         ldap_modify($ldapconn,"uid=".$_POST['uid'].",".$_SESSION['config']['baseSearch'],$info) or die("Could not update user!" . ldap_error($ldapconn));

}

ldap_close($ldapconn);
header("location:dashboard.php");
}
}



if(!isset($_COOKIE['user'])){
	unset($_SESSION['user']);
	unset($_SESSION['password']);
	unset($_SESSION['config']);

}

if(isset($_SESSION['user'])){

 	$ldapconn = ldap_connect($_SESSION['config']['urlLdap']) or die("Could not connect to LDAP server.");
	 $search = ldap_search($ldapconn, $_SESSION['config']['baseSearch'],"uid=".$_GET['uid']) or die("Error in search query: " . ldap_error($ldapconn));

        //validamos busqueda
       if ($search) {
            $data = ldap_get_entries($ldapconn, $search);
        }

}
else {
	sessioncookie();
	//header("location:dashboard.php");
}
?>

<form action="editUser.php" method="post">
<div class="ed-container s-1-3">
	<div class="ed-container">
		<div class="ed-item">
		<h5>Edit User</h5>
		</div>
	</div>
	<div class="ed-container">
		<div class="ed-item"><label>Uid</label></div>
		<div class="ed-item"><input type="text" readonly="readonly" name="uid" required="true" value=<?php echo $data[0]['uid'][0]?> /></div>
	</div>
	<div class="ed-container">
		 <div class="ed-item"><label>Nombre</label></div>
                <div class="ed-item"><input type="text"  name="nombre" required="true" value=<?php echo $data[0]['cn'][0]?> /></div>
	</div>
	 <div class="ed-container">
                 <div class="ed-item"><label>apellido</label></div>
                <div class="ed-item"><input type="text"  name="apellido" required="true" value=<?php echo $data[0]['sn'][0] ?> /></div>
        </div>
	 <div class="ed-container">
                <div class="ed-item"><button class="btn waves-effect waves-light s-100" type="submit" name="action">Guardar Cambios</button></div>
        </div>
	  <div class="ed-container">
                <div class="ed-item"><a class="btn waves-effect waves-light s-100 red" href="dashboard.php">Cancelar</a></div>
        </div>

</div>
</form>


<center><a class="btn waves-effect waves-light s-100 orange" href="../auth/logout.php">Logout</a></center>

<?php
include("../footer.php");
?>
