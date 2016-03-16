<?php
namespace Core\Drivers;

class Config {
	private static $instance = null;
	private static $dbtype = "mysql";
    private static $controller = "test";
	private static $content = "main.default";
	private static $database = array(
		"mysql" => array(
			"host" => "localhost",
			"user" => "root",
			"password" => "",
			"database" => "phprs"
		)
	);
	
	private function __construct() { }
	
	public function __clone() {
		trigger_error('Clone no se permite.', E_USER_ERROR);
	}
	
	static private $mail = array("method" => "mail", "data" => array());

	public static function getDBConfig() {
		$dbObj = new \stdClass();
		$dbObj->type = self::$dbtype;

		foreach (self::$database[self::$dbtype] as $key => $value) {
			$dbObj->$key = $value;
		}
		
		return $dbObj;
	}
	
	public static function getAutoloaders() {
		return self::$autoloaders;
	}
	
	public static function getMailConfig() {
		return self::$mail;
	}
	
	

    public static function defaultController() {
		return self::$controller;
	}
        
	public static function defaultContent() {
		return self::$content;
	}
        
    public static function setDefaultView($view = null) {
                if( !$view ) return false;
		self::$view = $view;
	}
	
	public static function getInstance() 
	{
		if (!isset(self::$instance)) 
		{ 
			$c = __CLASS__;
			self::$instance = new $c;
		}
		return self::$instance;
	} 
}
