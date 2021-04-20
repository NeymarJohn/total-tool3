<?php
$wondertage_settings = array();
if (mysqli_query($wo['sqlConnect'], "DESCRIBE `wondertage_settings`" )) {
	$settings = $db->get('wondertage_settings');
	foreach ($settings as $key => $value) {
		$wondertage_settings[$value->name] = $value->value;
	}
}
$data = array('status' => 400,
              'error' => $wo['lang']['please_check_details']);
if (Wo_IsAdmin() || Wo_IsModerator()) {
	if ($s == 'install') {
		if (!mysqli_query($wo['sqlConnect'], "DESCRIBE `wondertage_settings`")) {
			// mysqli_query($wo['sqlConnect'],"CREATE TABLE `wondertage_settings` ( `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(100) NOT NULL DEFAULT '', `value` varchar(20000) NOT NULL DEFAULT '', PRIMARY KEY (`id`), KEY `name` (`name`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;");
			$filename = 'wondertag.sql';
	        // Temporary variable, used to store current query
	        $templine = '';
	        // Read in entire file
	        $lines = file($filename);
	        // Loop through each line
	        foreach ($lines as $line) {
	           // Skip it if it's a comment
	           if (substr($line, 0, 2) == '--' || $line == '')
	              continue;
	           // Add this line to the current segment
	           $templine .= $line;
	           $query = false;
	           // If it has a semicolon at the end, it's the end of the query
	           if (substr(trim($line), -1, 1) == ';') {
	              // Perform the query
	              $query = mysqli_query($wo['sqlConnect'], $templine);
	              // Reset temp variable to empty
	              $templine = ''; 
	           }
	        }

			$data = array('status' => 200,
		                  'message' => "Settings installed");
		}
		else{
			$data = array('status' => 400,
			              'error' => $wo['lang']['please_check_details']);
		}
	}
	if ($s == 'update') {
		foreach ($_POST as $key => $value) {
			if (in_array($key, array_keys($wondertage_settings))) {
				if (empty($value)) {
					$value = 0;
				}
				$update_name = Wo_Secure($key);
			    $value       = mysqli_real_escape_string($sqlConnect, $value);
			    $query_one   = " UPDATE `wondertage_settings` SET `value` = '{$value}' WHERE `name` = '{$update_name}'";
			    $query       = mysqli_query($sqlConnect, $query_one);
			}
		}
		$data = array('status' => 200,
		              'message' => "Settings updated");
	}
}
header("Content-type: application/json");
echo json_encode($data);
exit();