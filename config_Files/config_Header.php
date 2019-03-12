<?php

   include("Website.php");

   //Website Class takes Array of settings file names.
   //Optional, can be empty array for defaults.
   $settingsFiles = array();
   //An array to store configuration settings.
   //currentDir - Must be the directory storing this file.
   //settingsDir - The directory that settings files are stored.
   //              If left blank settings files will be searched
   //              for in the Settings subfolder to this one.
   //webroot - Must be the website root directory in relation to
   //          this directory.
   $configuration = array("currentDir"=>dirname(__FILE__)."/",
						"settingsDir"=>"",
	   					"webroot"=>"..",
					    "settingsFiles"=>$settingsFiles);
   $website = new Website($configuration);

?>
