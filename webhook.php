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

/**
$doc = new DOMDocument('1.0');
// we want a nice output
$doc->formatOutput = true;

$root = $doc->createElement('activity-item xmlns="http://crisply.com/api/v1"');
$root = $doc->appendChild($root);

$title = $doc->createElement('Date');
$title = $root->appendChild($title);

$text = $doc->createTextNode($obj['commits'][0]['timestamp']);
$text = $title->appendChild($text);

$title = $doc->createElement('Message');
$title = $root->appendChild($title);

$text = $doc->createTextNode($obj['commits'][0]['message']);
$text = $title->appendChild($text);

$body .= $doc->saveXML('activity-item.xml') . "\n";

//add comment
// send email


$additionalHeaders = "charset=UTF-8";
$username = "m1Qy3WWSV1IbISTe4EBD";
$password = "";
$host = "http://seccareccia.crisply.com/api/"

$process = curl_init($host);
curl_setopt($process, CURLOPT_HTTPHEADER, array('Content-Type: application/xml', $additionalHeaders));
curl_setopt($process, CURLOPT_HEADER, 1);
curl_setopt($process, CURLOPT_USERPWD, $username . ":" . $password);
curl_setopt($process, CURLOPT_TIMEOUT, 30);
curl_setopt($process, CURLOPT_POST, 1);
curl_setopt($process, CURLOPT_POSTFIELDS, $hold);
curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
$return = curl_exec($process);
curl_close($process);
*/
mail($emailto, $subject, $body, "From: <$emailfrom>");

$hold = toXml($obj);

$body .=  $hold . "\n";

	public static function toXml($data, $rootNodeName = 'data', $xml=null)
	{
		// turn off compatibility mode as simple xml throws a wobbly if you don't.
		if (ini_get('zend.ze1_compatibility_mode') == 1)
		{
			ini_set ('zend.ze1_compatibility_mode', 0);
		}
 
		if ($xml == null)
		{
			$xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><$rootNodeName />");
		}
 
		// loop through the data passed in.
		foreach($data as $key => $value)
		{
			// no numeric keys in our xml please!
			if (is_numeric($key))
			{
				// make string key...
				$key = "unknownNode_". (string) $key;
			}
 
			// replace anything not alpha numeric
			$key = preg_replace('/[^a-z]/i', '', $key);
 
			// if there is another array found recrusively call this function
			if (is_array($value))
			{
				$node = $xml->addChild($key);
				// recrusive call.
				ArrayToXML::toXml($value, $rootNodeName, $node);
			}
			else 
			{
				// add single node.
                                $value = htmlentities($value);
				$xml->addChild($key,$value);
			}
 
		}
		// pass back as string. or simple xml object if you want!
		return $xml->asXML();
	}

?>
<html>
<head>
</head>
<body bgcolor="black">
<h1>
Webhook from Github to Crisply
</h1>
</body>
</html>