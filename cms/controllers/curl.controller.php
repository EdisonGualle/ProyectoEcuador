<?php

class CurlController
{

	static public function request($url, $method, $fields)
	{

		$baseUrl = $_ENV["API_BASE_URL"] ?? null;
		$token = $_ENV["API_AUTH_TOKEN"] ?? null;

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $baseUrl . '/' . ltrim($url, '/'),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => $method,
			CURLOPT_POSTFIELDS => $fields,
			CURLOPT_HTTPHEADER => array(
				'Authorization: ' . $token
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return json_decode($response);
	}
}
