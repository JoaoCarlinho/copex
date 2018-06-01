<?php
$conn_error = 'Could not connect';

function copexConnect(){
    
$host = 'theho014.mysql.guardedhost.com';
$user = 'theho014_JoaoTC';
$password = 'g3t$MONEY16uk';
$database='theho014_copex';

	try{
		$db = new PDO('mysql:host='.$host.';dbname='.$database, $user, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") );
		// set the PDO error mode to exception
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		return $db;
	}
	catch(PDOException $e)
	{
		echo "Connection failed: " . $e->getMessage();
	}
}

?>