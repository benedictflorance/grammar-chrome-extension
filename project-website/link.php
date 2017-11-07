<html>
  <head>
    <meta name="google-signin-client_id" content="807204187397-mu2iefr1uak1gf3perstme3s1rcgmvq9.apps.googleusercontent.com">
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <link href="dashboard.css" rel="stylesheet" type="text/css"/>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
  </head>
  <body><div id="outer"><div id="middle">
<?php
   ini_set('display_errors', 1); 
   ini_set('log_errors',1); 
   error_reporting(E_ALL); 
   mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
   include("configure.php");
   session_start();
if(isset($_SESSION['username'])&&!empty($_SESSION['username']))
{
echo "
  <a href =\"logout.php\" id=\"button\" class=\"green left\">Logout</a>
  <h1>GrammarCheck &copy</h1><h5>#1 grammar ext since 2017</h5><h2 class=\"top\" >Welcome, ".ucwords($_SESSION['name'])."!</h2>
  <h2>Sign in to your Google Account to view your grammar history and analysis</h2>
    <div class=\"g-signin2\" data-onsuccess=\"onSignIn\" data-theme=\"dark\"></div>
    <script>
      function onSignIn(googleUser) {
        // Useful data for your client-side scripts:
        var profile = googleUser.getBasicProfile();
        var name=profile.getName();
        var email=profile.getEmail();
      var req = new XMLHttpRequest();
    req.addEventListener('readystatechange', function (evt) {
    if (req.readyState === 4) {
      if (req.status === 200) {
      } else {
        console.log(\"Status: \"+req.status);
      }
    }
  });
  req.open('GET', 'setsession.php?name='+encodeURIComponent(name)+\"&email=\"+encodeURIComponent(email), true);
  req.send();
  setTimeout(function(){
    window.location.href='grammar.php';
  },2000);
      };
    </script>
    </div>
    </div>
  </body>
</html>";
}
else{
echo "<h1>Access Denied</h2><br><a id=\"button\" class=\"green\" href=\"login.php\">Click here to log in</a></div></div></body></html>";
}
