<?php 
	include_once '../controllers/function.php';
	$target_dir = "../temp/";
	$target_file = $target_dir . basename($_FILES["markUpload"]["name"]);
	$uploadOk = 1;
	if (file_exists($target_file)) {
	    $uploadOk = 0;
	}

	if ($_FILES["markUpload"]["size"] > 500000) {
	    $uploadOk = 0;
	}

	if ($uploadOk == 0) {
	} else {
	    if (move_uploaded_file($_FILES["markUpload"]["tmp_name"], $target_file)) {
	    	default_message_process($target_file);
	    	delete_target_file($target_file);
	    	header("Location: mark_upload.php");
	    } else {
	    	echo "Error in inserting";
	    }
	}


	function default_message_process($target_file){
		$link = db_connect();
		require('../php-excel-reader/excel_reader2.php');
		require('../SpreadsheetReader.php');
		echo "$target_file<br/><pre>";
		$Reader = new SpreadsheetReader($target_file);
		$i = 0;
		foreach ($Reader as $Row) {
			if($i == 0){
				$i++;
				continue;
			} else{
				$message_content = "Name of the exam :- ".$_POST['name_of_exam'].", Class :- ".$_POST['class'].", Section :- ".$_POST['section'].", ".$Row['1']." Scored Tamil :- ".$Row['2'].", English :- ".$Row['3'].", Physics :- ".$Row['4'].", Chemistry :- ".$Row['5'].", Bio/CS :- ".$Row['6'].", Mat :- ".$Row['7'].", Total :- ".$Row['8'].", Result :- ".$Row['9'];
				$sql = "INSERT INTO `MessageOut` (`MessageTo` , `MessageText`) VALUES ('$Row[10]', '$message_content')";
				mysqli_set_charset($link, 'utf8mb4'); 
				executeQuery($sql, $link);
			}
		}
	}

	function db_connect(){
                $connection = mysqli_connect('103.207.4.235', "vani", "vani1234", "vani", 3306);
                if (!$connection) {
                    die("Connection failed: " . mysqli_connect_error());
                    exit();
                }
                return $connection;
    }


	function delete_target_file($path){
		if (!unlink($path))
		{
		  echo ("Error deleting $path");
		}
		else
		{
		  echo ("Deleted $path");
		}
	}
