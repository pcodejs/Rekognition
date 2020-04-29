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
 * Original Filename: face-comparison-backend.php
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
	if (isset($_FILES['file']) && isset($_FILES['file-comparison'])) {
		
		
		# File Specifications (Name)
		$file_name = $_FILES['file']['name'];				  
		$file_name_comparison = $_FILES['file-comparison']['name'];				  

		

		# Call s3Upload Method to Upload Image
		$s3_status = $rekognition->s3Upload($_FILES['file']);
		$s3_status_comparison = $rekognition->s3Upload($_FILES['file-comparison']);


		# If successfully uploaded to S3 Bucket
		if (($s3_status === 200) && ($s3_status_comparison === 200)) {
		
					# )Call facialAnalysis Method for the Upload Image
			$result = $rekognition->faceComparison($file_name, $file_name_comparison);

			# Check Status of facialAnalysis
			$result_status = $result['@metadata']['statusCode'];

			# If successful return JSON of Output
			if ($result_status === 200) {
				
				json_output($result['FaceMatches']);				

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


	# If Demo Images are Used
	if(isset($_POST['demo-image']) && isset($_POST['demo-image-comparison']) ){

		# Read demo images
		$demo_image = filter_input(INPUT_POST, 'demo-image', FILTER_SANITIZE_STRING);	
		$fp_image = fopen($demo_image, 'r');
	    $image = fread($fp_image, filesize($demo_image));
	    fclose($fp_image);

	    $demo_image_comparison = filter_input(INPUT_POST, 'demo-image-comparison', FILTER_SANITIZE_STRING);	
		$fp_image_comparison = fopen($demo_image_comparison, 'r');
	    $image_comparison = fread($fp_image_comparison, filesize($demo_image_comparison));
	    fclose($fp_image_comparison);


	    # Call faceComparisonLocalFileSystem() and pass bytes of the images
		$result = $rekognition->faceComparisonLocalFileSystem($image, $image_comparison);


		# Check Status of facialAnalysis
		$result_status = $result['@metadata']['statusCode'];


		# If successful return JSON of Output
		if ($result_status === 200) {
			
			json_output($result['FaceMatches']);			

		} else {

			$output['message'] = 'There was an error during image processing.';
			$output['status'] = 'error';
			json_output($output);

		}

	}

?>