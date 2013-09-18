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

$student_info = array(your array data);

// creating object of SimpleXMLElement
$xml_git = new SimpleXMLElement("<?xml version=\"1.0\"?><object></object>");

// function call to convert array to xml
array_to_xml($obj,$xml_git);

//saving generated xml file
//$xml_git->asXML('/xml');


// function defination to convert array to xml
function array_to_xml($obj, &$xml_git) {
    foreach($obj as $key => $value) {
        if(is_array($value)) {
            if(!is_numeric($key)){
                $subnode = $xml_git->addChild("$key");
                array_to_xml($value, $subnode);
            }
            else{
                $subnode = $xml_git->addChild("item$key");
                array_to_xml($value, $subnode);
            }
        }
        else {
            $xml_git->addChild("$key","$value");
        }
    }


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


// send email
mail($emailto, $subject, $body, "From: <$emailfrom>");

$url = "http://seccareccia.crisply.com/api/"
$xml_git;

$post_data = array('xml' => $xml_git);
$stream_options = array(
    'http' => array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/xml; charset=UTF-8' . "\r\n",
        'content' =>  http_build_query($post_data)));

$context  = stream_context_create($stream_options);
$response = file_get_contents($url, null, $context);

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