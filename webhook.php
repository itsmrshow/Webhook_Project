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
$com = $obj['commits'];
$aut = $com['author'];
$repo = $obj['repository'];
$owner = $repo['owner'];
// prepare email body text
$body = "Contact Form Submissions"; //Title
$body .= "\n";  //Nothing but new line
$body .= "Before: ". $obj['before']; //Print Name
$body .= "\n";
$body .= "After: ". $obj['after']; //Print Email
$body .= "\n";
$body .= "Ref: ". $obj['ref']; //Print Message
$body .= "\n";
$body .= "Id: ". $com['id']; //Print Message
$body .= "\n";
$body .= "Message: ". $com['message']; //Print Message
$body .= "\n";
$body .= "Timestamp: ". $com['timestamp']; //Print Message
$body .= "\n";
$body .= "URL: ". $com['url']; //Print Message
$body .= "\n";
$body .= "Added: ". $com['added']; //Print Message


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