<html>
  <head>
      <script src="https://apis.google.com/js/platform.js" async defer></script>
      <title>Extension History</title>
    <link href="dashboard.css" rel="stylesheet" type="text/css"/>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
  </head>
  <body><div id="outer"><div id="middle">

<?php
   ini_set('display_errors', 1); 
   ini_set('log_errors',1); 
   ini_set("allow_url_fopen", 1);
   error_reporting(E_ALL); 
   mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
   include("configure.php");
   session_start();
if(isset($_SESSION['username'])&&!empty($_SESSION['username']))
{
	if(isset($_SESSION['gname'])&&isset($_SESSION['gmail']))
	{
  echo "<a href =\"logout.php\" id=\"button\" class=\"red left\">Logout</a><a href =\"analytics.php\" id=\"button\" class=\"red right\">Analytics</a>
  <h1>GrammarCheck &copy</h1><h5 class=\"push\">#1 grammar ext since 2017</h5><h2>Welcome, ".ucwords($_SESSION['name'])."!</h2>
  <h2>Your Grammar History and Scores</h2>";
  $query=$conn->prepare("SELECT * FROM data WHERE name=? and email=?");
  $query->bind_param("ss",$_SESSION['gname'],$_SESSION['gmail']);
  $query->execute();
  $result=$query->get_result();
  $i=1;
  if(mysqli_num_rows($result)>0){
  echo "<table><tr><th>S. No.</th><th>Text Searched</th><th>Corrections Suggested</th><th>Score</th><th>Date/Time</th></tr>";
  while($rows=mysqli_fetch_array($result))
  { 
  echo "<tr><td>$i</td><td>{$rows['text']}</td><td>";
  $json = file_get_contents("https://api.textgears.com/check.php?text=".urlencode($rows['text'])."&key=RxOg8C2AsuPygXPN");
  $obj = json_decode($json);
  if($rows['score']==100)
  	echo "Fully right!";
  else{
  for($a=0;;$a++)
    	{
    		if(empty($obj->errors[$a])||!isset($obj->errors[$a]))
    		{
    			$num=$a-1;
    			break;
    		}
    	}
  for($b=0;$b<=$num;$b++)
    	{
    		 echo "<br><b>".$obj->errors[$b]->bad."</b> as ";
    		 for($j=0;;$j++)
    		 {
    		 	if(isset($obj->errors[$b]->better[$j])){
    		 		if($j==0)
    		 		echo "\"".$obj->errors[$b]->better[$j]."\"  ";
    		 		else
    		 		echo " or \"".$obj->errors[$b]->better[$j]."\"  ";
    		 	}
    		 	else 
    		 		break;
    		 }

    	}
    }
    		echo "</td><td>{$rows['score']}</td><td>";
    		$dt = new DateTime("@{$rows['time']}");
			echo $dt->format('d-m-Y H:i:s')."</td>";
			$i++;
	}
	echo "</table>";
}
  else
    echo "<br><h2>Alas, You haven't started using the GrammarCheck Extension</h2>";
	}
echo "</div></div></body></html>";
}

else{
echo "<h1>Access Denied</h2><br><a id=\"button\" class=\"green\" href=\"login.php\">Click here to log in</a></div></div></body></html>";
}


































