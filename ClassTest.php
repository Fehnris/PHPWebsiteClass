<?php
	
	//include website configuration file that sets up various website features.
	//Features such as database details are made available through the reading of json files that store database details.
	include("../config_Files/config_Header.php");

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
	
<?php
	
	if($website->hasMysql()) {
	$db = mysqli_connect($website->mysqlHost(), $website->mysqlUser(), $website->mysqlPass(), $website->mysqlDB());

	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
	}
	else {
		printf("Connection Success!");
	}
	}
	
?>
	
</body>
</html>