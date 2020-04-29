<?php
	
	/*=============================================================================================
	* Amazon Rekognition & Amazon S3 User credentials (Access key & Secret Access key)
	* For enhanced security reasons you can use an AWS credentials file to specify your credentials
	* This is a special, INI-formatted file stored under your HOME directory (~/.aws/credentials)
	*
	* [credential profile name]
	*   aws_access_key_id = ANOTHER_AWS_ACCESS_KEY_ID
	*	aws_secret_access_key = ANOTHER_AWS_SECRET_ACCESS_KE
	*==============================================================================================*/

	return [
		'rekognition' => [
		/* 'accessKey' => '', */					# IAM User Access key (in case if you want to hard code directly )
		/* 'secretAccessKey' => '', */				# IAM User Secret Access key (in case if you want to hard code directly)
			'profile' => 'default',					# AWS credentials profile to specify your credentials (*****UPDATE PROFILE NAME ACCORDINGLY*****)
			'region' => 'XX-XXXX-X',				# AWS Region Code - Select your region code as needed: https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/using-regions-availability-zones.html (*****UPDATE REGION NAME ACCORDINGLY*****)
			'version' => 'latest',					# Latest version of the client
			'bucketName' => 'XXXXXXXXXX'			# Amazon S3 Bucket Name (must be unique) *****UPDATE BUCKET NAME ACCORDINGLY*****
		]
	];	

?>