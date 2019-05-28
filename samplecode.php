<?php
session_start();
//create the jobid
function bulk_job_create($instance_url, $access_token) {
	$url = "$instance_url/services/data/v42.0/jobs/ingest";
	
	$content = json_encode(array("object" => 'Account', "externalIdFieldName" => "Id","contentType"=>'CSV',"operation" => 'update',"lineEnding" => "LF"));
	
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_HTTPHEADER,
			array("Authorization: OAuth $access_token",
					"Content-type: application/json; charset=UTF-8",
					"Accept: text/csv"
			));
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
	
			curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

			$response = curl_exec($curl);
			
			$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			$job = json_decode($status,true);
			
			
			if ( $status != 204 ) {
				
			}
			

			
			curl_close($curl);
}
//upload the csv to the api
function bulkupload($instance_url,$access_token,$jobId=null){
	$url = "$instance_url/services/data/v42.0/jobs/ingest/7509D0000008dznQAA/batches";
	
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_HTTPHEADER,
			array("Authorization: OAuth $access_token",
					"Content-type: text/csv",
					"Accept: application/json"
					
			));
	
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
	
	
	$file = "test-3.csv";
	
		
		curl_setopt($curl, CURLOPT_POSTFIELDS, $file);
		curl_exec($curl);
		
		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		
		
		if ( $status != 204 ) {
			die("Error: call to URL $url failed with status $status, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
		}
		
		echo "HTTP status $status updating account<br/><br/>";
		
		curl_close($curl);
}
//check the jobid status
function check_job_status($instance_url,$access_token)
{
	$url = "$instance_url/services/data/v42.0/jobs/ingest/7509D0000008dznQAA/";
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_HTTPHEADER,
			array("Authorization: OAuth $access_token",
					"Content-type: application/json; charset=UTF-8",
					"Accept: application/json"
					
			));
	
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
	curl_exec($curl);
	
	$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	

	
	echo "HTTP status $status updating account<br/><br/>";
	
	curl_close($curl);
}
function bulkjobcomplete($instance_url,$access_token){
	
	$url = "$instance_url/services/data/v42.0/jobs/ingest/7509D0000008dznQAA/";
	$content = json_encode(array("state" => 'UploadComplete'));
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_HTTPHEADER,
			array("Authorization: OAuth $access_token",
					"Content-type: application/json; charset=UTF-8",
					"Accept: application/json"
					
			));
	
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
	curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
	curl_exec($curl);
	
	$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	
	echo "HTTP status $status updating account<br/><br/>";
	
	curl_close($curl);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>REST/OAuth Example</title>
    </head>
    <body>
    <tt>
            <?php
            $access_token = $_SESSION['access_token'];
            $instance_url = $_SESSION['instance_url'];

            if (!isset($access_token) || $access_token == "") {
                die("Error - access token missing from session!");
            }

            if (!isset($instance_url) || $instance_url == "") {
                die("Error - instance URL missing from session!");
            }
            //create the job id
           bulk_job_create($instance_url,$access_token);
           //upload csv
            bulkupload($instance_url,$access_token,'');
            //mark the status as upload completed
          bulkjobcomplete($instance_url,$access_token);
          //check status of the job
          check_job_status($instance_url,$access_token);
          
            
    ?>

    </tt>
    </body>
</html>
