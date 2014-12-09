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
define("SURVEYOR_PID", 188); #PROJECT ID OF "SURVEYOR INFORMATION" REDCAP PROJECT

# Assuming the code in on the same level as redcap directory 
require_once './common_api_funcs.php'; 	# library posted on wiki 

define("NOAUTH", true); 	# No need to authenticate - called as Project Bookmark only 
require_once './redcap_connect.php'; # for Plugin 

# Check that legit request 
if (isset($_POST["authkey"]) && isset($_GET["pid"])) { 
  $pid = $_GET["pid"];     	# retrieves current project_id (number)
  $data = array('authkey' => $_POST["authkey"], 'format' => 'csv'); 
  $result = getAPI($data,'http://localhost/redcap/api/',false); # Check that bookmark authenticates 
  if ($result) { # Success! Check if $record is valid (has data in [block_id]) 
    # NOTE: Using deprecated mysql_* functions.  Change to mysqli_* for newer PHP 
    $sql = "SELECT record, value FROM redcap_data WHERE project_id = ".SURVEYOR_PID." 
            AND field_name = 'surveyor_name'"; #Pulls list of student surveyors from REDCap project Surveyor Information
    if($q = mysql_query($sql)) { 
      if (mysql_num_rows($q)) { 	# value for [block_id] set - can proceed 
        # Let's make the simplest page 
        print "<div><form action='./select_area.php' method='get'>\n ";
        print "<td><b>Select surveyor : </td><td> <select name='surveyor_id'>\n </td> </div>"; 
        while ($row = mysql_fetch_assoc($q)) { 
          print "<option value='".$row["record"]."'> ".$row["value"]."</option>\n"; 
        } 
        print "<br></br></select>		<br><br><input type='submit' value='Submit'> </br>\n</form></br></br>";
      } else print "Error! Couldn't fetch MySQL rows."; 
    } else print "Error! MySQL returned error <pre>".mysql_error()."</pre>";
  } else print "Error! Couldn't authenticate project bookmark!"; 
} else print "You cannot go here!"; 
print " <td><button type='button'  onClick='window.close();'>Close</button>\n </td>"; 

?>
</td></tr></div>

	
 </body>
	
	
</html>