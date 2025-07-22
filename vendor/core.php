<?php
class xiigroup{
	public function __construct($user=null, $pass=null){
		$this->url = "https://api.xiigroup.co.za";
		$this->payload = [];
		$this->authentication = "$user:$pass";
	}
	
	public function __destruct(){
		$this->url = null;
		$this->payload = [];
		$this->authentication = "null:null";
	}
  
	public function authenticate($username, $password){
		$this->authentication = "$username:$password";
	}
	
	public function payload($payload){
		$this->payload = $payload;
	}
	
	public function execute(){
		$auth_header = [
			"Content-Type"=>"application/x-www-form-urlencoded; charset=utf-8",
			"Content-Length"=>strlen(http_build_query($this->payload))
		];
		
		if(function_exists('curl_init')){
			$options = [
			CURLOPT_URL => $this->url,
			CURLOPT_HEADER => false,
			CURLOPT_TIMEOUT => 60,
			CURLOPT_CONNECTTIMEOUT => 60,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_VERBOSE => false,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_HTTPHEADER => $auth_header,
			CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
			CURLOPT_USERPWD => $this->authentication
			];
		
			if(is_array($this->payload) && !empty($this->payload) && isset($this->payload)){
				$options[CURLOPT_POSTFIELDS] = $this->payload;
			}
			$ch = curl_init();
			curl_setopt_array($ch, $options);
			$contents = curl_exec($ch);
			$contents = json_decode($contents, true);
			$header_size = curl_getinfo($ch);
			if($errno = curl_errno($ch)) {
				$contents = curl_strerror($errno);
			}
			curl_close($ch);
		}
		return $contents;
	}
}
?>
