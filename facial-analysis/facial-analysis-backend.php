<?php

/*==========================================================================
 * Copyright (c) 2020 Berkine Design
 * =========================================================================
 * 
 *
 * AMAZON REKOGNITION - DEEP LEARNING POWERED IMAGE RECOGNITION SERVICE
 * 
 * 
 * =========================================================================
 * Original Filename: facial-analysis-backend.php
 * Last Modified Date: 15 March 2020
 *
 */
	
	# Include main Rekognition Class
	require '../Rekognition.php';

	# Ajax Return Array
	$output = [];

	# Create Rekognition Class Object
	$rekognition = new Rekognition;


	# Check Uploaded Image File
	if (isset($_FILES['file'])) {
		
		
		# File Specifications (Name)
		$file_name = $_FILES['file']['name'];				  


		# Call s3Upload Method to Upload Image
		$s3_status = $rekognition->s3Upload($_FILES['file']);


		# If successfully uploaded to S3 Bucket
		if ($s3_status === 200) {

			# Call facialAnalysis Method for the Upload Image
			$result = $rekognition->facialAnalysis($file_name);

			# Check Status of facialAnalysis
			$result_status = $result['@metadata']['statusCode'];

			# If successful return JSON of Output
			if ($result_status === 200) {
				
				json_output($result['FaceDetails']);				

			} else {

				$output['message'] = 'There was an error during image processing.';
				$output['status'] = 'error';
				json_output($output);

			}
			

		} else {

			$output['message'] = 'There was an error during file upload.';
			$output['status'] = 'error';
			json_output($output);

		}  		    	


	}

	
	# Process JSON response for AJAX request
	function json_output($data) {
    	
    	header('Content-Type: application/json');

    	die(json_encode($data, JSON_UNESCAPED_SLASHES));

	}



	# If Demo Image is Used
	if(isset($_POST['demo-image'])){

		# Read demo image
		$demo_image = filter_input(INPUT_POST, 'demo-image', FILTER_SANITIZE_STRING);		
		$fp_image = fopen($demo_image, 'r');
	    $image = fread($fp_image, filesize($demo_image));
	    fclose($fp_image);


	    # Call imageAnalysisLocalFileSystem() and pass bytes of the image
		$result = $rekognition->imageAnalysisLocalFileSystem($image);


		# Check Status of facialAnalysis
		$result_status = $result['@metadata']['statusCode'];


		# If successful return JSON of Output
		if ($result_status === 200) {
			
			json_output($result['FaceDetails']);			

		} else {

			$output['message'] = 'There was an error during image processing.';
			$output['status'] = 'error';
			json_output($output);

		}

	}

