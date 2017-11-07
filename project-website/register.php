<?php
   ini_set('display_errors', 1); 
   ini_set('log_errors',1); 
   error_reporting(E_ALL); 
   mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
 include("configure.php");
 session_start();
 $userErr=$passErr=$nameErr=$emailErr=$submitErr="";
 if($_SERVER["REQUEST_METHOD"] == "POST") 
{
$errors=0;
$name = trim(mysqli_real_escape_string($conn,$_POST['personname']));
$user = trim(mysqli_real_escape_string($conn,$_POST['username']));
$pass = mysqli_real_escape_string($conn,$_POST['password']);
$name = preg_replace('!\s+!', ' ', $name);
if(empty($name))
   {$nameErr="Name cannot be empty";
	$errors++;}
if(empty($pass))
   {$passErr="Password cannot be empty";
	$errors++;}
if(empty($user))
   	{$userErr="Username cannot be empty";
	$errors++;}
if (!preg_match("/^[a-zA-Z ]*$/",$name)) 
{
	$nameErr="Only letters and space allowed";
	$errors++;
}
if (!preg_match("/^[a-z0-9_.]*$/",$user)) 
{
	$userErr="Only lower alphanumeric, _ and . allowed";
	$errors++;
}
if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){
     $secret = '6Ld4dScUAAAAALM1JwK-cyterioH49a4AxlpywuI';
     $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
     $responseData = json_decode($verifyResponse);
     if($responseData->success){
if($errors==0)
{   try{
	$pass=password_hash($pass,PASSWORD_DEFAULT);
	$sql =$conn->prepare("INSERT INTO users(name,username,password) VALUES(?,?,?)");
	$sql->bind_param("sss",$name,$user,$pass);
	$result=$sql->execute();
    header("location:login.php");
}
    catch(mysqli_sql_exception $e){
    	$submitErr="Username already taken";
    }
}
}
else
  $submitErr="Robot verification failed. Please try again";
}
else
  $submitErr="Please click on the Recaptcha box";
}
?>
<!DOCTYPE html>
<head>
<title>GrammarCheck-Registration</title>
<link href="login.css" type="text/css" rel="stylesheet"/>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
<script>
function checkUser(str) {
    if (str.length == 0) { 
        document.getElementById("user").innerHTML ='';
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
            	if (this.responseText=="Username can be used"){
                document.getElementById("usersuccess").innerHTML = this.responseText;
                document.getElementById("user").innerHTML ="";
            	}
            	else{
                document.getElementById("user").innerHTML = this.responseText;
                document.getElementById("usersuccess").innerHTML = "";
            	}
            }
        };
        xmlhttp.open("GET", "checkuser.php?q=" + str, true);
        xmlhttp.send();
    }
}
</script>
</head>
<body>
<div class="outer">
<div class="middle">
<form action="<?php echo htmlentities($_SERVER["PHP_SELF"]);?>" method="post">
<h2>Registration Portal</h2>
<p>All <span style="color:red">*</span> fields are mandatory</p>
<p>On successful registration, you will be redirected to the login page.</p>
<label>Name:<span style="color:red">*</span><input type = "text" name = "personname"/></label><br><span class="error"><?php echo $nameErr;?></span><br>
<label>Username:<span style="color:red">*</span><input type = "text" name = "username" onkeyup="checkUser(this.value)"/></label><br><span class="error" id="user"><?php echo $userErr;?></span><span id="usersuccess" style="color: green"></span><br>
<label>Password:<span style="color:red">*</span><input type = "password" name = "password"/></label><br><span class="error"><?php echo $passErr;?></span><br>
<div class="g-recaptcha" data-sitekey="6Ld4dScUAAAAAMNd65qyE8smg_uZqbS7vZMGGTvr"></div>
<input type = "submit" value = "Get Started"/><br><span class="error"><?php echo $submitErr;?></span><br>
<p>Already a member? <a href="login.php">Log In</a></p>
</div>
</div>
</form>
</body>
</html>