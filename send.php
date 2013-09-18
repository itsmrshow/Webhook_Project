<?php

$dom = new DOMDocument();

$dom->load('activity-item.xml');

$additionalHeaders = "charset=UTF-8";
$username = "m1Qy3WWSV1IbISTe4EBD";
$password = "";
$host = "http://seccareccia.crisply.com/api/activity-item.xml"

$process = curl_init($host);
curl_setopt($process, CURLOPT_HTTPHEADER, array('Content-Type: application/xml', $additionalHeaders));
curl_setopt($process, CURLOPT_HEADER, 1);
curl_setopt($process, CURLOPT_USERPWD, $username . ":" . $password);
curl_setopt($process, CURLOPT_TIMEOUT, 30);
curl_setopt($process, CURLOPT_POST, 1);
curl_setopt($process, CURLOPT_POSTFIELDS, $dom);
curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
$return = curl_exec($process);
curl_close($process);


?>