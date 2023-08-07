<?php
function perform_remote_request($url, $http_header = array())
{
	$curl = curl_init($url);

	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	if(!empty($http_header))
	{
		curl_setopt($curl, CURLOPT_HTTPHEADER, $http_header);
	}

	$response = curl_exec($curl);

	curl_close($curl);

	return $response;
}