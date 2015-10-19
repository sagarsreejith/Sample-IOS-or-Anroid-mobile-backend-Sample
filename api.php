<?php
    
	/*
	Auther Name : Sagar Sreejith
	Version : V1:2:3
	*/
	
	require_once("Rest.inc.php");
	
	class API extends REST {
	
		public $data = "";
		
		const DB_SERVER = "localhost";
		const DB_USER = "YOR USER NAME";
		const DB_PASSWORD = "db PASSWORD";
		const DB = "YOUR db NAME";
		
		private $db = NULL;
	
		public function __construct(){
			parent::__construct();				// Init parent contructor
			$this->dbConnect();					// Initiate Database connection
		}
		
		/*
		 *  Database connection 
		*/
		private function dbConnect(){
			$this->db = mysql_connect(self::DB_SERVER,self::DB_USER,self::DB_PASSWORD);
			if($this->db)
				mysql_select_db(self::DB,$this->db);
		}
		
		/*
		 * Public method for access api.
		 * This method dynmically call the method based on the query string
		 *
		 */
		public function processApi(){
			$func = strtolower(trim(str_replace("/","",$_REQUEST['rquest'])));
			if((int)method_exists($this,$func) > 0)
				$this->$func();
			else
				$this->response('',404);				// If the method not exist with in this class, response would be "Page not found".
		}
		
		/* 
		 *	Simple login API
		 *  Login must be POST method
		 *  email : <USER EMAIL>
		 *  pwd : <USER PASSWORD>
		 */
		private function sample_request(){
			$iphone_data = file_get_contents("php://input");
			$json = json_decode($iphone_data, true);
			if(!empty($json['user_name'])){ // We are only passing user name using form post(data format is JSON)
				$success = array('status' => "Success", "msg" => "User exist");
				$this->response($this->json($success),200);
				// If success everythig is good send header as "OK" and user details
			}
			else{				
				// If invalid inputs "Bad Request" status message and reason
				$error = array('status' => "Failed", "msg" => "Invalid Email address or Password");
				$this->response($this->json($error), 400);
			}
		}
		
		/*
		 *	Encode array into JSON
		*/
		
		private function json($data){
			if(is_array($data)){
				return json_encode($data);
			}
		}
	}
	
	// Initiiate Library
	
	$api = new API;
	$api->processApi();
	
			
?>