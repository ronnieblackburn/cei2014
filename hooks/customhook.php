<?php
function redcap_survey_complete($project_id, $record, $instrument, $event_id, $group_id, $survey_hash, $response_id)
{
	if ($survey_hash == 'TETHKRLLDC')
	{
		$recorddata = REDCap::getData('array',$record,null,$event_id)[$record][$event_id];
		$surveyor_id = $recorddata["surveyor_id"];
		$block_id = $recorddata["block_id"];
		//header("Location: http://kc-is-redcap/select_parcel.php?surveyor_id=$surveyor_id&block_id=$block_id");
	}
}
?>