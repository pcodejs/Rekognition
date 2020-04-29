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
 * Original Filename: Rekognition.php
 * Last Modified Date: 15 March 2020
 *
 */
	
	# AWS PHP SDK - Required to run AWS APIs
	require_once 'vendor/autoload.php';


	# AWS Namespaces
	use Aws\S3\S3Client;   
	use Aws\Rekognition\RekognitionClient;
	use Aws\Exception\AwsException;
	use Aws\Exception\CredentialsException;
	use Aws\TranscribeService\Exception;



	class Rekognition {

		private $s3;					# Amazon S3 Client
		private $rekognition;			# Amazon Rekognition Client
		private $config;				# Parameters from config.php


		/**
		 * Rekognition Class Contructor 
		 * Set private variables: $s3; $rekognition; $config;
		 */
		public function __construct() {

			# Amazon Rekognition & Amazon S3 Parameters
			$config = require_once 'configs/config.php';

			$this->config = $config;


		   /*======================================================================== 
			*  Initialize Amazon Rekognition client and pass Rekognition parameters
			*========================================================================*/
			$rekognition = new Aws\Rekognition\RekognitionClient([
					'profile' => $config['rekognition']['profile'],
					'region'  => $config['rekognition']['region'],
					'version' => $config['rekognition']['version']
			]);

			$this->rekognition = $rekognition;


			/*========================================================================== 
			 * Initializing Amazon s3 client and pass S3 parameters 
			 * S3 Bucket must be in the same AWS Region where Amazon Rekognition is used
			 *==========================================================================*/
			$s3 = new Aws\S3\S3Client([
				'profile' => $config['rekognition']['profile'],
				'version' => $config['rekognition']['version'],		
				'region'  => $config['rekognition']['region']
			]);

			$this->s3 = $s3;

		}



		/**
		 * Upload Selected Image 
		 * @param  File $file - File to Upload
		 * @return int $s3_status - upload status (returns 200 is uploaded successfully)
		 */
		public function s3Upload($file) {

			# File Specifications (Name, Size, TempName, Extension)
			$file_name = $file['name'];				  
			$file_tmp_name = $file['tmp_name'];


			try {						
						
				# Upload to Amazon S3 Bucket
				$input_image = $this->s3 -> putObject([
					'Bucket' => $this->config['rekognition']['bucketName'],			# Bucket Name for storing uploaded audio files
					'Key' => "{$file_name}",				# S3 Object Name for the uploaded audio file
					'SourceFile' => $file_tmp_name,								# S3 Object Content
					'ServerSideEncryption' => 'AES256',							# Server Side Encryption (optional)
					'StorageClass' => 'STANDARD',  								# S3 Storage Type - Can be one of the follwoing: STANDARD|REDUCED_REDUNDANCY|GLACIER|STANDARD_IA|ONEZONE_IA|INTELLIGENT_TIERING|DEEP_ARCHIVE 
					'ACL' => 'public-read', 									# S3 Object Access Control List - Can be one of the following: private|public-read|public-read-write|authenticated-read|aws-exec-read|bucket-owner-read|bucket-owner-full-control
					'ContentDisposition' => 'attachment'						# Allows you to download the file without opening it in the browser
				]);


				# Status of the S3 upload (returns 200 if uploaded successfully)
				$s3_status = $input_image['@metadata']['statusCode'];

				return $s3_status;


			} catch (AwsException $e) {		

			    return $e->getMessage() . "\n";

			}

		}


		/*========================================================================== 
		 * IMAGES UPLOADED AND PROCESSED VIA AMAZON S3
		 *==========================================================================*/

		/**
		 * Recognize Famous Parameters and Emotions
		 * @param  string $file_name - s3 object key name
		 * @return JSON $result - list facial parameters (including Sex, Age)
		 */
		public function facialAnalysis($file_name) {

			try {

				$result = $this->rekognition->detectFaces([
						    'Attributes' => ['ALL'],			# Values can be ['DEFAULT'] or ['ALL']; Default returns facial attributes: BoundingBox, Confidence, Pose, Quality, and Landmarks;
						    'Image' => [ 						# Required - Image to Process
						        'S3Object' => [
						            'Bucket' => $this->config['rekognition']['bucketName'],
						            'Name' => "{$file_name}"
						        ],
						    ],
						]);

				return $result;

			} catch (InvalidS3ObjectException $e) { 
				return $e->getMessage() . "\n";
			} catch (InvalidParameterException $e) {
				return $e->getMessage() . "\n";
			} catch (ImageTooLargeException $e) {
				return $e->getMessage() . "\n";
			} catch (AccessDeniedException $e) {
				return $e->getMessage() . "\n";
			} catch (InvalidImageFormatException $e) {
				return $e->getMessage() . "\n";
			} catch (ThrottlingException $e) {
				return $e->getMessage() . "\n";
			} catch (InternalServerError $e) {
				return $e->getMessage() . "\n";
			}

		}



		/**
		 * Compare Faces in the Images
		 * @param  string $file_name_one - s3 object key name of face to search
		 * @param  string $file_name_two - s3 object key name of the image to search from
		 * @return JSON $result 
		 */
		public function faceComparison($file_name_one, $file_name_two) {

			try {

				$result = $this->rekognition->compareFaces([
					    	'SimilarityThreshold' => 60,
						    'SourceImage' => [ 
						        'S3Object' => [
						            'Bucket' => $this->config['rekognition']['bucketName'],
						            'Name' => "{$file_name_one}"
						        ]
						    ],
						    'TargetImage' => [ 
						        'S3Object' => [
						            'Bucket' => $this->config['rekognition']['bucketName'],
						            'Name' => "{$file_name_two}"
						        ]
						    ],
						]);

				return $result;

			} catch (InvalidS3ObjectException $e) { 
				return $e->getMessage() . "\n";
			} catch (InvalidParameterException $e) {
				return $e->getMessage() . "\n";
			} catch (ImageTooLargeException $e) {
				return $e->getMessage() . "\n";
			} catch (AccessDeniedException $e) {
				return $e->getMessage() . "\n";
			} catch (InvalidImageFormatException $e) {
				return $e->getMessage() . "\n";
			} catch (ThrottlingException $e) {
				return $e->getMessage() . "\n";
			} catch (InternalServerError $e) {
				return $e->getMessage() . "\n";
			}

		}



		/**
		 * Recognize Famous Celebrities
		 * @param  string $file_name - s3 object key name
		 * @return JSON $result - list celebrity details (Name, URL, Confidence score)
		 */
		public function celebrityRecognition($file_name) {

			try {

				$result = $this->rekognition->recognizeCelebrities([
						    'Image' => [ 
						        'S3Object' => [
						            'Bucket' => $this->config['rekognition']['bucketName'],
						            'Name' => "{$file_name}"
						        ],
						    ],
						]);

				return $result;

			} catch (InvalidS3ObjectException $e) { 
				return $e->getMessage() . "\n";
			} catch (InvalidParameterException $e) {
				return $e->getMessage() . "\n";
			} catch (ImageTooLargeException $e) {
				return $e->getMessage() . "\n";
			} catch (AccessDeniedException $e) {
				return $e->getMessage() . "\n";
			} catch (InvalidImageFormatException $e) {
				return $e->getMessage() . "\n";
			} catch (ThrottlingException $e) {
				return $e->getMessage() . "\n";
			} catch (InternalServerError $e) {
				return $e->getMessage() . "\n";
			}

		}



		/**
		 * Recognize Adult Content in the Image
		 * @param  string $file_name - s3 object key name
		 * @return JSON $result - list of adult content terms/subjects
		 */
		public function imageModeration($file_name) {

			try {

				$result = $this->rekognition->detectModerationLabels([
						    'Image' => [ 
						        'S3Object' => [
						            'Bucket' => $this->config['rekognition']['bucketName'],
						            'Name' => "{$file_name}"
						        ],
						    ],
						    'MinConfidence' => 60,
						]);

				return $result;

			} catch (InvalidS3ObjectException $e) { 
				return $e->getMessage() . "\n";
			} catch (InvalidParameterException $e) {
				return $e->getMessage() . "\n";
			} catch (ImageTooLargeException $e) {
				return $e->getMessage() . "\n";
			} catch (AccessDeniedException $e) {
				return $e->getMessage() . "\n";
			} catch (InvalidImageFormatException $e) {
				return $e->getMessage() . "\n";
			} catch (ThrottlingException $e) {
				return $e->getMessage() . "\n";
			} catch (InternalServerError $e) {
				return $e->getMessage() . "\n";
			}

		}



		/**
		 * Recognize Objects in the Image
		 * @param  string $file_name - s3 object key name
		 * @return JSON $result - list of objects in the image
		 */
		public function objectDetection($file_name) {

			try {

			   $result = $this->rekognition->detectLabels([
				          'Image' => [ 
				              'S3Object' => [
				                  'Bucket' => $this->config['rekognition']['bucketName'],
				                  'Name' => "{$file_name}"
				              ],
				          ],
				          'MaxLabels' => 20,
				          'MinConfidence' => 70,
						]);

			   return $result;

			} catch (InvalidS3ObjectException $e) { 
				return $e->getMessage() . "\n";
			} catch (InvalidParameterException $e) {
				return $e->getMessage() . "\n";
			} catch (ImageTooLargeException $e) {
				return $e->getMessage() . "\n";
			} catch (AccessDeniedException $e) {
				return $e->getMessage() . "\n";
			} catch (InvalidImageFormatException $e) {
				return $e->getMessage() . "\n";
			} catch (ThrottlingException $e) {
				return $e->getMessage() . "\n";
			} catch (InternalServerError $e) {
				return $e->getMessage() . "\n";
			}

		}



		/**
		 * Recognize Text in the Image
		 * @param  string $file_name - s3 object key name
		 * @return JSON $result - list of text in the image
		 */
		public function textInImage($file_name) {

			try {

				$result = $this->rekognition->detectText([
						    'Image' => [ 
						        'S3Object' => [
						            'Bucket' => $this->config['rekognition']['bucketName'],
						            'Name' => "{$file_name}"
						        ],
						    ],
						]);	

				return $result;

			} catch (InvalidS3ObjectException $e) { 
				return $e->getMessage() . "\n";
			} catch (InvalidParameterException $e) {
				return $e->getMessage() . "\n";
			} catch (ImageTooLargeException $e) {
				return $e->getMessage() . "\n";
			} catch (AccessDeniedException $e) {
				return $e->getMessage() . "\n";
			} catch (InvalidImageFormatException $e) {
				return $e->getMessage() . "\n";
			} catch (ThrottlingException $e) {
				return $e->getMessage() . "\n";
			} catch (InternalServerError $e) {
				return $e->getMessage() . "\n";
			}

		}



		/*========================================================================== 
		 * IMAGES UPLOADED AND PROCESSED DIRECTLY AS BYTES
		 *==========================================================================*/

		/**
		 * Compare Faces in 2 images
		 * @param  string $file_name_one; $file_name_two - passed as image bytes
		 * @return JSON $result - face match
		 */
		public function faceComparisonLocalFileSystem($file_name_one, $file_name_two) {

			try {

				$result = $this->rekognition->compareFaces([
					    	'SimilarityThreshold' => 60,
						    'SourceImage' => [ 
						         'Bytes' => "{$file_name_one}"
						    ],
						    'TargetImage' => [ 
						        'Bytes' => "{$file_name_two}"
						    ],
						]);

				return $result;

			} catch (InvalidS3ObjectException $e) { 
				return $e->getMessage() . "\n";
			} catch (InvalidParameterException $e) {
				return $e->getMessage() . "\n";
			} catch (ImageTooLargeException $e) {
				return $e->getMessage() . "\n";
			} catch (AccessDeniedException $e) {
				return $e->getMessage() . "\n";
			} catch (InvalidImageFormatException $e) {
				return $e->getMessage() . "\n";
			} catch (ThrottlingException $e) {
				return $e->getMessage() . "\n";
			} catch (InternalServerError $e) {
				return $e->getMessage() . "\n";
			}

		}


		/**
		 * Analyse Image
		 * @param  string $image - passed as image bytes
		 * @return JSON $result - recognize oblects in the image
		 */
		public function imageAnalysisLocalFileSystem($image){
			
			try {

				$result = $this->rekognition->detectFaces([
					    'Attributes' => ['ALL'],
					    'Image' => [ 
					        'Bytes' => "{$image}"				        
					    	]
						]);

				return $result;

			} catch (InvalidS3ObjectException $e) { 
				return $e->getMessage() . "\n";
			} catch (InvalidParameterException $e) {
				return $e->getMessage() . "\n";
			} catch (ImageTooLargeException $e) {
				return $e->getMessage() . "\n";
			} catch (AccessDeniedException $e) {
				return $e->getMessage() . "\n";
			} catch (InvalidImageFormatException $e) {
				return $e->getMessage() . "\n";
			} catch (ThrottlingException $e) {
				return $e->getMessage() . "\n";
			} catch (InternalServerError $e) {
				return $e->getMessage() . "\n";
			}
		}


		/**
		 * Find Text in the image
		 * @param  string $image - passed as image bytes
		 * @return JSON $result - text in the image
		 */
		public function textInImageLocalFileSystem($image) {

			try {

				$result = $this->rekognition->detectText([
						    'Image' => [ 
						        'Bytes' => "{$image}"
						    ]
						]);	

				return $result;

			} catch (InvalidS3ObjectException $e) { 
				return $e->getMessage() . "\n";
			} catch (InvalidParameterException $e) {
				return $e->getMessage() . "\n";
			} catch (ImageTooLargeException $e) {
				return $e->getMessage() . "\n";
			} catch (AccessDeniedException $e) {
				return $e->getMessage() . "\n";
			} catch (InvalidImageFormatException $e) {
				return $e->getMessage() . "\n";
			} catch (ThrottlingException $e) {
				return $e->getMessage() . "\n";
			} catch (InternalServerError $e) {
				return $e->getMessage() . "\n";
			}
		}


		/**
		 * Find Celebrity Match
		 * @param  string $image - passed as image bytes
		 * @return JSON $result - celebrity details
		 */
		public function celebrityRecognitionLocalFileSystem($image) {

			try {

				$result = $this->rekognition->recognizeCelebrities([
						    'Image' => [ 
						        'Bytes' => "{$image}"
						    ]
						]);

				return $result;

			} catch (InvalidS3ObjectException $e) { 
				return $e->getMessage() . "\n";
			} catch (InvalidParameterException $e) {
				return $e->getMessage() . "\n";
			} catch (ImageTooLargeException $e) {
				return $e->getMessage() . "\n";
			} catch (AccessDeniedException $e) {
				return $e->getMessage() . "\n";
			} catch (InvalidImageFormatException $e) {
				return $e->getMessage() . "\n";
			} catch (ThrottlingException $e) {
				return $e->getMessage() . "\n";
			} catch (InternalServerError $e) {
				return $e->getMessage() . "\n";
			}

		}


		/**
		 * Find adult content
		 * @param  string $image - passed as image bytes
		 * @return JSON $result - adult content
		 */
		public function imageModerationLocalFileSystem($image) {

			try {

				$result = $this->rekognition->detectModerationLabels([
						    'Image' => [ 
						        'Bytes' => "{$image}"
						    ],
						    'MinConfidence' => 50,
						]);

				return $result;

			} catch (InvalidS3ObjectException $e) { 
				return $e->getMessage() . "\n";
			} catch (InvalidParameterException $e) {
				return $e->getMessage() . "\n";
			} catch (ImageTooLargeException $e) {
				return $e->getMessage() . "\n";
			} catch (AccessDeniedException $e) {
				return $e->getMessage() . "\n";
			} catch (InvalidImageFormatException $e) {
				return $e->getMessage() . "\n";
			} catch (ThrottlingException $e) {
				return $e->getMessage() . "\n";
			} catch (InternalServerError $e) {
				return $e->getMessage() . "\n";
			}

		}


		/**
		 * Detect objects
		 * @param  string $image - passed as image bytes
		 * @return JSON $result - oobjects in the image
		 */
		public function objectDetectionLocalFileSystem($image) {

			try {

			   $result = $this->rekognition->detectLabels([
				          'Image' => [ 
						        'Bytes' => "{$image}"
						   ],
				          'MaxLabels' => 40,
				          'MinConfidence' => 70,
						]);

			   return $result;

			} catch (InvalidS3ObjectException $e) { 
				return $e->getMessage() . "\n";
			} catch (InvalidParameterException $e) {
				return $e->getMessage() . "\n";
			} catch (ImageTooLargeException $e) {
				return $e->getMessage() . "\n";
			} catch (AccessDeniedException $e) {
				return $e->getMessage() . "\n";
			} catch (InvalidImageFormatException $e) {
				return $e->getMessage() . "\n";
			} catch (ThrottlingException $e) {
				return $e->getMessage() . "\n";
			} catch (InternalServerError $e) {
				return $e->getMessage() . "\n";
			}
		}

	}

