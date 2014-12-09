<?php
#############################################################################
## CODE AUTHORS: THACH BUI, RONNIE BLACKBURN                               ##
## CENTER FOR HEALTH INSIGHTS, UNIVERSITY OF MISSOURI-KANSAS CITY          ##
## CREATED IN 2014 FOR CENTER FOR ECONOMIC INFORMATION NEIGHBORHOOD SURVEY ##
#############################################################################
?>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="Style_Import/style.css"/>	
        <link rel="stylesheet" media="all" type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
        <link rel="stylesheet" media="all" type="text/css" href="jquery-ui-timepicker-addon.css" />
        <title>Survey</title>
        <p>
		<img src="redcap.png" alt="redcao" width="62" height="62" align="center">
		
		</p>
    </head>
    <body>
	
	<style> 
	@media only screen {
	body
		{
		zoom: 120%;
		font-size: 16px ;
		
		display:table-cell;
		vertical-align: middle;
		text-align: center;
		}
		}
		</style>
	
	
		
	<p></br><legend ><font size=10 color=red align=center  > <td><strong> Welcome to CEI Admin Portal </legend></strong></font></td></p>
	
<?php
define("PROJECT_ID", 189);

$row_records=array();

# Assuming the code in on the same level as REDCap directory 
require_once './common_api_funcs.php'; 	# library posted on wiki 
define("NOAUTH", true); 	# No need to authenticate - called as Project Bookmark only 
require_once './redcap_connect.php'; # for Plugin 
# Check that legit request 
# 
# get the array of Record in REDCap
$sql_row = "select record,field_name,value from redcap_data WHERE project_id=".PROJECT_ID."";
$result_row= mysqli_query($conn,$sql_row);

$num_records = 0;

while($row = mysqli_fetch_assoc($result_row)) {
    	
	$row_records[$row['record']][$row['field_name']] = $row['value'];
	$num_records++;
			

    
}
$temp= array();

# display records
echo "<table border='1'>
			<tr>
			<th>Surveyor Name</th>
			<th>Surveyor ID</th>
			<th>Block ID</th>
			<th>Parcel Address</th>
			<th>Structure Type</th>
			<th>Record ID</th>
			</tr> ";

    foreach ($row_records as $record =>$field_names)
    {
            echo "<tr>";
		echo "<td>" . $row_records[$record]['surveyor_name']. "</td>";
		echo "<td>" . $row_records[$record]['surveyor_id']. "</td>";
		echo "<td>" . $row_records[$record]['block_id']. "</td>";
		echo "<td>" . $row_records[$record]['parcel_address']. "</td>";
		echo "<td>" . $row_records[$record]['structure_type']. "</td>";
		echo "<td>" . $row_records[$record]['record_id']. "</td>";
            echo "</tr>";

            $temp[$record] = $field_names['parcel_address'];

                
    }
    
    
echo"</table>";
# for each record, add the array of row_record

$dup_array=array();
$duplication_r = false;
foreach ($row_records as $record =>$field_names)
{
	$i = 0;
	$duplication= false;
		foreach($temp as $key => $value) {
		
			if($key != $record && $row_records[$record]['parcel_address'] == $value) {
				$i++;
				#echo $row_records[$record]['parcel_address'];
				$duplication = true;
				#echo "<p><b> $key </p></b>";
				$dup_array[$record][$key]= $value;
			}
			if ($i > 0 )
			{
				
			}
		}
	
	if($duplication){
			$duplication_r =true;
			echo "<table border='1'>
			<tr>
			<th>Surveyor Name</th>
			<th>Surveyor ID</th>
			<th>Block ID</th>
			<th>Parcel Address</th>
			<th>Structure Type</th>
			<th>Record ID</th>
			</tr> ";
		
    		foreach ($dup_array as $keys => $values) {
			echo "<p><b>Parcel's Record_ID number <font color=red> $keys </font>has total:<font color=red> $i </font> duplications:</p></b>";
			echo "<tr>";
			echo "<td>" . $row_records[$keys]['surveyor_name']. "</td>";
			echo "<td>" . $row_records[$keys]['surveyor_id']. "</td>";
			echo "<td>" . $row_records[$keys]['block_id']. "</td>";
			echo "<td>" . $row_records[$keys]['parcel_address']. "</td>";
			echo "<td>" . $row_records[$keys]['structure_type']. "</td>";
			echo "<td>" . $row_records[$keys]['record_id']. "</td>";
			
			echo "</tr>";
			foreach($values as $key=>$values)
			{
				echo "<tr>";
				echo "<td><b>" . $row_records[$key]['surveyor_name']. "</b></td>";
				echo "<td><b>" . $row_records[$key]['surveyor_id']. "</b></td>";
				echo "<td><b>" . $row_records[$key]['block_id']. "</b></td>";
				echo "<td><b>" . $row_records[$key]['parcel_address']. "</b></td>";
				echo "<td><b>" . $row_records[$key]['structure_type']. "</b></td>";
				echo "<td><b><font color=red>" . $row_records[$key]['record_id']. "</font></b></td>";
				echo "</tr>";
			}
			
			echo "</table>";
			break;
		}
		# echo "<p><b>The last record is not related </p></b>";
		break;
		
	}
	
	
	
}

