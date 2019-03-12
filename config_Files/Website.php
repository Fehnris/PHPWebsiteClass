<?php

class Website {
	
	private $features;
	private $mysqlDB;
	private $postgresqlDB;
	private $webroot;
	private $configDir;
	private $settingsDir;
	private $confObjects;
	
	public function __construct($config) {
		$this->webroot = $this->getRoot($config["currentDir"], $config["webroot"]);
		$this->configDir = $config["currentDir"];
		$this->setSettingsDir($config["settingsDir"]);
		$this->features = array();
		
		$this->setObjects($config["settingsFiles"]);
		$this->loadSettings();
	}
	
	private function setObjects($config) {
		$this->confObjects = array("mysqlConnSettings"=>array("fileName"=>"", "method"=>"readMysqlSettings"),
								   "postgresqlConnSettings"=>array("fileName"=>"", "method"=>"readPostgresqlSettings"));
		
		if(!isset($config["mysqlConnSettings"])) { $this->confObjects["mysqlConnSettings"]["fileName"] = "mysqlConnSettings.json"; }
		else { $this->confObjects["mysqlConnSettings"]["fileName"] = $config["mysqlConnSettings"]; }
		
		if(!isset($config["postgresqlConnSettings"])) { $this->confObjects["postgresqlConnSettings"]["fileName"] = "postgresqlConnSettings.json"; }
		else { $this->confObjects["postgresqlConnSettings"]["fileName"] = $config["postgresqlConnSettings"]; }
	}
	
	private function setSettingsDir($data) {
		if(isset($data)) { $this->settingsDir = $data."/"; }
		else { $this->settingsDir = $this->configDir."Settings/"; }
	}
	
	private function getRoot($currentDir, $rootOffset) {
		$root = "";
		$path = explode("/", $currentDir);
		$subdirs = explode("/", $rootOffset);
		$updirs = 0;
		for($s = 0; $s < count($subdirs); $s++) {
			if($subdirs[$s] == "..") { $updirs++; }
		}
		for($p = 0; $p < (count($path) - $updirs); $p++) {
			$root .= $path[$p]."/";
		}
		return $root;
	}
	
	private function loadSettings() {
		$files = scandir($this->settingsDir);
		foreach($this->confObjects as $key=>$confName) {
			foreach($files as $file) {
				if($file == $confName["fileName"]) {
					$data = $this->readFile($this->settingsDir.$file);
					call_user_func(array($this, $confName["method"]), $data);
				}
			}
		}
	}
	
	private function readMysqlSettings($data) {
		if($data == false) { $this->features["hasMysql"] = false; }
		else {
			$this->mysqlDB = array("Credentials"=>array("Username"=>$data["Username"],
														"Password"=>$data["Password"]),
								   "Host"=>array("Hostname"=>$data["Host"],
												 "Port"=>$data["Port"]),
								   "Database"=>$data["Database"]);
			$this->features["hasMysql"] = true;
		}
	}
	
	private function readPostgresqlSettings($data) {
		if($data == false) { $this->features["hasPostgresql"] = false; }
		else {
			$this->postgresqlDB = array("Credentials"=>array("Username"=>$data["Username"],
														"Password"=>$data["Password"]),
								   "Host"=>array("Hostname"=>$data["Host"],
												 "Port"=>$data["Port"]),
								   "Database"=>$data["Database"]);
			$this->features["hasPostgresql"] = true;
		}
	}
	
	private function readFile($fileName) {
		if ( file_exists($fileName) && ($fp = fopen($fileName, "r"))!==false ) {
			$json = json_decode(fread($fp, filesize($fileName)), TRUE);
			fclose($fp);
			return $json;    
		}
		else {
			return false; 
		}
	}
	
	//Public Methods
	
	public function hasMysql() {
		return $this->features["hasMysql"];
	}
	
	public function hasPostgresql() {
		return $this->features["hasPostgresql"];
	}
	
	public function mysqlUser() {
		if($this->features["hasMysql"]) {
			return $this->mysqlDB["Credentials"]["Username"];
		}
		else {
			return "not configured";
		}
	}
	
	public function mysqlPass() {
		if($this->features["hasMysql"]) {
			return $this->mysqlDB["Credentials"]["Password"];
		}
		else {
			return "not configured";
		}
	}
	
	public function mysqlHost() {
		if($this->features["hasMysql"]) {
			return $this->mysqlDB["Host"]["Hostname"];
		}
		else {
			return "not configured";
		}
	}
	
	public function mysqlPort() {
		if($this->features["hasMysql"]) {
			return $this->mysqlDB["Host"]["Port"];
		}
		else {
			return "not configured";
		}
	}
	
	public function mysqlDB() {
		if($this->features["hasMysql"]) {
			return $this->mysqlDB["Database"];
		}
		else {
			return "not configured";
		}
	}
	
	public function postgresqlUser() {
		if($this->features["hasPostgresql"]) {
			return $this->postgresqlDB["Credentials"]["Username"];
		}
		else {
			return "not configured";
		}
	}
	
	public function postgresqlPass() {
		if($this->features["hasPostgresql"]) {
			return $this->postgresqlDB["Credentials"]["Password"];
		}
		else {
			return "not configured";
		}
	}
	
	public function postgresqlHost() {
		if($this->features["hasPostgresql"]) {
			return $this->postgresqlDB["Host"]["Hostname"];
		}
		else {
			return "not configured";
		}
	}
	
	public function postgresqlPort() {
		if($this->features["hasPostgresql"]) {
			return $this->postgresqlDB["Host"]["Port"];
		}
		else {
			return "not configured";
		}
	}
	
	public function postgresqlDB() {
		if($this->features["hasPostgresql"]) {
			return $this->postgresqlDB["Database"];
		}
		else {
			return "not configured";
		}
	}
	
}

?>