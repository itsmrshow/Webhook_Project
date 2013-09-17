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
$body .= "Name: ". $obj['q1_name']; //Print Name
$body .= "\n";
$body .= "Email: ". $obj['q3_email3']; //Print Email
$body .= "\n";
$body .= "Message: ". $obj['q4_message']; //Print Message

// send email
mail($emailto, $subject, $body, "From: <$emailfrom>");
?>