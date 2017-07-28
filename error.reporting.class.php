<?php

class errorHandler {
	protected static $errors=array();
	public static $instance=0;

	public static function add($type,$message,$file,$line){
		self::$errors[]=array("type"=>$type,"message"=>$message,"file"=>$file,"line"=>$line);
		if(self::$instance!=0){
			global $layout;
			$layout->errorOut(end(self::$errors));
		}
	}

	public static function get($type="all"){
		if(count(self::$errors)==0) return array();
		if($type!="all"){
			$buffer=array();
			foreach (self::$errors as $error) {
				if($error["type"]==$type)
					$buffer[]=$error;
				}	
			return $buffer;
		} else {
			return self::$errors;
		}
		return array();
	}
}

function errorHandler($errno,$message,$file,$line) {
  if (error_reporting()==0) {
    return false;
  }
  errorHandler::add($errno,$message,$file,$line);
  return true;
}
error_reporting(E_ALL & ~E_NOTICE);
set_error_handler('errorHandler');

?>