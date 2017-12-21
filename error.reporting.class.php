<?php

/**
 * Class for database connection
 * @category  Database Access
 * @package   php-errorReporting
 * @author    Jonas Embach
 * @copyright Copyright (c) 2017
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/jembach/php-errorReporting
 * @version   1.0-master
 */
class errorHandler {

	protected static $errors=array();	//Holds all error
	protected static $callback=NULL;	//The callback paramters
	protected static $errorCount=0;		//The error counter for the errors array
	
	/**
	 * Sets the output function.
	 * @param      callback  $callback  The callback
	 */
	public static function setOutputFunction($callback){
		self::$callback=$callback;
	}

	/**
	 * prints an error if an callback is set
	 * @param      integer  $key    The key of the error in the $errors array
	 */
	public static function out($key){
		if(self::$callback!==NULL)
			call_user_func(self::$callback,self::$errors[$key]);
	}

	/**
	 * Sets the error hanlder.
	 * @param      integer  $level  The level
	 */
	public static function setErrorHanlder($level){
		error_reporting($level);
	}

	/**
	 * Adding an error message generated by the compiler
	 * @param      string   $type     The type
	 * @param      string   $message  The message
	 * @param      string   $file     The file
	 * @param      integer  $line     The line
	 */
	public static function addError($type,$message,$file,$line){
		self::$errors[self::$errorCount]=array("type"=>$type,"message"=>$message,"file"=>$file,"line"=>$line);
		self::out(self::$errorCount);
		self::$errorCount++;
	}

	/**
	 * Adding an error message generated by the compiler
	 * @param      string   $type     The type
	 * @param      string   $message  The message
	 * @param      string   $file     The file
	 * @param      integer  $line     The line
	 */
	public static function addException(Exception $ex){
		self::$errors[self::$errorCount]=array("type"=>E_USER_ERROR,"message"=>$ex->getMessage(),"file"=>$ex->getFile(),"line"=>$ex->getLine(),"trace"=>$ex->getTrace());
		self::out(self::$errorCount);
		self::$errorCount++;
	}

	/**
	 * returns a specified list of errors
	 * @param      string  $type   The type
	 * @return     array   		   the error informations
	 */
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

//sets the exception and error handler on the error reporting class
set_error_handler(array("errorHandler","addError"));
set_exception_handler(array("errorHandler","addException"));


?>