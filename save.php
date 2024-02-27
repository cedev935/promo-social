<?php
require 'config.php';

if (isset($_POST['jsonData'])) {
	# code...

	$jsonData = $_POST['jsonData'];

	//var_dump($jsonData);
	$jsonData = json_decode($jsonData, true);
	$data_json = file_get_contents(datafile);
	$data = json_decode($data_json, true);
	$exist = 0;
	for ($i = 0; $i < count($data); $i++) {
		# code...
		if ($data[$i]['email'] == $jsonData['email']) {
			if (strpos($jsonData['email'], "@")) {
				$jsonData['birthday'] = $data[$i]['birthday'];
				$data[$i] = $jsonData;
				$exist++;
				break;
			}
		} elseif (
			$data[$i]['number'] == $jsonData['number']
		) {
			if (preg_match("/[0-9]/", $jsonData['number'])) {
				$jsonData['birthday'] = $data[$i]['birthday'];
				$data[$i] = $jsonData;
				$exist++;
				break;
			}
		}
	}
	if ($exist == 0) {
		$data[count($data)] = $jsonData;
	} else {
		echo 'update';
	}
	$data_json = json_encode($data);
	//var_dump($data_json);
	file_put_contents(datafile, $data_json);
}

if (isset($_POST['userbook'])) {
	# code...

	$jsonData = $_POST['userbook'];

	//var_dump($jsonData);
	$jsonData = json_decode($jsonData, true);
	$data_json = file_get_contents('db/bookings.json');
	$data = json_decode($data_json, true);
	$exist = 0;
	for ($i = 0; $i < count($data); $i++) {
		# code...
		if ($data[$i]['email'] == $jsonData['email']) {
			if (strpos($jsonData['email'], "@")) {
				$jsonData['birthday'] = $data[$i]['birthday'];
				$data[$i] = $jsonData;
				$exist++;
				break;
			}
		} elseif (
			$data[$i]['number'] == $jsonData['number']
		) {
			if (preg_match("/[0-9]/", $jsonData['number'])) {
				$jsonData['birthday'] = $data[$i]['birthday'];
				$data[$i] = $jsonData;
				$exist++;
				break;
			}
		}
	}
	if ($exist == 0) {
		$data[count($data)] = $jsonData;
	} else {
		echo 'update';
	}
	$data_json = json_encode($data);
	//var_dump($data_json);
	file_put_contents('db/bookings.json', $data_json);
}
if (isset($_POST['jsonDatAdmin'])) {
	# code...
	$jsonData = $_POST['jsonDatAdmin'];

	$fileName = datafile;

	$file = fopen($fileName, 'w');

	fwrite($file, $jsonData);

	fclose($file);
}


if (isset($_POST['template'])) {
	# code...
	$template = $_POST['template'];

	//var_dump($jsonData);
	$template = json_decode($template, true);

	$data_json = file_get_contents('db/templates.json');
	$data = json_decode($data_json, true);

	function idUniq($data, $id)
	{
		for ($i = 0; $i < count($data); $i++) {
			# code...
			$exist = 0;
			if ($data[$i]['id'] == $id) {

				$exist++;
				break;
			}
		}
		if ($exist) {
			$id++;
			return idUniq($data, $id);
		} else {
			return $id;
		}
	}
	$template['id'] = idUniq($data, $template['id']);
	$data[count($data)] = $template;
	//var_dump($data, $template['id']);
	$data_json = json_encode($data);
	file_put_contents('db/templates.json', $data_json);
}

if (isset($_POST['deleteTemplate'])) {
	# code...
	$jsonData = $_POST['deleteTemplate'];

	$fileName = 'db/templates.json';

	$file = fopen($fileName, 'w');

	fwrite($file, $jsonData);

	fclose($file);
}


//campaign


if (isset($_POST['campaign'])) {
	# code...
	$campaign = $_POST['campaign'];

	//var_dump($jsonData);
	$campaign = json_decode($campaign, true);

	$data_json = file_get_contents('db/campaign.json');
	$data = json_decode($data_json, true);

	function idUniq($data, $id)
	{
		for ($i = 0; $i < count($data); $i++) {
			# code...
			$exist = 0;
			if ($data[$i]['id'] == $id) {

				$exist++;
				break;
			}
		}
		if ($exist) {
			$id++;
			return idUniq($data, $id);
		} else {
			return $id;
		}
	}
	$campaign['id'] = idUniq($data, $campaign['id']);
	$data[count($data)] = $campaign;
	//var_dump($data, $template['id']);
	$data_json = json_encode($data);
	file_put_contents('db/campaign.json', $data_json);
}

if (isset($_POST['deleteCampaign'])) {
	# code...
	$jsonData = $_POST['deleteCampaign'];

	$fileName = 'db/campaign.json';

	$file = fopen($fileName, 'w');

	fwrite($file, $jsonData);

	fclose($file);
}



//booking

if (isset($_POST['booking'])) {
	# code...
	$booking = $_POST['booking'];

	//var_dump($jsonData);
	$booking = json_decode($booking, true);
 var_dump($booking);
	$data_json = file_get_contents('db/services.json');
	$data = json_decode($data_json, true);
	$booking['id'] = 1;
	$lastbooking = $data[0];
	$lastservices = $lastbooking['services'];
	$services = ($booking['services']);
	$data[0] = $booking;
	$nameservices = [];
	foreach ($services as $key => $service) {
		# code...
		if(in_array($service['name'],$nameservices))
		{
			unset($services[$key]);
		}else{
			array_push($nameservices,$service['name']);
		}
		
	}

	$data_json = json_encode($data);
	file_put_contents('db/services.json', $data_json);
}

//userbookings
if (isset($_POST['deleteBooking'])) {
	# code...
	$jsonData = $_POST['deleteBooking'];

	$fileName = 'db/bookings.json';

	$file = fopen($fileName, 'w');

	fwrite($file, $jsonData);

	fclose($file);
}



if (isset($_POST['token'])) {
	# code...

	$lead = json_decode($_POST['token'], true);

	//var_dump($jsonData);
	$data_json = file_get_contents(datafile);
	$data = json_decode($data_json, true);
	$exist = 0;
	for ($i = 0; $i < count($data); $i++) {
		# code...
		if ($data[$i]['id'] == $lead['id']) {

			$data[$i] = $lead;
			break;
		}
	}

	$data_json = json_encode($data);
	file_put_contents(datafile, $data_json);
}
if (isset($_POST['birth'])) {
	# code...

	$lead = json_decode($_POST['birth'], true);

	//var_dump($jsonData);
	$data_json = file_get_contents(datafile);
	$data = json_decode($data_json, true);
	$exist = 0;
	for ($i = 0; $i < count($data); $i++) {
		# code...
		if ($data[$i]['id'] == $lead['id']) {

			$data[$i] = $lead;
			break;
		}
	}

	$data_json = json_encode($data);
	file_put_contents(datafile, $data_json);
}
