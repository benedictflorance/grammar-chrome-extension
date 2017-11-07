<?php
   ini_set('display_errors', 1); 
   ini_set('log_errors',1); 
   error_reporting(E_ALL); 
   mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
   include("configure.php");
   session_start();
if(isset($_SESSION['username'])&&!empty($_SESSION['username']))
{
	$name=$_REQUEST['name'];
	$email=$_REQUEST['email'];
	$_SESSION['gname']=$name;
	$_SESSION['gmail']=$email;
}
?>