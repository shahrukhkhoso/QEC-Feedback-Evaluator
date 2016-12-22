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
<?php
					
					$sentence_id=$_SESSION['array'][$_SESSION['previous']];
					$servername="localhost";
					$conn = new mysqli($servername, "root", "","quality_enhancement_cell");
					if ($conn->connect_error) {
			    		die("Connection failed:".$conn->connect_error);
					}
					else if($result=$conn -> query("select * from sentences where sentence_id='".$sentence_id."'")){
						if($result->num_rows){
							$rows=$result->fetch_assoc();
						}
					}
					if(isset($_POST['submitbutton']) || isset($_POST['skipbutton'])){
						$arrboxesvalues=array();
						for ($i=0; $i < 7; $i++) { 
								$arrboxesvalues[$i]=0;
						}
						$arrboxes=$_POST['checkbox'];
						foreach ($arrboxes as $key) {
							$arrboxesvalues[$key]=1;
						}
						
						if($result=$conn -> query("select * from task2 where sentence_id='".$sentence_id."'")){
							if($result->num_rows){
								$rowsupdate=$result->fetch_assoc();
							}
						}
						$qecArray=array($rowsupdate['checkbox_1']+$arrboxesvalues[0],$rowsupdate['checkbox_2']+$arrboxesvalues[1],$rowsupdate['checkbox_3']+$arrboxesvalues[2],
							$rowsupdate['checkbox_4']+$arrboxesvalues[3],$rowsupdate['checkbox_5']+$arrboxesvalues[4],$rowsupdate['checkbox_6']+$arrboxesvalues[5],$rowsupdate['checkbox_7']+$arrboxesvalues[6],$rowsupdate['submit_count']+1,$rowsupdate['skip_count']+1);
						if(isset($_POST['submitbutton'])){
							$conn -> query("update task2 set checkbox_1='".$qecArray[0]."',checkbox_2='".$qecArray[1]."',checkbox_3='".$qecArray[2]."',checkbox_4='".$qecArray[3]."',checkbox_5='".$qecArray[4]."',checkbox_6='".$qecArray[5]."',checkbox_7='".$qecArray[6]."',submit_count='".$qecArray[7]."' where sentence_id='".$sentence_id."'");
						}
						if(isset($_POST['skipbutton'])){
							$conn -> query("update task2 set skip_count='".$qecArray[8]."' where sentence_id='".$sentence_id."'");
						}
						header("Location: QualityEnhancementCell.php");// send to login page
					}
					else if (isset($_POST['submitbutton2']) || isset($_POST['skipbutton2'])) {
						$sentence_id2=$_SESSION['array2'][$_SESSION['previous2']];
						if($result=$conn -> query("select * from sentences where sentence_id='".$sentence_id2."';")){
						if($result->num_rows){
								$rows=$result->fetch_assoc();
							}
						}
						$skipCount=0;
						if($result=$conn -> query("select * from task3 where sentence_id='".$sentence_id2."'")){
							if($result->num_rows){
								$rowsupdate=$result->fetch_assoc();
								$skipCount=$rowsupdate['skip_count'];
							}
						}
						$stringempty="";
					$sentence=$rows['sentence'];
					if(isset($_POST['submitbutton2'])){
						$subject=$_POST['subject'];
						$predicate=$_POST['predicate'];
						$score=$_POST['radios'];
						$conn -> query("insert into task3 (sentence_id,sentence,subject,predicate,skip_count,score) values ('".$sentence_id2."','".$sentence."','".$subject."','".$predicate."','".$skipCount."','".$score."');");
				}
				$scoreskip=0;
				if(isset($_POST['skipbutton2'])){
					$skipCount=$skipCount+1;
					if($conn -> query("insert into task3 (sentence_id,sentence,subject,predicate,skip_count,score) values ('".$sentence_id2."','".$sentence."','".$stringempty."','".$stringempty."','".$skipCount."','".$scoreskip."')")){
					}
				}
				header("Location: QualityEnhancementCell.php");// send to login page
			}
					
									
?>