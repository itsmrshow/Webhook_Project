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

$doc->formatOutput = true;

$root = $doc->createElementNS('http://crisply.com/api/v1', 'activity-item');
$doc->appendChild($root);

$title = $doc->createElement('guid');
$title = $root->appendChild($title);

$text = $doc->createTextNode('github-activity-' .($obj['commits'][0]['timestamp']));
$text = $title->appendChild($text);

$title = $doc->createElement('text');
$title = $root->appendChild($title);

$text = $doc->createTextNode($obj['commits'][0]['timestamp']);
$text = $title->appendChild($text);

$body .= $doc->saveXML() . "\n";
mail($emailto, $subject, $body, "From: <$emailfrom>");

$username = "m1Qy3WWSV1IbISTe4EBD";
$password = "";

$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, 'https://seccareccia.crisply.com/api/activity_items.xml'); 
curl_setopt($ch, CURLOPT_USERPWD, $username.':'.$password);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_TIMEOUT, 4); 
curl_setopt($ch, CURLOPT_POSTFIELDS, $doc->saveXML()); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: close'));
 
//Execute the request and also time the transaction ( optional )
$start = array_sum(explode(' ', microtime()));
$result = curl_exec($ch); 
$stop = array_sum(explode(' ', microtime()));
$totalTime = $stop - $start;
 
//Check for errors ( again optional )
if ( curl_errno($ch) ) {
    $result = 'ERROR -> ' . curl_errno($ch) . ': ' . curl_error($ch);
} else {
    $returnCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    switch($returnCode){
        case 200:
            break;
        default:
            $result = 'HTTP ERROR -> ' . $returnCode;
            break;
    }
}
 
//Close the handle
curl_close($ch);
 
//Output the results and time
echo 'Total time for request: ' . $totalTime . "\n";
echo $result; 

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