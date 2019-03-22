<?php

class Website {
	
	private $features;
	private $mysqlDB;
	private $postgresqlDB;
	private $webroot;
	private $configDir;
	private $settingsDir;
	private $confObjects;
	protected static $mysqlConn;
	protected static $mysqlError;
	protected static $postgresqlConn;
	protected static $postgresqlError;
	
	public function __construct($config) {
		$this->webroot = $this->getRoot($config["currentDir"], $config["webroot"]);
		$this->configDir = $config["currentDir"];
		$this->setSettingsDir($config["settingsDir"]);
		$this->features = array();
		$this->loadSettings($this->setObjects($config["settingsFiles"]));
	}
	
	private function setObjects($c) {
		$cO = array("mysqlConnSettings"=>array("fileName"=>"", "method"=>"readMysqlSettings"),
					"postgresqlConnSettings"=>array("fileName"=>"", "method"=>"readPostgresqlSettings"));
		$cO["mysqlConnSettings"]["fileName"] = (!isset($c["mysqlConnSettings"])) ? "mysqlConnSettings.json" : $c["mysqlConnSettings"];
		$cO["postgresqlConnSettings"]["fileName"] = (!isset($c["postgresqlConnSettings"])) ? "postgresqlConnSettings.json" : $c["postgresqlConnSettings"];
		return $cO;
	}
	
	private function setSettingsDir($data) {
		$this->settingsDir = (isset($data)) ? $data."/" : $this->configDir."Settings/";
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
	
	private function loadSettings($cO) {
		$files = scandir($this->settingsDir);
		foreach($cO as $key=>$confName) {
			foreach($files as $file) {
				if($file == $confName["fileName"]) {
					$data = $this->readFile($this->settingsDir.$file);
					call_user_func(array($this, $confName["method"]), $data);
				}
			}
		}
	}
	
	private function readMysqlSettings($data) {
		$this->features["hasMysql"] = false;
		if($data === false) { self::$mysqlError = "Failed to retrieve connection settings"; }
		else {
			$c = 0;
			$error = array();
			foreach($data as $key => $value) {
				if(!isset($value) || $value == "") {
					$error[$c] = $key;
					$c++;
				}
			}
			if(count($error) > 0) {
				self::$mysqlError = "Missing connection settings: ".implode("/", $error);
			}
			else {
				$this->mysqlDB = array("Credentials"=>array("Username"=>$data["Username"],
															"Password"=>$data["Password"]),
									   "Host"=>array("Hostname"=>$data["Host"],
													 "Port"=>$data["Port"]),
									   "Database"=>$data["Database"]);
				$this->features["hasMysql"] = $this->createMysqlConn();
			}
		}
	}
	
	
	private function createMysqlConn() {
		$connEstablished = false;
		if(!isset(self::$mysqlConn)) {
			self::$mysqlConn = new mysqli($this->mysqlDB['Host']['Hostname'], 
											 $this->mysqlDB['Credentials']['Username'], 
											 $this->mysqlDB['Credentials']['Password'], 
											 $this->mysqlDB['Database']);
		}
		if(self::$mysqlConn === false) {
            self::$mysqlError = "Failed to connect to database";
        }
		else {
			//self::$mysqlConn->set_charset("utf8mb4");
			$connEstablished = true;
		}
		return $connEstablished;
	}
	
	public function mysqlConn() {
        return self::$mysqlConn;
    }
	
	public function mysqlError() {
        return self::$mysqlError;
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
}

?>