if(!$duplication_r) {
    if ($_POST["submit"] != "Submit")
	{
		echo "<br> No duplicate records detected; if this data is ready for import then please submit. <br>";
		echo "<form action='' method='post'><input type='submit' name='submit' value='Submit'></form>";
	}
	else
	{
		$Redcap_record='0';

		$Is_visible_address='0'; 

		$address_modified="";
		$verified_address='1';


		$Use_Type='5'; 
		$Residential_Type='6';
		$Structure_Profile='6';

		$done_rating ="";

		#set default

		$Cond_Roof= '6';  
		$Cond_Foundations_Walls=$Cond_Windows_Doors=$Cond_Porches=$Cond_Ext_Paint ='6'; 
					
		#start to import
		   foreach ($row_records as $record =>$field_names)
		   {
						#import and modify variables
						 $Redcap_record= $field_names['record_id'];
						 
						 $date = $field_names['date'];
						 $primary_surveyor =$field_names['surveyor_name'];
						 $secondary_surveyor =$field_names['surveyor_name_2'];
						 
						 $Is_visible_address = $field_names['address_visible'];
						 $verified_address = $field_names['addresses_same'];
						
						 
						 if($Is_visible_address== '1' && $verified_address == '0')
						{
							$address_modified = $field_names['corrected_address'];
							#echo "<p><b>$Is_visible_address and $verified_address </p></b>";
						
						}
						else 
						{
							$address_modified ="";
						}
						 
						 $Structure_Type = $field_names['structure_type'];
						 if ($Structure_Type == '1'){ $done_rating="Residential";}
						 elseif ($Structure_Type == '2') { $done_rating = "Non_Residential";}
						 elseif ($Structure_Type == '3'){ $done_rating="Vacant Lot"; }
						 elseif ($Structure_Type == '4') {  $done_rating= "Parking Lot"; }
						 elseif ($Structure_Type == '5') { $done_rating ="Park"; }
						 else { $done_rating ="Residential Commons"; }
						
						# echo "<p><b>$Structure_Type and".$done_rating."  </p></b>";  
							
						 
						 if($Structure_Type =='1') { 
							$Use_Type = $field_names['use_type'];
							$Residential_Type = $field_names['residential_type'];
						 }
						 
					   
						 
						 if($Structure_Type =='1' || $Structure_Type =='2') { $Structure_Profile = $field_names['structure_profile'];}
						 
						 #Structural condition
						 if($Structure_Type == '1' || $Structure_Type == '2'){ $Cond_Roof = $field_names['rating_roof'];}
						 
						 if($Structure_Type != '3') { $Cond_Foundations_Walls = $field_names['rating_foundations'];}
						  
						 if($Structure_Type == '1' || $Structure_Type == '2') { $Cond_Windows_Doors =$field_names['rating_windows'];}
						 
						 if($Structure_Type == '1' ) { $Cond_Porches = $field_names['rating_porches'];}
						 
						 if($Structure_Type != '3' && $Structure_Type != '4') { $Cond_Ext_Paint = $field_names['rating_ext_paint']; }
						 
						 #ground condition
						 
						 $Cond_Priv_Sidewalks = $field_names['rating_private_walks'];
						 $Cond_Lawn = $field_names['rating_lawns'];
						 $Cond_Vehicles = $field_names['rating_vehicles'];
						 $Cond_Litter = $field_names['rating_litter'];
						 $Cond_Storage = $field_names['rating_storage'];
						 
						 #public infrastructure condition
						 
						 $Cond_Pub_Sidewalks = $field_names['rating_public_walks'];
						 $Cond_Curbs = $field_names['rating_curbs'];
						 $Cond_Streetlights = $field_names['rating_streetlights'];
						 $Cond_Basins = $field_names['rating_basins'];
						 $Cond_Street = $field_names['rating_street'];
						 
						 
						# query to the housing data
					/*	#This query will up date the new address that modified 
					
						if ($Is_visible_address== '1' && $verified_address == '0')
						{
			$query = "UPDATE parcels_lookup SET Last_survey_date='".$date."', Record_ID = '".$Redcap_record."', Primary_surveyor= '".$primary_surveyor."', secondary_surveyor= '".$secondary_surveyor."',
										is_address_visible=".$Is_visible_address.", address= '".$verified_address."' , address_modified= '".$address_modified."',
										Structure_Type= ".$Structure_Type.", done_rating = '$done_rating' ,
										Use_Type= ".$Use_Type.", Residential_Type=".$Residential_Type.", Structure_Profile= ".$Structure_Profile.",
										Cond_Roof= ".$Cond_Roof.", Cond_Foundations_Walls = ".$Cond_Foundations_Walls.", Cond_Windows_Doors = ".$Cond_Windows_Doors.", Cond_Porches='".$Cond_Porches."', Cond_Ext_Paint = '".$Cond_Ext_Paint."', 
										Cond_Priv_Sidewalks= '".$Cond_Priv_Sidewalks."', Cond_Lawn = '".$Cond_Lawn."', Cond_Vehicles= '".$Cond_Vehicles."', Cond_Litter='".$Cond_Litter."', Cond_Storage='".$Cond_Storage."',
										Cond_Pub_Sidewalks= '".$Cond_Pub_Sidewalks."', Cond_Curbs = '".$Cond_Curbs."', Cond_Streetlights = '".$Cond_Streetlights."', Cond_Basins = '".$Cond_Basins."', Cond_Street= '".$Cond_Street."'
										WHERE Parcel_ID ='".$field_names['parcel_id']."'";			
						
						}
						*/
						
						
			$query = "UPDATE parcels_lookup SET Last_survey_date='".$date."', Record_ID = '".$Redcap_record."', Primary_surveyor= '".$primary_surveyor."', secondary_surveyor= '".$secondary_surveyor."',";
			if ( $is_address_visible != '' )
				$query .= "is_address_visible='".$Is_visible_address."',";
			if ( $correct_address != '' )
				$query .= " correct_address= '".$verified_address."',";
			$query.= "address_modified= '".$address_modified."',
						Structure_Type=".$Structure_Type.", done_rating = '$done_rating' ,";
			if ( $Use_Type != '' )
				$query .= "Use_Type='".$Use_Type."',";
			if ( $Residential_Type != '' )
				$query .= "Residential_Type='".$Residential_Type."',";
			if ( $Structure_Profile != '' )
				$query .= "Structure_Profile='".$Structure_Profile."',";
			if ( $Cond_Roof != '' )
				$query .= "Cond_Roof='".$Cond_Roof."',";
			if ( $Cond_Foundations_Walls != '' )
				$query .= "Cond_Foundations_Walls ='".$Cond_Foundations_Walls."',";
			if ( $Cond_Windows_Doors != '' )
				$query .= "Cond_Windows_Doors ='".$Cond_Windows_Doors."',";
			if ( $Cond_Porches != '' )
				$query .= "Cond_Porches='".$Cond_Porches."',";
			if ( $Cond_Ext_Paint != '' )
				$query .= "Cond_Ext_Paint = '".$Cond_Ext_Paint."',";
			$query .= "Cond_Priv_Sidewalks= '".$Cond_Priv_Sidewalks."', Cond_Lawn = '".$Cond_Lawn."', Cond_Vehicles= '".$Cond_Vehicles."', Cond_Litter='".$Cond_Litter."', Cond_Storage='".$Cond_Storage."',
						Cond_Pub_Sidewalks= '".$Cond_Pub_Sidewalks."', Cond_Curbs = '".$Cond_Curbs."', Cond_Streetlights = '".$Cond_Streetlights."', Cond_Basins = '".$Cond_Basins."', Cond_Street= '".$Cond_Street."'
						WHERE Parcel_ID ='".$field_names['parcel_id']."'";
										
							
										
			# PRINT  "<p><b>".$query. "</p></b>";
		   
			 mysqli_query($conn,$query);
			
			# echo"done! ".$field_names['parcel_id'].$field_names['batch_id']."";

					}
		 
		 # This will display when data import sucessfully
		echo "<p><b>Congratulations! Data has NO duplications, and it has been imported into Parcels_lookup table!</p></b>";
	}
} #this will be executed when there is no duplications.


	print "<br><button type='button' onClick='window.close();'>Close</button></br>"; 
?>

</td></tr></div>

	
 </body>
	
	
</html>
