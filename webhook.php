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
 
?>