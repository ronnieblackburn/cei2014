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
		<img src="redcap.png" alt="redcao" width="62" height="62" align="right">
		
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
		button,submit {
		zoom:100%;
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
		zoom:150%;
		font-size: 100% ;
		background-color: #FFFFCC;
		display:table-cell;
		vertical-align: middle;
		
		}
				
		select {
		width: 120px;
		}
		input[type="text"] {
		width: 140px;
		font-size:12px;
}
	
	</style>
	
		
	<p></br><legend><font size="6"  > <td><strong> Neighborhood Housing Conditions Survey </legend></strong></font></td></p>
	
<?php
define("SURVEYOR_PID", 188);
$surveyhash = 'TETHKRLLDC';

# Assuming the code in on the same level as redcap directory 
require_once './common_api_funcs.php'; 	# library posted on wiki 
define("NOAUTH", true); 	# No need to authenticate - called as Project Bookmark only 
require_once './redcap_connect.php'; # for Plugin 
# Check that legit request 
if (isset($_GET["block_id"]) && isset($_GET["surveyor_id"])) {
  $block_id = $_GET["block_id"];
 
  $surveyor_id = $_GET["surveyor_id"];
  # NOTE: Using deprecated mysql_* functions.  Change to mysqli_* for newer PHP 
  $sql = "SELECT Parcel_ID,Address FROM parcels_lookup WHERE block_id = $block_id";
  if($q = mysql_query($sql)) {
    if (mysql_num_rows($q)) { 
      # Look up surveyor name from the ID
      $sql = "SELECT value FROM redcap_data
          WHERE project_id = ".SURVEYOR_PID." AND field_name = 'surveyor_name' AND record = $surveyor_id";
      if($s = mysql_query($sql)) { 
        if (mysql_num_rows($s)) {
          # Let's make the simplest page 
          $surveyor_name = mysql_result($s,0);
        } else print "Error! This surveyor is not assigned any blocks."; 
      } else print "Error! MySQL returned error <pre>".mysql_error()."</pre>";

      # Look up surveyor name from the ID
      $sql = "SELECT Starting_Address FROM blocks_lookup WHERE Block_ID = $block_id";
      if($s = mysql_query($sql)) { 
        if (mysql_num_rows($s)) {
          # Let's make the simplest page 
          $block_address = mysql_result($s,0);
        } else print "Error! This surveyor is not assigned any blocks."; 
      } else print "Error! MySQL returned error <pre>".mysql_error()."</pre>";
      
      # Let's make the simplest page 
	  
      print "<p><td>Surveyor:<font size=4 color=blue> $surveyor_name <br></font></td>";
      print "<td>Block ID: <font size=4 color=blue> $block_id <br></font></td> </p>";
	  print "<fieldset font size=5> <legend> <font size=5 color=darkblue> <strong> Parcels: </legend></strong></font>";
      while ($row = mysql_fetch_assoc($q)) { 
        print "<form action='./surveys/?s=$surveyhash' method='post'>";
        print "<input type='hidden' name='surveyor_id' value='$surveyor_id'>";
        print "<input type='hidden' name='surveyor_name' value='$surveyor_name'>";
        print "<input type='hidden' name='block_id' value='$block_id'>";
        
        print "<input type='hidden' name='parcel_id' value='".$row["Parcel_ID"]."'>";
        print "<input type='hidden' name='parcel_address' value='".$row["Address"]."'>";
		
        print "<td>".$row["Address"]. "</td>";
        print "<td>................. <input type='submit' name='__prefill' value='Survey'></form> </td> ";
      }
		print "</fieldset>";
		
		
    } else print "Error! This block does not have any parcels assigned."; 
  } else print "Error! MySQL returned error <pre>".mysql_error()."</pre>";
	print "<br><a href= './select_block.php?surveyor_id=$surveyor_id'>CLICK HERE TO SELECT ANOTHER BLOCK</a>";
  } else print "You cannot go here!";







print "<br><button type='button' onClick='window.close();'>Close</button>\n"; 

?>

</td></tr></div>

	
 </body>
	
	
</html>
