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

//curl_setopt($tuCurl, CURLOPT_HTTPHEADER, array("Content-Type: application/xml","charset: utf-8", "X-Crisply-Authentication: m1Qy3WWSV1IbISTe4EBD")); 

class cURL { 
var $headers; 
var $user_agent; 
var $compression; 
var $cookie_file; 
var $proxy; 
function cURL($cookies=TRUE,$cookie='cookies.txt',$compression='gzip',$proxy='') { 
$this->headers[] = 'Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg'; 
$this->headers[] = 'Connection: Keep-Alive'; 
$this->headers[] = 'Content-type: application/xml;charset=UTF-8;X-Crisply-Authentication=m1Qy3WWSV1IbISTe4EBD'; 
$this->user_agent = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0)'; 
$this->compression=$compression; 
$this->proxy=$proxy; 
$this->cookies=$cookies; 
if ($this->cookies == TRUE) $this->cookie($cookie); 
} 
function cookie($cookie_file) { 
if (file_exists($cookie_file)) { 
$this->cookie_file=$cookie_file; 
} else { 
fopen($cookie_file,'w') or $this->error('The cookie file could not be opened. Make sure this directory has the correct permissions'); 
$this->cookie_file=$cookie_file; 
fclose($this->cookie_file); 
} 
} 
function get($url) { 
$process = curl_init($url); 
curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers); 
curl_setopt($process, CURLOPT_HEADER, 0); 
curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent); 
if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file); 
if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file); 
curl_setopt($process,CURLOPT_ENCODING , $this->compression); 
curl_setopt($process, CURLOPT_TIMEOUT, 30); 
if ($this->proxy) curl_setopt($process, CURLOPT_PROXY, $this->proxy); 
curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1); 
$return = curl_exec($process); 
curl_close($process); 
return $return; 
} 
function post($url,$data) { 
$process = curl_init($url); 
curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers); 
curl_setopt($process, CURLOPT_HEADER, 1); 
curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent); 
if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file); 
if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file); 
curl_setopt($process, CURLOPT_ENCODING , $this->compression); 
curl_setopt($process, CURLOPT_TIMEOUT, 30); 
if ($this->proxy) curl_setopt($process, CURLOPT_PROXY, $this->proxy); 
curl_setopt($process, CURLOPT_POSTFIELDS, $data); 
curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1); 
curl_setopt($process, CURLOPT_POST, 1); 
$return = curl_exec($process); 
curl_close($process); 
return $return; 
} 
function error($error) { 
echo "<center><div style='width:500px;border: 3px solid #FFEEFF; padding: 3px; background-color: #FFDDFF;font-family: verdana; font-size: 10px'><b>cURL Error</b><br>$error</div></center>"; 
die; 
} 
} 
$cc = new cURL(); 
//$cc->get('http://requestb.in/13w5d631'); 
$cc->post('http://requestb.in/13w5d631',$doc->saveXML()); 

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