<?php

/**
 * Clase cliente del Api2 de Flow
 * @Filename: FlowApi.php
 * @version: 2.0
 * @Author: flow.cl
 * @Email: csepulveda@tuxpan.com
 * @Date: 28-04-2017 11:32
 * @Last Modified by: Carlos Sepulveda
 * @Last Modified time: 28-04-2017 11:32
 */

class FlowApiwp
{
	private $apiKey;
	private $endpoint;
	private $secretKey;

	public function __construct($apiKey, $secretKey, $endpoint){
		$this->apiKey = $apiKey;
		$this->secretKey = $secretKey;
		$this->endpoint = $endpoint;
	}
	public function setApiKey($value){
		$this->apiKey = $value;
	}
	public function setSecretKey($value){
		$this->secretKey = $value;
		
	}
	public function setEndpoint($value){
		$this->endpoint = $value;
		
	}	
	/**
	 * Funcion que invoca un servicio del Api de Flow
	 * @param string $service Nombre del servicio a ser invocado
	 * @param array $params datos a ser enviados
	 * @param string $method metodo http a utilizar
	 * @return string en formato JSON
	 */
	public function send( $service, $params, $method = "GET") {
		$method = strtoupper($method);
		$url =  $this->endpoint . "/" . $service;
		$params = array("apiKey" =>  $this->apiKey) + $params;
		$toSign = $this->getPack($params, $method);
		
		if(!function_exists("hash_hmac")) {
			throw new Exception("function hash_hmac not exist", 1);
		}
		$sign = hash_hmac('sha256', $toSign ,  $this->secretKey);
		if($method == "GET") {
			$response = $this->httpGet($url, $toSign, $sign);
		} else {
			$response = $this->httpPost($url, $toSign, $sign);
		}
		if(empty($response["output"]) && $response["info"]["http_code"] != 200) {
			throw new Exception("Unexpected error occurred. HTTP_CODE: " .$response["info"]["http_code"] , $response["info"]["http_code"]);
		}
		$body = json_decode($response["output"], true);
		if($response["info"]["http_code"] != 200) {
			throw new Exception($body["message"], $body["code"]);
		}
		return $body;
	}
	
	/**
	 * Funcion que empaqueta los datos para ser firmados
	 * @param array $params datos a ser empaquetados
	 * @param string $method metodo http a utilizar
	 */
	private function getPack($params, $method) {
		$keys = array_keys($params);
		sort($keys);
		$toSign = "";
		foreach ($keys as $key) {
			if($method == "GET") {
				$toSign .= "&" . rawurlencode($key) . "=" . rawurlencode($params[$key]);
			} else {
				$toSign .= "&" . $key . "=" . $params[$key];
			}
		}
		return substr($toSign, 1);
	}
	
	/**
	 * Funcion que hace el llamado via http GET
	 * @param string $url url a invocar
	 * @param array $data datos a enviar
	 * @param string $sign firma de los datos
	 * @return string en formato JSON 
	 */
	private function httpGet($url, $data, $sign) {
		$url = $url . "?" . $data . "&s=" . $sign;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$output = curl_exec($ch);
		if($output === false) {
			$error = curl_error($ch);
			throw new Exception($error, 1);
		}
		$info = curl_getinfo($ch);
		curl_close($ch);
		return array("output" =>$output, "info" => $info);
	}
	
	/**
	 * Funcion que hace el llamado via http POST
	 * @param string $url url a invocar	 * @param array $data datos a enviar
	 * @param string $sign firma de los datos
	 * @return string en formato JSON 
	 */
	private function httpPost($url, $data, $sign ) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data . "&s=" . $sign);
		$output = curl_exec($ch);
		if($output === false) {
			$error = curl_error($ch);
			throw new Exception($error, 1);
		}
		$info = curl_getinfo($ch);
		curl_close($ch);
		return array("output" =>$output, "info" => $info);
	}
	
}
