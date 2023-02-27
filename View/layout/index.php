<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
</head>
<body>
    <pre> this is the index page</pre>
	<?php
	$dn = "CN=Administrator,CN=Users,DC=zube,DC=com";
	$password = "GwgwmhYUTWmdRT7fAccIM@=nRiXX9*fk";
	$dc = ldap_connect("ldap://zube.com", 636);
	$ldap = ldap_bind($dc, $dn, $password);
	print_r($ldap);
	?>
</body>
</html>
