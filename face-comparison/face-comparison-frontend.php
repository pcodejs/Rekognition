<?php
	
/*==============================================================================
 * Copyright (c) 2020 Berkine Design
 * =============================================================================
 * 
 *
 * Project: Amazon Rekognition - Deep Learning Powered Image Recognition Service
 * Author: Berkine Design
 * 
 * 
 * =============================================================================
 * Original Filename: face-comparison-frontend.php
 * Last Modified Date: 15 March 2019
 *
 */

?>


<!DOCTYPE html>
<html lang="en">
<head>


	<!-- GENERAL METAS -->
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


	<!-- WEBSITE TITLE -->
	<title>Amazon Rekognition - Deep Learning Powered Image Recognition Service</title>


	<!-- INCLUDE CSS FILES -->
	<link rel="stylesheet" href="../distr/css/bootstrap.min.css">
	<link rel="stylesheet" href="../distr/css/all.min.css">
	<link rel="stylesheet" href="../distr/css/fontawesome.min.css">	
	<link rel="stylesheet" href="../distr/css/animate.css">			
	<link rel="stylesheet" href="../distr/css/styles.css">

	
	<!-- INCLUDE FONTS -->
	<link href="https://fonts.googleapis.com/css?family=Poppins:400,600,700,800" rel="stylesheet">
	<link href='https://fonts.googleapis.com/css?family=Lato:300,400,700,900' rel='stylesheet' type='text/css'>


	<!-- INCLUDED JS FILES FOR PRELOAD CHECK -->
	<script src="../distr/js/jquery-3.4.1.min.js"></script>
	<script src="../distr/js/modernizr.js"></script>
	

	<!-- PRELOAD CHECK -->
	<script>
		$(window).on("load", function() {

			$(".se-pre-con").fadeOut("slow");
			
		});
	</script>

