<?php
#############################################################################
## CODE AUTHORS: THACH BUI (CSS/HTML), RONNIE BLACKBURN (PHP)              ##
## CENTER FOR HEALTH INSIGHTS, UNIVERSITY OF MISSOURI-KANSAS CITY          ##
## CREATED IN 2014 FOR CENTER FOR ECONOMIC INFORMATION NEIGHBORHOOD SURVEY ##
#############################################################################
?>

<html lang="en">
    <head>
		
        <meta charset="utf-8" />
        <p>
		<img src="redcap.png" alt="redcap" width="62" height="62" align="right">
		
		</p>
    </head>
    <body>
	
	
	<style>
	@media only screen and (max-device-width: 480px) only screen and (max-device-width: 480px){
		div#wrapper {
			width: 100px;
		}

		div#header {
			background-image: url(media-queries-phone.jpg);
			height: 93px;
			position: relative;
		}

		div#header h1 {
			font-size: 140%;
		}

		#content {
			float: none;
			width: 300%;
		}

		#navigation {
			float:none;
			width: auto;
		}
		
	img, table#form_table {
			max-width: 320px;
		} 
		button {
		zoom:50%;
		align:"middle";}
		
		html {
			display:table;
			margin:auto;
			}
		
		
		textarea {
		height: 50px;
		width: 95%;
		font-size: 12px;
		font-family: Arial, Helvetica;
		}
	
		form {
		zoom:190%;}
		
		fieldset {
		align:right;
		margin:non;}
		
		body
		{
		zoom:180%;
		font-size: 100% ;
		background-color: #FFFFCC;
		display:table-cell;
		vertical-align: middle;
		text-align: center;
		}
				
		select {
		width: 120px;
		}
		input[type="text"] {
		width: 140px;
		font-size:12px;
}
	
	</style>
	<p></br><legend><font size="6"  > <td><strong> Neighborhood Housing Conditions Survey </legend></strong></font><br></br></td></p>
	

<?php
define("SURVEYOR_PID", 188);

# Assuming the code in on the same level as redcap directory 
require_once './common_api_funcs.php'; 	# library posted on wiki 
define("NOAUTH", true); 	# No need to authenticate - called as Project Bookmark only 
require_once './redcap_connect.php'; # for Plugin 
# Check that legit request 
if (isset($_GET["surveyor_id"])) {
  $surveyor_id = $_GET["surveyor_id"];
  # NOTE: Using deprecated mysql_* functions.  Change to mysqli_* for newer PHP 
  $sql = "SELECT bl.Block_ID,Starting_Address FROM batch_lookup ba
          LEFT JOIN blocks_lookup bl ON bl.Block_ID = ba.Block_ID
          WHERE ba.Batch_ID =
              (SELECT value FROM redcap_data
               WHERE project_id = ".SURVEYOR_PID." AND field_name = 'surv_batch_id' AND record = $surveyor_id)";
  if($q = mysql_query($sql)) { 
    if (mysql_num_rows($q)) {
      $sql = "SELECT value FROM redcap_data
          WHERE project_id = ".SURVEYOR_PID." AND field_name = 'surveyor_name' AND record = $surveyor_id";
      if($s = mysql_query($sql)) { 
        if (mysql_num_rows($s)) {
          # Let's make the simplest page 
          $surveyor_name = mysql_result($s,0);
        } else print "Error! This surveyor is not assigned any blocks."; 
      } else print "Error! MySQL returned error <pre>".mysql_error()."</pre>";
      
      # Let's make the simplest page 
      print "<p> Surveyor: <b> $surveyor_name </p>";
      print "<form action='./select_parcel.php' method='get'>\n";
      print "<input type='hidden' name='surveyor_id' value='$surveyor_id'>\n";
      print "Select block: <select name='block_id'>\n  "; 
      while ($row = mysql_fetch_assoc($q)) { 
        print "<option value='".$row["Block_ID"]."'> ".$row["Starting_Address"]."</option>\n"; 
      } 
      print "</select> <br><br> <td><input type='submit' value='Submit'>\n</form></td></br>";
    } else print "Error! This surveyor is not assigned any blocks."; 
  } else print "Error! MySQL returned error <pre>".mysql_error()."</pre>";
} else print "You cannot go here!"; 
print "<br><td><button type='button' onClick='window.close();'>Close</button></td></br>"; 

?>
</td></tr></div>

	
 </body>
	
	
</html>