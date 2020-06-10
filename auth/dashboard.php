<?php
include "../header.php";
include "sessioncookie.php";

if(!isset($_COOKIE['user'])  || !isset($_COOKIE['password'])){
//unset($_COOKIE['user']);
//unset($_COOKIE['password']);
//setcookie("user",null,-1);
//setcookie('password',null,-1);
unset($_SESSION['user']);
unset($_SESSION['password']);
unset($_SESSION['config']);

}

if(isset($_SESSION['user']) && isset($_SESSION['password'])){
 	$ldapconn = ldap_connect($_SESSION['config']['urlLdap']) or die("Could not connect to LDAP server.");
	 $search = ldap_search($ldapconn, $_SESSION['config']['baseSearch'],"uid=*") or die("Error in search query: " . ldap_error($ldapconn));

        //validamos busqueda
       if ($search) {
            $data = ldap_get_entries($ldapconn, $search);
?>
	
	<div class="ed-container">
                <table>
                        <thead>
                                <tr>
					<th>UID</th>
					<th>Nombre CN</th>
					<th>Apellido SN</th>
					<th>Actions</th>
				</tr> 
                        </thead> 
                        <tbody>
<?php

 for ($i = 0; $i < count($data); $i++) {             
                if (isset($data[$i]['uid'][0])) {
		 echo '<tr><td>'.$data[$i]['uid'][0].
		'</td><td>'.$data[$i]['cn'][0].
		'</td><td>'.$data[$i]['sn'][0].
		'</td><td><a class="btn waves-effect waves-light blue" href='."editUser.php?uid=".$data[$i]["uid"][0].
                '>Edit</a>'.
		'<a class="btn waves-effect waves-light red" href='."deleteUser.php?uid=".$data[$i]["uid"][0].
		'>Delete</a>'.
		'<a class="btn waves-effect waves-light grey" href='."cert/certs/".$data[$i]["uid"][0]."/clientcert.pem".  
                ' download="clientcert.pem" >Descargar cert</a>'.
		'<a class="btn waves-effect waves-light black" href='."cert/certs/".$data[$i]["uid"][0]."/clientkey.key".
                ' download="clientkey.key">Descargar key</a></td></tr>';
                
}
            }

?>
                <tbody>
                </table>
                </div>


<?php

        }

}
else {
	sessioncookie();
	//header("location:dashboard.php");
}
//$certs = scandir('cert/certs/Fernando.Menjivar');
//print_r($certs);
?>
<form action="addUser.php" method="post">
<div class="ed-container s-1-3">
	<div class="ed-container"><div class="ed-item"><h5>Add New User</h5></div></div>	
	<div class="ed-container">
		<div class="ed-item"><label>Uid</label></div>
		<div class="ed-item"><input type="text" placeholder="uid" name="uid" required="true" /></div>
	</div>
	<div class="ed-container">
		 <div class="ed-item"><label>Nombre</label></div>
                <div class="ed-item"><input type="text" placeholder="nombre" name="nombre" required="true" /></div>
	</div>
	 <div class="ed-container">
                 <div class="ed-item"><label>apellido</label></div>
                <div class="ed-item"><input type="text" placeholder="apellido" name="apellido" required="true"/></div>
        </div>
	 <div class="ed-container">
                 <div class="ed-item"><label>password</label></div>
                <div class="ed-item"><input type="password" placeholder="password" name="password" required="true"/></div>
        </div>

	 <div class="ed-container">
                <div class="ed-item"><button class="btn waves-effect waves-light s-100" type="submit" name="action">Registrar</button></div>
        </div>
	
</div>
</form>


<center><a class="btn waves-effect waves-light s-100 orange" href="../auth/logout.php">Logout</a></center>

<?php
include("../footer.php");
?>