</head>
<body>

	
	<!-- PRELOAD CHECK -->
	<div class="se-pre-con"></div>


	<!-- MAIN SECTION -->
	<section id="section">


		<!-- MENU BAR -->
		<div class="container-fluid" id="top-nav">
			<div class="container">
				<nav class="navbar sticky-top navbar-expand-lg">
				  	<a class="navbar-brand" href="../index.php">Amazon <span>Rekognition</span></a>
				  	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
				    	<span class="navbar-toggler-icon"></span>
				  	</button>
				  	<div class="collapse navbar-collapse" id="navbarNav">
				    	<ul class="navbar-nav">
				      		<li class="nav-item active">
				        		<a class="nav-link" href="../index.php">Home <span class="sr-only">(current)</span></a>
				      		</li>
				      		<li class="nav-item dropdown">
						        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						          Demo
						        </a>
						        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
						          	<a class="dropdown-item" href="../object-detection/object-detection-frontend.php">Object and Scene Detection</a>
						          	<a class="dropdown-item" href="../facial-analysis/facial-analysis-frontend.php">Facial Analysis</a>
						          	<a class="dropdown-item" href="../face-comparison/face-comparison-frontend.php">Face Comparison</a>
						          	<a class="dropdown-item" href="../celebrity-recognition/celebrity-recognition-frontend.php">Celebrity Recognition</a>
						          	<a class="dropdown-item" href="../image-moderation/image-moderation-frontend.php">Image Moderation</a>
						          	<a class="dropdown-item" href="../text-in-image/text-image-frontend.php">Text in Image</a>
						        </div>
	     	 				</li>
				      		<li class="nav-item" id="purchase-button">
				        		<a href="https://codecanyon.net/user/berkinedesign/portfolio" target="_blank">Purchase Now</a>
				      		</li>			 
				   	 	</ul>
				  </div>
				</nav>
			</div>
		</div> <!-- END MENU BAR -->



		<div class="container-fluid" id="comparison">



			<!-- MAIN ROW FOR IMAGE REKOGNITION -->
			<div class="row no-gutters">
		

				<!-- MAIN OUTER BOX WRAPPER -->
				<div id="outer-wrapper" >
					
					<h3>Face Comparison</h3>
					<h5>Compare faces to see how closely they match based on a similarity percentage</h5>
	

					<!-- MAIN FORM -->
					<form id="face-comparison" action="face-comparison-backend.php" method="post" enctype="multipart/form-data">

						
						<div class="row">
							

							<!-- RESULTS OUTPUT PANEL -->
							<div class="col-md-3">
								
								<div id="label-container">
									
									<!-- COLUMN TITLES -->
									<div id="columns">
										<h6 class="left">Results</h6>
										<h6 class="right">Confidence</h6>
									</div>
									
									<!-- OUTPUT RESULTS -->
									<div id="labels"></div>
									
									<span id="processing" class="deactivated"><img src="../distr/img/processing.gif" alt="Processing"></span>

								</div>

							</div> <!-- END RESULTS OUTPUT PANEL -->	
							


							<!-- IMAGE UPLOAD AND PROCESS -->
							<div class="col-md-4">
								
								<!-- MAIN ACTIVE IMAGE -->
								<div id="image-holder">
									<div id="image-wrapper">				
								
										<img id="image" src="../distr/img/fc1-reference.jpg" alt="">

									</div>
								</div>		


								<!-- UPLOAD BUTTON AND SAMPLE IMAGES -->
								<div id="image-utilities" class="row">
									
									<!-- UPLOAD IMAGE -->
									<div id="upload-image" class="col-md-6">
										
										<h6>Use your own image</h6>

										<!-- SELECT FILE -->
										<div class="upload">
											<input type="file" name="file" id="file" class="upload-image"  required />
											<label for="file">
												<i class="fas fa-upload"></i>
												<span id="file-label">Upload an image...</span>
											</label>		
										</div>
										
										<p>Upload Face to Search</p>
										<p>Image must be <span>.jpeg</span> or <span>.png</span> format and no larger than <span>5MB</span></p>
										
										<div id="status-message"></div>

									</div> <!-- END UPLOAD IMAGE -->
									

									<!-- SAMPLE IMAGES TO TRY OUT -->
									<div id="sample-image" class="col-md-6">

										<h6>Try Sample Faces to Search</h6>

										<a href="" class="img-thumb active"><img src="../distr/img/fc1-reference.jpg" alt="Demo Image"></a>

										<a href="" class="img-thumb"><img src="../distr/img/fc2-reference.jpg" alt="Demo Image"></a>
										
									</div>

								</div> <!-- END UPLOAD BUTTON AND SAMPLE IMAGES -->													
								

							</div> <!-- END IMAGE UPLOAD AND PROCESS -->




							<!-- IMAGE UPLOAD AND PROCESS -->
							<div class="col-md-5">
								
								<!-- MAIN ACTIVE IMAGE -->
								<div id="image-holder">
									<div id="image-wrapper-comparison">				
										
										<canvas id="canvas"></canvas>

										<img id="image-comparison" src="../distr/img/fc1-large.jpg" alt="">

									</div>
								</div>		


								<!-- UPLOAD BUTTON AND SAMPLE IMAGES -->
								<div id="image-utilities" class="row">
									
									<!-- UPLOAD IMAGE -->
									<div id="upload-image" class="col-md-6">
										
										<h6>Use your own image</h6>

										<!-- SELECT FILE -->
										<div class="upload">
											<input type="file" name="file-comparison" id="file-comparison" class="upload-image"  required />
											<label for="file-comparison">
												<i class="fas fa-upload"></i>
												<span id="file-label-comparison">Upload an image...</span>
											</label>		
										</div>
										
										<p>Upload Image to Search From</p>
										<p>Image must be <span>.jpeg</span> or <span>.png</span> format and no larger than <span>5MB</span></p>
										
										<div id="status-message-comparison"></div>

									</div> <!-- END UPLOAD IMAGE -->
									

									<!-- SAMPLE IMAGES TO TRY OUT -->
									<div id="sample-image-comparison" class="col-md-6">

										<h6>Try Sample Images to Search From</h6>

										<a href="" class="img-thumb-comparison active"><img src="../distr/img/fc1-large.jpg" alt="Demo Image"></a>

										<a href="" class="img-thumb-comparison"><img src="../distr/img/fc2-large.jpg" alt="Demo Image"></a>
										
									</div>

								</div> <!-- END UPLOAD BUTTON AND SAMPLE IMAGES -->													
								

							</div> <!-- END IMAGE UPLOAD AND PROCESS -->



						</div>
						
			
					
					</form> <!-- END MAIN FORM -->
					
					
					<!-- AWS LOGO -->
					<div id="logo">
						<p>Powered By</p>
						<img src="../distr/img/aws.png" alt="AWS Logo">
					</div>


				</div> <!-- END MAIN OUTER BOX WRAPPER -->


			</div> <!-- END MAIN ROW FOR IMAGE REKOGNITION -->

			
			<!-- BOTTOM COPYRIRGHT INFO -->
			<div id="copyright">		

				<p>Copyright &copy; 2020 <a href="aws.berkinedesign.com" target="_blank">Berkine Design</a> <span>|</span> All Rights Reserved</p>
				<p>Amazon Rekognition is a service that belongs to Amazon Web Services &trade;</p>

			</div>



		</div> <!-- END CONTAINER -->

	</section> <!-- END SECTION -->


	
	<!-- INCLUDED JS FILES -->
	<script src="../distr/js/bootstrap.min.js"></script>
	<script src="../distr/js/jquery.foggy.min.js"></script>
	<script src="../distr/js/jcanvas.min.js"></script>
	<script src="../distr/js/custom.js"></script>
	


</body>
</html>