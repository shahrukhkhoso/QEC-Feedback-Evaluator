<html>
	<?php
		
		if(empty($_SESSION)) // if the session not yet started 
	   	session_start();

		if(!isset($_SESSION['number'])) { //if not yet logged in
	   		header("Location: sessionQEC.php");// send to login page
	   		exit;
		}
		if(!isset($_SESSION['secondnumber'])) { //if not yet logged in
	   		header("Location: sessionQEC.php");// send to login page
	   		exit;
		}
	?>
	<head>
		
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" 
				integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" 
				integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" 
				integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="style.css">
		
		<title>Namal Feedback</title>
		
	</head>
	
	<body>
	<?php
		class ReaingFromDatabase
		{
				function read_Data($Query){
					$servername="localhost";
					$conn = new mysqli($servername, "root", "","quality_enhancement_cell");
					if ($conn->connect_error) {
			    		die("Connection failed:".$conn->connect_error);
					}
					else{
						if($result=$conn->query($Query)){
							if($result->num_rows){
								$rows=$result->fetch_assoc();
							}
						}
						$newComment=$rows['sentence'];
						return $newComment;
					}
				}
				function button1Clicked(){
					$_SESSION['previous']=$_SESSION['number']; 
					$_SESSION['number']=$_SESSION['number']+1;
				}
				function button2Clicked(){
					$_SESSION['previous2']=$_SESSION['secondnumber']; 
					$_SESSION['secondnumber']=$_SESSION['secondnumber']+1;
				}
		}
		$read = new ReaingFromDatabase();
		$sentence_id = $_SESSION['array'][$_SESSION['number']];
		$Query = "select sentence from sentences where sentence_id='".$sentence_id."'";
		$sentence=$read->read_Data($Query);
		$sentence_id2 = $_SESSION['array2'][$_SESSION['secondnumber']];
		$Query2 = "select sentence from sentences where sentence_id='".$sentence_id2."'";
		$sentence2=$read->read_Data($Query2);
		?>
		<div class="container auth">
			<div>
				<h1 class="text-center" align="center">Quality Enhancement Cell
					<span>Feedback helps us to improve our teaching methods!</span>
				</h1>
			</div>
			<div id="big-form" class="well auth-box">
				<ul class="nav nav-tabs">
					<li class="active"><a data-toggle="tab" href="#task1">Task 1</a></li>
					<li><a data-toggle="tab" href="#task2">Task 2</a></li>
				</ul>
				
				<div class="tab-content">
					<div id="task1" class="tab-pane fade in active">
						<form action="cboxe.php" method="post">
							<fieldset style="padding-top:20px;">
								<div id="alert">
									<h4 align="center" color="yellow"><strong>Task 1</strong></h4>
									<p align="center">Check appropriate checkboxes relevant to following comment</p>
								</div>
								<div class="form-group" style="padding-top:21px;">
									<label class=" control-label" for="textarea">Comment</label>
									<div class="" style="padding-top:2px;">                
										<textarea class="form-control" id="comment" name="textarea" placeholder= "<?php echo $sentence; ?>"></textarea>
									</div>
								</div>
								<div class="form-group" style="padding-top:11px;">
									<label for="checkboxes-0">
									  <input name="checkbox[]" id="checkboxes-0" value="0" type="checkbox"/>
									  Accessibility of teacher outside the classroom
									</label><br>
									<label for="checkboxes-1">
									  <input name="checkbox[]" id="checkboxes-1" value="1" type="checkbox"/>
									  Knowledge base/grip of instructor over the subject
									</label><br>
									<label for="checkboxes-2">
									  <input name="checkbox[]" id="checkboxes-2" value="2" type="checkbox"/>
									  Instructor's ability to motivate you towards the subject
									</label><br>
									<label for="checkboxes-3">
									  <input name="checkbox[]" id="checkboxes-3" value="3" type="checkbox"/>
									  Instructor's ability to integerate the content with the real world
									</label><br>
									<label for="checkboxes-4">
									  <input name="checkbox[]" id="checkboxes-4" value="4" type="checkbox">
									  Adherence to course outline
									</label><br>
									<label for="checkboxes-5">
									  <input name="checkbox[]" id="checkboxes-5" value="5" type="checkbox">
									  Instructions help regarding lab
									</label><br>
									<label for="checkboxes-6">
									  <input name="checkbox[]" id="checkboxes-6" value="6" type="checkbox">
									  Your satisfaction with the delivery method of the instructor
									</label>
								</div>
								<div class="form-group">
									<div align="center">
										<button id="nextComment" onclick="<?php $read -> button1Clicked(); ?>" name="submitbutton" class="btn btn-primary">Next Comment</button>
										<button id="skipComment" name="skipbutton" class="btn btn-primary">Skip Comment</button>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
					
					<div id="task2" class="tab-pane fade">
						<form action="cboxe.php" method="post">
							<fieldset style="padding-top:20px;">
								<div id="alert">
									<h4 align="center"><strong>Task 2</strong></h4>
									<p align="center">Assign Score and Select Subject and Predicte</p>
								</div>
								<div class="form-group" style="padding-top:20px;">
									<label class=" control-label" for="textarea">Comment</label>
									<div class="" style="padding-top:2px;">                
										<textarea class="form-control" id="comment" name="textarea" placeholder="<?php echo $sentence2; ?>"></textarea>
									</div>
								</div>
								<div class="form-group">
									<label>Subject</label>
									<input class="form-control inputField" id="subject" name="subject" placeholder="Teacher/Module/..">
								</div>
								<div class="form-group">
									<label>Predicate</label>
									<input class="form-control inputField" id="predicate" name="predicate" placeholder="Teacher/Module/..">
								</div>
								<div class="form-group">
									<label class=" control-label" for="radios">Select Score</label>
									<div class="" align="center">
										<label class="radio-inline" for="radios-0">
											<input name="radios" id="radios-0" value="1" type="radio">
											1
										</label> 
										<label class="radio-inline" for="radios-1">
											<input name="radios" id="radios-1" value="2" type="radio">
											2
										</label> 
										<label class="radio-inline" for="radios-2">
											<input name="radios" id="radios-2" value="3" type="radio">
											3
										</label> 
										<label class="radio-inline" for="radios-3">
											<input name="radios" id="radios-3" value="4" type="radio">
											4
										</label>
										<label class="radio-inline" for="radios-4">
											<input name="radios" id="radios-4" value="5" type="radio">
											Very Positive (5)
										</label>
									</div>
								</div>
								<div class="form-group" style="padding-top:2px;">
									<div align="center">
										<button id="nextComment2" onclick="<?php $read -> button2Clicked();?>" name="submitbutton2" class="btn btn-primary">Next Comment</button>
										<button id="skipComment2" name="skipbutton2" class="btn btn-primary">Skip Comment</button>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>