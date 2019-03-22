<?php
	
	//include website configuration file that sets up various website features.
	//Features such as database details are made available through the reading of json files that store database details.
	include("../config_Files/config_Header.php");
	include("../config_Files/Speaker.php");

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
		$id = array("fieldName"=>"id", "fieldValue"=>$_REQUEST['id'], "fieldType"=>"i");
		$speaker = new Speaker($id, $dbAttributes, $website->mysqlConn());
		echo "Connection Success! Model Searched: ".$id['fieldValue']."<br />";
		if($speaker->detailsValid) {
			echo "Range: ".$speaker->details['RangeID']."<br />";
			echo "Speaker Name: ".$speaker->details['SpeakerName']."<br />";
			echo "Speaker Summary: ".$speaker->details['SpeakerBlob']."<br />";
			echo "Speaker Server Path: ".$speaker->details['SpeakerServerPath']."<br />";
		}
		else {
			for($c = 0; $c < count($speaker->error); $c++) {
				foreach($speaker->error[$c] as $field => $value) {
					echo $field.": ".$value;
				}
				echo "<br />";
			}
		}
		$speaker->load_finishes($priceQuery, $priceParams);
	}
	else {
		echo $website->mysqlError();
	}
	
?>
	
</body>
</html>