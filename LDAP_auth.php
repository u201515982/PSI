<?php
function authenticate($user, $password){
	if(empty($user) || empty($password)) return false;
	
	$conn = ldap_connect("ldap://192.168.0.41:389");
	$username = $user."@psi.gob";
	ldap_set_option($conn,LDAP_OPT_PROTOCOL_VERSION,3);
	ldap_set_option($conn, LDAP_OPT_REFERRALS, 0);

	if($bind = @ldap_bind($conn,$username,$password)) {
		$filter = "samaccountname=$user";
		$sr = ldap_search($conn,"DC=PSI,DC=GOB",$filter);
		$entries = ldap_get_entries($conn, $sr);
		if($entries[0]['description'][0]==""){
			return false;
		}
		$dni = $entries[0]['description'][0];
		$firstname = explode(' ',trim($entries[0]['givenname'][0]));
		$lastname = explode(' ',trim($entries[0]['sn'][0]));
		$name = $firstname[0]." ".$lastname[0];
		ldap_unbind($conn);

		$access = 1;
		$_SESSION['user'] = $user;
		$_SESSION['name'] = $name;
		$_SESSION['dni'] = $dni;
		$_SESSION['access'] = $access;

		return true;
	} else {
		return false;
	}
}
?>