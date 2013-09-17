<?php
 
        // Turn off error reporting
        error_reporting(0);
 
        try
        {
                // Decode the payload json string
                $payload = json_decode($_REQUEST['payload']);
				echo $payload;
        }
        catch(Exception $e)
        {
                exit;
        }

	// Retrieve the request's body and parse it as JSON
	$body = @file_get_contents('php://input');
	$event_json = json_decode($body);

	// Do something with $event_json
?>
<html>
<head>
<h1>
hello world
</h1>
</head>
<body>
<p><?php echo $event_json; ?>
</p>
</body>
</html>