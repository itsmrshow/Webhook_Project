<?php

//Strips all slashes in an array
function stripslashes_deep($value){
    $value = is_array($value) ?
                array_map('stripslashes_deep', $value) :
                stripslashes($value);
    return $value;
}
$result = stripslashes_deep($_REQUEST['payload']);
$result=preg_replace('/.+?({.+}).+/','$1',$result);
//email data
$emailfrom = "robert.seccareccia.jr@gmail.com"; //Sender, replace with your email
$emailto = "robert.seccareccia.jr@gmail.com"; //Recipient, replace with your email
$subject = "Github Test Webhook"; //Email Subject

$obj = json_decode($result, true);


// prepare email body text
$body = "Contact Form Submissions"; //Title
$body .= "\n";  //Nothing but new line
$body .= "Before: ". $result['before']; //Print Name
$body .= "\n";
$body .= "After: ". $obj['after']; //Print Email
$body .= "\n";
$body .= "Ref: ". $obj['ref']; //Print Message
$body .= "\n";
$body .= "Id: ". $obj['commits']["id"]; //Print Message
$body .= "\n";
$body .= "Message: ". $obj['commits']; //Print Message
$body .= "\n";
$body .= "Timestamp: ". $obj['commits']; //Print Message
$body .= "\n";
$body .= "URL: ". $result; //Print Message
$body .= "\n";
$body .= "Added: ". $obj['commits'][$id]; //Print Message
$body .= "\n";
$body .= "Removed: ". $obj['commits']; //Print Message
$body .= "\n";
$body .= "Modified: ". $obj['commits']; //Print Message
$body .= "\n";
$body .= "Name: ". $obj['commits']; //Print Message


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