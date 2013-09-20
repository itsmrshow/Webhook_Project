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

$url = "http://seccareccia.crisply.com/api/activity_item.xml";
$username = 'm1Qy3WWSV1IbISTe4EBD';
$password = "";

$credentials = 'm1Qy3WWSV1IbISTe4EBD@seccareccia.crisply.com';
$header_array = array('Expect' => '',
                'From' => 'User A');
$ssl_array = array('version' => SSL_VERSION_SSLv3);
$options = array(headers => $header_array,
                httpauth => $credentials,
                httpauthtype => HTTP_AUTH_BASIC,
                protocol => HTTP_VERSION_1_1,
                ssl => $ssl_array);
                
//create the httprequest object                
$httpRequest_OBJ = new httpRequest($url, HTTP_METH_POST, $options);
//add the content type
$httpRequest_OBJ->setContentType = 'Content-Type: application/xml';
//add the raw post data
$httpRequest_OBJ->setRawPostData ($doc->saveXML());
//send the http request
$result = $httpRequest_OBJ->send();
//print out the result
echo "<pre>"; print_r($result); echo "</pre>";
 
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