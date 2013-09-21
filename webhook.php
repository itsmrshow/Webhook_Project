<?php
//Strips all slashes in an array
function stripslashes_deep($value){
    $value = is_array($value) ?
                array_map('stripslashes_deep', $value) :
                stripslashes($value);
    return $value;
}

$result = stripslashes_deep($_REQUEST['payload']);

//email data
$emailfrom = "robert.seccareccia.jr@gmail.com"; //Sender, replace with your email
$emailto = "robert.seccareccia.jr@gmail.com"; //Recipient, replace with your email
$subject = "Github Test Webhook"; //Email Subject

$obj = json_decode($result, true);


// prepare email body text
$body = "Contact Form Submissions"; //Title
$body .= "\n";  //Nothing but new line
$body .= "Before: ". $obj['before']; //Print Name
$body .= "\n";
$body .= "After: ". $obj['after']; //Print Email
$body .= "\n";
$body .= "Ref: ". $obj['ref']; //Print Message
$body .= "\n";
$body .= "ID: ". ($obj['commits'][0]['id']); //Print Message
$body .= "\n";
$body .= "Message: ". ($obj['commits'][0]['message']); //Print Message
$body .= "\n";
$body .= "timestamp: ". ($obj['commits'][0]['timestamp']); //Print Message
$body .= "\n";
$body .= "URL: ". ($obj['commits'][0]['url']); //Print Message
$body .= "\n";

$doc  = new DOMDocument('1.0', 'utf-8');

$doc->formatOutput = false;

$root = $doc->createElementNS('http://crisply.com/api/v1', 'activity-item');
$doc->appendChild($root);

$title = $doc->createElement('guid');
$title = $root->appendChild($title);

$text = $doc->createTextNode('github-activity-' .($obj['commits'][0]['timestamp']));
$text = $title->appendChild($text);

$title = $doc->createElement('text');
$title = $root->appendChild($title);

$text = $doc->createTextNode($obj['commits'][0]['message']);
$text = $title->appendChild($text);

$body .= $doc->saveXML() . "\n";

$tuCurl = curl_init(); 
curl_setopt($tuCurl, CURLOPT_URL, "http://requestb.in/13w5d631"); 
curl_setopt($tuCurl, CURLOPT_PORT , 443); 
curl_setopt($tuCurl, CURLOPT_VERBOSE, 0); 
curl_setopt($tuCurl, CURLOPT_HEADER, 0); 
curl_setopt($tuCurl, CURLOPT_SSLVERSION, 3); 
curl_setopt($tuCurl, CURLOPT_SSLCERT, getcwd() . "/client.pem"); 
curl_setopt($tuCurl, CURLOPT_SSLKEY, getcwd() . "/keyout.pem"); 
curl_setopt($tuCurl, CURLOPT_CAINFO, getcwd() . "/ca.pem"); 
curl_setopt($tuCurl, CURLOPT_POST, 1); 
curl_setopt($tuCurl, CURLOPT_SSL_VERIFYPEER, 1); 
curl_setopt($tuCurl, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($tuCurl, CURLOPT_POSTFIELDS, $doc->saveXML()); 
curl_setopt($tuCurl, CURLOPT_HTTPHEADER, array("Content-Type: application/xml","charset: utf-8", "X-Crisply-Authentication: m1Qy3WWSV1IbISTe4EBD")); 

$tuData = curl_exec($tuCurl); 
if(!curl_errno($tuCurl)){ 
  $info = curl_getinfo($tuCurl); 
  echo 'Took ' . $info['total_time'] . ' seconds to send a request to ' . $info['url']; 
} else { 
  echo 'Curl error: ' . curl_error($tuCurl); 
} 

curl_close($tuCurl); 
echo $tuData; 

mail($emailto, $subject, $body, "From: <$emailfrom>");
?>
<html>
<head>
</head>
<body bgcolor="blue">
<h1>
Webhook handler from Github to Crisply
</h1>
</body>
</html>