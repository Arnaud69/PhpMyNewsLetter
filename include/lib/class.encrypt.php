<?php
class Encrypt{
	public function __construct() {
		$this->ciphering = 'AES-128-CBC';
		$this->options = 0;
		$this->iv = '12345678910126821';
		$this->encryptionKey = '1213141516';
	}
	public function encrypt($text) {
		$encryption = openssl_encrypt($text,$this->ciphering,$this->encryptionKey,$this->options,$this->iv);
		//$encryption = strtr($encryption,array('+' => '.' , '=' => '-' , '/' => '~'));
		$encryption = bin2hex($encryption);
		return $encryption;
	}
	public function decrypt($encrypt) {
		//$encrypt = strtr($encrypt,array('.' => '+' , '-' => '=' , '~'=>'/'));
		$encrypt = hex2bin($encrypt);
		$decrypt = openssl_decrypt($encrypt,$this->ciphering,$this->encryptionKey,$this->options,$this->iv);
		return $decrypt;
	}
}
