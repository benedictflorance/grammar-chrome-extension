<?php
   	include("configure.php");
   	ini_set('display_errors', 1); 
   	ini_set('log_errors',1); 
   	error_reporting(E_ALL); 
   	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	$name = $_REQUEST["name"];
	$email=$_REQUEST["email"];
	$text=$_REQUEST["text"];
	$score=$_REQUEST["score"];
	$time=time();
	$query=$conn->prepare("INSERT INTO data(name,email,text,score,time) VALUES(?,?,?,?,?)");
	$query->bind_param("sssii",$name,$email,$text,$score,$time);
	$query->execute();
?>