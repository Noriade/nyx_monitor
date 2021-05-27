<?php
include 'config.php';

$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_PORT => $rpc_port,
  CURLOPT_URL => $rpc_url . ":" . $rpc_port,
  CURLOPT_USERPWD => $rpc_user . ":" . $rpc_password,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_POSTFIELDS => "{\"jsonrpc\": \"1.0\", \"id\":\"curltest\", \"method\": \"masternodelist\", \"params\": [\"full\"] }",
));
$listmasternodes = curl_exec($curl);
$listmasternodes = json_decode($listmasternodes);
echo "<pre>";
print_r($listmasternodes);
echo "</pre>";
$mnlist = $listmasternodes->{'result'};
curl_close($curl);

?>

<!DOCTYPE html>
<html>
<title>NYX NODE MONITOR</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<style>
html,body,h1,h2,h3,h4,h5 {font-family: "Raleway", sans-serif}
</style>
<body class="w3-light-grey">

<!-- Top container -->
<div class="w3-bar w3-top w3-black w3-large" style="z-index:4">

  <span class="w3-bar-item w3-right"><b>nyx_monitor</b> | <?php print $version; ?></span>
</div>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-top:23px;">

  <!-- Header -->
<?php include 'header.php'; ?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["address"])) {
    $addressErr = "<b>Address is required</b>";
  }
  else {
    $address = test_input($_POST["address"]);
    if (!preg_match("/^[a-zA-Z0-9 ]*$/",$address)) {
      $addressErr = "<b>Bad address</b>";
    }
  }
}
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  <div class="w3-container w3-padding">
    <h4>Check Masternode</h4>
    Address : <input type="text" name="address" size="42" class="w3-border w3-padding" value="<?php echo $address; ?>">
    <span class="error">*</span>
    <button type="submit" name="submit" value="Submit" class="w3-button w3-dark-grey">Find &nbsp;<i class="fa fa-arrow-right"></i></button>
    <?php echo $addressErr;?>
  </div>
</form>

<?php
if(!empty($_POST["address"])){
echo '<div class="w3-container w3-padding">';
echo "<h4>Masternode Found:</h4>";
for ($i = 0; $i < count($mnlist); $i++) {
    if($mnlist[$i]->{'addr'} == ($_POST["address"]))
    {
    echo '<table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">';
    echo '<tr>';
    echo   '<td><b>Rank</b></td>';
    echo   '<td><b>Status</b></td>';
    echo   '<td><b>Address</b></td>';
    echo   '<td><b>Last Seen</b></td>';
    echo   '<td><b>Last Paid</b></td>';
    echo   '<td><b>Active Time</b></td>';
    echo   '</tr>';    echo "<tr>";
    echo "<td>" . $mnlist[$i]->{'rank'} . "</td>";
    echo "<td>" . $mnlist[$i]->{'status'} . "</td>";
    echo "<td><a href=http://" . $nyx_explorer . "/address/" . $mnlist[$i]->{'addr'} .">" . $mnlist[$i]->{'addr'} . "</a></td>";
    echo "<td>" . date($date_format, $mnlist[$i]->{'lastseen'}) . "</td>";

    if($mnlist[$i]->{'lastpaid'} == 0){echo "<td>Not yet</td>";}
    else{echo "<td>" . date($date_format, $mnlist[$i]->{'lastpaid'}) . "</td>";}

    echo "<td>" . number_format(($mnlist[$i]->{'activetime'} / 86400), 0) . " days</td>";
    echo "</tr>";
    echo "</table>";
    }
}
echo "</div>";
}
?>



<?php
if(!empty($custom_mnlist[0])){
echo '<div class="w3-container w3-padding">';
echo "<h4>My Masternodes :</h4>";
echo '<table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">';
echo '<tr>';
echo '<td><b>Rank</b></td>';
echo '<td><b>Status</b></td>';
echo '<td><b>Address</b></td>';
echo '<td><b>Last Seen</b></td>';
echo '<td><b>Last Paid</b></td>';
echo '<td><b>Active Time</b></td>';
echo '</tr>';

  for ($i = 0; $i < count($mnlist); $i++) {
    if(in_array($mnlist[$i]->{'addr'}, $custom_mnlist))
      {
        echo "<tr>";
        echo "<td>" . $mnlist[$i]->{'rank'} . "</td>";
        echo "<td>" . $mnlist[$i]->{'status'} . "</td>";
        echo "<td><a href=http://" . $nyx_explorer . "/address/" . $mnlist[$i]->{'addr'} .">" . $mnlist[$i]->{'addr'} . "</a></td>";
        echo "<td>" . date($date_format, $mnlist[$i]->{'lastseen'}) . "</td>";
        if($mnlist[$i]->{'lastpaid'} == 0){
          echo "<td>Not yet</td>";
        }
        else{
          echo "<td>" . date($date_format, $mnlist[$i]->{'lastpaid'}) . "</td>";
        }
        echo "<td>" . number_format(($mnlist[$i]->{'activetime'} / 86400), 0) . " days</td>";
        echo "</tr>";
      }
    }
  echo "</table>";
  echo "</div>";
}
else{
  echo '<div class="w3-container w3-padding">';
  echo '<table>';
  echo "<h4>My Masternodes :</h4>";
  echo '<table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-orange">';
  echo '<tr>';
  echo '<td>Make your own list by adding each addresses in <b>config.php</b>:<br><b>$custom_mnlist = array("ADDR1", "ADDR2", "ADDR3");</b></td>';
  echo '</tr>';
  echo "</table>";
  echo "</div>";
}
?>




<?php if(!empty($mnlist)){
echo '<div id="mnlist" class="w3-container">';
echo   '<h4>All Masternodes :</h4>';
echo   '<table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">';
echo     '<tr>';
echo       '<td><b>Rank</b></td>';
echo       '<td><b>Status</b></td>';
echo       '<td><b>Address</b></td>';
echo       '<td><b>Last Seen</b></td>';
echo       '<td><b>Last Paid</b></td>';
echo       '<td><b>Active Time</b></td>';
echo     '</tr>';

    for ($i = 0; $i < count($mnlist); $i++) {
        echo "<tr>";
        echo "<td>" . $mnlist[$i]->{'0'} . "</td>";
        echo "<td>" . $mnlist[$i]->{'status'} . "</td>";
        echo "<td><a href=http://" . $nyx_explorer . "/address/" . $mnlist[$i]->{'addr'} .">" . $mnlist[$i]->{'addr'} . "</a></td>";
        echo "<td>" . date($date_format, $mnlist[$i]->{'lastseen'}) . "</td>";
        if($mnlist[$i]->{'lastpaid'} == 0){echo "<td>Not yet</td>";}
        else{echo "<td>" . date($date_format, $mnlist[$i]->{'lastpaid'}) . "</td>";}
        echo "<td>" . number_format(($mnlist[$i]->{'activetime'} / 86400), 0) . " days</td>";
        echo "</tr>";
    }

echo '</table><br>';
echo '</div>';
}
?>

  <!-- Footer -->
<?php include 'footer.php';?>

  <script>
    function loadlink(){
      $( "#status" ).load( "status.php" );
      $( "#connectioncount" ).load( "connectioncount.php" );
      $( "#blockcount" ).load( "blockcount.php" );
      $( "#balance" ).load( "balance.php" );
    }

    loadlink(); // This will run on page load
    setInterval(function(){
        loadlink() // this will run after every 5 seconds
    }, 1000);
  </script>


  <!-- End page content -->
</div>

</body>
</html>
