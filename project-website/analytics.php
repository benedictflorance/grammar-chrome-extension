  <html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <title>Analytics</title>
    <link href="dashboard.css" rel="stylesheet" type="text/css"/>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>

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
  echo "<a href =\"logout.php\" id=\"button\" class=\"red left\">Logout</a><a href =\"grammar.php\" id=\"button\" class=\"red right\">Dashboard</a>
  <h1>GrammarCheck &copy</h1><h5 class=\"push\">#1 grammar ext since 2017</h5><h2>Welcome, ".ucwords($_SESSION['name'])."!</h2>
  <h2>Analytics of your scores!</h2>";
  $query=$conn->prepare("SELECT * FROM data WHERE name=? and email=?");
  $query->bind_param("ss",$_SESSION['gname'],$_SESSION['gmail']);
  $query->execute();
  $result=$query->get_result();
  if(mysqli_num_rows($result)>0){
  $string='[[\'Time when GrammarCheck was used\',\'Score\'],';
  while($rows=mysqli_fetch_array($result))
  {
    $dt = new DateTime("@{$rows['time']}");
    $dt=$dt->format('d-m-Y H:i:s');
    $string.="['{$dt}', {$rows['score']}],";
  }
  rtrim($string,",");
  $string.=']';
}
  echo "<script>google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable(".$string.");

        var options = {
          title: '{$_SESSION['name']}\'s GrammarCheck Score Analysis',
          curveType: 'function',
          legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }</script>";
}
}
else{
echo "<h1>Access Denied</h2><br><a id=\"button\" class=\"green\" href=\"login.php\">Click here to log in</a></div></div></body></html>";
}

?>    
  </head>
  <body>
    <div id="curve_chart" style="width: 900px; height: 500px"></div>
  </body>
</html>