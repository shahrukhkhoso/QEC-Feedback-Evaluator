<?php

if(empty($_SESSION)) // if the session not yet started 
   session_start();

if(isset($_SESSION['number'])) { //if not yet logged in
   header("Location: QualityEnhancementCell.php");// send to login page
  exit;
}
if(isset($_SESSION['secondnumber'])) { //if not yet logged in
   header("Location: QualityEnhancementCell.php");// send to login page
  exit;
}   
?>
<?php
	$conn = new mysqli($servername, "root", "","quality_enhancement_cell");
	if($result = $conn -> query("select sentence_id from sentences order by sentence_id DESC limit 1;")){
		if($result->num_rows){
			$rowsnew=$result->fetch_assoc();
			$limit=$rowsnew['sentence_id'];
		}
	}
	$my_array = array();
	for ($i=0; $i < $limit; $i++) { 
		$my_array[$i]=$i+1;
	}
	shuffle($my_array);
	$my_array2 = array();
	for ($j=0; $j < $limit; $j++) { 
		$my_array2[$j]=$j+1;
	}
	shuffle($my_array2);
	$iterator=0;
	$previousID=0;
	$iterator2=0;
	$previousID2=0;
	$_SESSION['number']=$iterator;
	$_SESSION['secondnumber']=$iterator2;
	$_SESSION['array']=$my_array;
	$_SESSION['previous']=$$previousID;
	$_SESSION['array2']=$my_array2;
	$_SESSION['previous2']=$previousID2;
	header("Location: QualityEnhancementCell.php");// send to login page
?>