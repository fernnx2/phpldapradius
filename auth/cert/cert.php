<?php

function generateCertificate($cwd,$useruid){
$dn= array(
    "countryName"=>"US",
    "stateOrProvinceName" => "Santa Ana",
    "localityName" => "Santa Ana",
    "organizationName" => "Panes Chucos",
    "organizationalUnitName" => "Panes chucos Team",
    "commonName" => "panes chucos",
    "emailAddress" => "paneschucos@occ.ues.edu.sv"
);

$config = array(
        'config' => $cwd.'/cert/openssl.cnf',
	'encrypt_key' => false,
        'private_key_type' => OPENSSL_KEYTYPE_RSA,
        'digest_alg' => 'sha1',
        'x509_extensions' => 'v3_ca',
	'private_key_bits' => 4096
);

// Generar una nueva pareja de clave privada (y pública)
$privkey = openssl_pkey_new($config);

// Generar una petición de firma de certificado
$csr = openssl_csr_new($dn, $privkey);

// punto hasta que su AC satisfaga su petición.
// Esto crea un certificado autofirmado que es válido por 365 días
$sscert = openssl_csr_sign($csr, null, $privkey, 365, $config);

openssl_csr_export($csr, $csrout); // and var_dump($csrout);
openssl_x509_export($sscert, $certout); //and var_dump($certout);
openssl_pkey_export($privkey, $pkeyout, "mypassword"); //and var_dump($pkeyout);
mkdir($cwd."/cert/certs/".$useruid);
if(openssl_x509_export_to_file($sscert, $cwd."/cert/certs/".$useruid."/clientcert.pem",FALSE)){
	if(openssl_pkey_export_to_file($privkey, $cwd."/cert/certs/".$useruid."/clientkey.key", FALSE)){
	shell_exec("openssl x509 -outform der -in ".$cwd."/cert/certs/".$useruid."/clientcert.pem -out ".$cwd."/cert/certs/".$useruid."/clientcert.der");
	return true;
	}
	else{

	return false;}
}
else{
return false;
}
// Mostrar cualquier error que ocurra
//while (($e = openssl_error_string()) !== false) {
//    echo $e . "\n";
//}
}


?>
