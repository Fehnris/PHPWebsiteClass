<?php

	//Include the config_Header file which sets up the website class object.
	include("config_Files/config_Header.php");
	//The include file above creates a data object called $website.
	//Database credentials can be retrieved from this object as shown below.
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>PHP Website Class Example Usage</title>
</head>
<body>
<?php

	//Example usage for retrieving database credentials.

	if($website->hasMysql()) {
		echo "Website has a Mysql Database Configuration!<br />";
	  $mdb = mysqli_connect($website->mysqlHost(), $website->mysqlUser(), $website->mysqlPass(), $website->mysqlDB());
	  if (mysqli_connect_errno()) { printf("Connect failed: %s<br />", mysqli_connect_error()); }
		else { printf("Connection Success!<br />"); }
	}
	else { echo "Website does not have a Mysql Database Configuration :(<br />"; }
	if($website->hasPostgresql()) {
		echo "Website has a Postgresql Database Configuration!<br />";
	  $pdb = pg_connect("host=".$website->postgresqlHost()." port=".$website->postgresqlDB()." dbname=".$website->postgresqlUser()." user=lamb password=".$website->postgresqlPass());
	}
	else { echo "Website does not have a Postgresql Database Configuration :(<br />"; }

?>
</body>
</html>
