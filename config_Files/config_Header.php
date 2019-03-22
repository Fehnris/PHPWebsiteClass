<?php

   include("Website.php");

   //Website Class takes Array of config file names.  Optional, can be empty array for defaults.
   $settingsFiles = array();
   $configFiles = array("currentDir"=>dirname(__FILE__)."/",
						"settingsDir"=>"/home/proacl5/WSSettings",
	   					"webroot"=>"..",
					    "settingsFiles"=>$settingsFiles);
   $website = new Website($configFiles);

?>