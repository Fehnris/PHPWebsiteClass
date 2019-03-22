<?php
	
	//include website configuration file that sets up various website features.
	//Features such as database details are made available through the reading of json files that store database details.
	include("../config_Files/config_Header.php");
	include("../config_Files/Product.php");

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
		$searchArray = array("fieldName"=>"id", "fieldValue"=>$_REQUEST['id'], "fieldType"=>"i");
		$some_product = new Product($searchArray, $dbAttributes, $website->mysqlConn());
		if($some_product->detailsValid) {
			//output some details from $some_product->details[<Variable Name>];
		}
		else {
			for($c = 0; $c < count($some_product->error); $c++) {
				foreach($ssome_product->error[$c] as $field => $value) {
					echo $field.": ".$value;
				}
				echo "<br />";
			}
		}
	}
	else {
		echo $website->mysqlError();
	}
	
?>
	
</body>
</html>
