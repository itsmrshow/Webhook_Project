<?php
//Created By:Robert Seccareccia
//September 21 2013
//Github to Crisply Webhook Handler


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
//decode payload from github
$obj = json_decode($result, true);
//create new xml document 
$doc  = new DOMDocument('1.0', 'utf-8');
$doc->formatOutput = false;
//create header with root elements
$root = $doc->createElementNS('http://crisply.com/api/v1', 'activity-item');
$doc->appendChild($root);
//create guid element
$title = $doc->createElement('guid');
$title = $root->appendChild($title);
// fill element with activity and timestamp
$text = $doc->createTextNode('github-activity-' .($obj['commits'][0]['timestamp']));
$text = $title->appendChild($text);
//create text element 
$title = $doc->createElement('text');
$title = $root->appendChild($title);
//fill text element with message from commit
$text = $doc->createTextNode($obj['commits'][0]['message']);
$text = $title->appendChild($text);

// class using curl 
class cURL { 
// universal variables for use inside class
var $headers; 
var $user_agent; 
var $compression; 
var $cookie_file; 
var $proxy; 
//function to set up curl properties 
function cURL($cookies=TRUE,$cookie='cookies.txt',$compression='gzip',$proxy='') { 
//headers that include the x-crisply-authentication to allow crisply api access
$this->headers[] = 'Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg'; 
$this->headers[] = 'Connection: Keep-Alive'; 
$this->headers[] = 'Content-type: application/xml; charset=UTF-8'; 
$this->headers[] = 'X-Crisply-Authentication: m1Qy3WWSV1IbISTe4EBD';
$this->user_agent = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0)'; 
$this->compression=$compression; 
$this->proxy=$proxy; 
$this->cookies=$cookies; 
if ($this->cookies == TRUE) $this->cookie($cookie); 
} 
//function to create cookies for web browser session
function cookie($cookie_file) { 
if (file_exists($cookie_file)) { 
$this->cookie_file=$cookie_file; 
} else { 
fopen($cookie_file,'w') or $this->error('The cookie file could not be opened. Make sure this directory has the correct permissions'); 
$this->cookie_file=$cookie_file; 
fclose($this->cookie_file); 
} 
} 
//function to post my xml file to website
function post($url,$data) { 
$process = curl_init($url); 
//establish headers
curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers); 
curl_setopt($process, CURLOPT_HEADER, 1); 
curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent); 
//if statement for cookies 
if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file); 
if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file); 
curl_setopt($process, CURLOPT_ENCODING , $this->compression); 
curl_setopt($process, CURLOPT_TIMEOUT, 30); 
//if statment if using a proxy
if ($this->proxy) curl_setopt($process, CURLOPT_PROXY, $this->proxy); 
//postfields for passing data to website
curl_setopt($process, CURLOPT_POSTFIELDS, $data); 
curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1); 
curl_setopt($process, CURLOPT_POST, 1); 
$return = curl_exec($process); 
//once done close and return 
curl_close($process); 
return $return; 
} 
//function to deliver errors if needed
function error($error) { 
echo "<center><div style='width:500px;border: 3px solid #FFEEFF; padding: 3px; background-color: #FFDDFF;font-family: verdana; font-size: 10px'><b>cURL Error</b><br>$error</div></center>"; 
die; 
} 
} 
$cc = new cURL(); 
$cc->post('http://seccareccia.crisply.com/api/activity_items.xml',$doc->saveXML()); 

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