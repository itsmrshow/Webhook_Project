<?php

//Strips all slashes in an array
function stripslashes_deep($value){
    $value = is_array($value) ?
                array_map('stripslashes_deep', $value) :
                stripslashes($value);
    return $value;
}
$result = stripslashes_deep($_REQUEST['rawRequest']);

//email data
$emailfrom = "robert.seccareccia.jr@gmail.com"; //Sender, replace with your email
$emailto = "robert.seccareccia.jr@gmail.com"; //Recipient, replace with your email
$subject = "JotForm Test Webhook"; //Email Subject

$obj = json_decode($result, true);
 
// prepare email body text
$body = "Contact Form Submissions"; //Title
$body .= "\n";  //Nothing but new line
$body .= "Name: ". $obj['name']; //Print Name
$body .= "\n";
$body .= "Config: ". $obj['config']; //Print Email
$body .= "\n";
$body .= "Events: ". $obj['events']; //Print Message
$body .= "\n";
$body .= "Active: ". $obj['active']; //Print Message
$body .= "\n";
$body .= "URL: ". $obj['url']; //Print Message
$body .= "\n";
$body .= "Content type: ". $obj['content_type']; //Print Message
$body .= "\n";
$body .= "Secret: ". $obj['secret']; //Print Message

// send email
mail($emailto, $subject, $body, "From: <$emailfrom>");
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