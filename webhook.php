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

$URL = "http://seccareccia.crisply.com/api/activity_items.xml";
//$URL = "http://requestb.in/135vbk21";
<?
HTTPpost("seccareccia.crisply.com","/api/activity_items.xml",$doc->saveXML(),"m1Qy3WWSV1IbISTe4EBD","","Mozilla/4.0 
(compatible; MSIE 5.5; Windows NT 5.0)");
?>
function HTTPost($host, $path, $data_to_send,$user,$pass,$agent) {
$fp = fsockopen($host,80, &$err_num, &$err_msg, 10);
if (!$fp) {
echo "$err_msg ($err_num)<br>\n";
} else {
$auth = $user.":".$pass ; 
$string=base64_encode($auth);
echo $string;
fputs($fp, "POST $path HTTP/1.1\r\n");
fputs($fp, "Authorization: Basic ".$string."\r\n");
fputs($fp, "User-Agent: ".$agent."\n");
fputs($fp, "Host: $host\n");
fputs($fp, "Content-type: application/x-www-form-urlencoded\n");
fputs($fp, "Content-length: ".strlen($data_to_send)."\n");
fputs($fp, "Connection: close\n\n");
fputs($fp, $data_to_send);
for ($i = 1; $i < 10; $i++){$reply = fgets($fp, 256);}
fclose($fp);
}
}

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