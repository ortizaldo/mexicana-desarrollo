<?php
		include_once 'DAO.php';
		include_once "libs/utils.php";

		session_start();

		if( !isset($_SESSION["nickname"]) ) {
				//return error message. There´s no session for that user
		}

		$DB = new DAO();
		$conn = $DB->getConnect();

		//print_r($conn);
		//exit;

		extract($_POST);

		$dir="../uploads/";

		if( !file_exists($dir) ) {
			mkdir($dir, 0777, true);
		}

		$fileName="";

		$idReport=0;

		$element1=$_POST["element1"];
		$element2=$_POST["element2"];
		$element3=$_POST["element3"];
		$element4=$_POST["element4"];
		$element5=$_POST["element5"];
		$element6=$_POST["element6"];
		$element7=$_POST["element7"];
		$element8=$_POST["element8"];
		$element9=$_POST["element9"];
		$element10=$_POST["element10"];

		$AuToken="";

		$extension=array("jpeg","jpg","png","gif");
		$error=array();

		$files_id=array();
		$returnData=array();

		foreach($_FILES["files"]["tmp_name"] as $key=>$tmp_name) {
				$file_name=$_FILES["files"]["name"][$key];

				$x = 5; // Amount of digits
				$min = pow(10,$x);
				$max = pow(10,($x+1)-1);
				$value = rand($min, $max);

				$file_name = time().$value.'-'.str_replace("", "_", $file_name);
				$file_tmp=$_FILES["files"]["tmp_name"][$key];
				$type=$_FILES["files"]["type"][$key];
				$size=$_FILES["files"]["size"][$key];
				$ext=pathinfo($file_name, PATHINFO_EXTENSION);

				/*echo "<pre>";
					print_r($_FILES["files"]);
				echo "</pre>";
				exit;*/

				if( in_array($ext, $extension) ) {
						//echo "entering to in_array";
						//echo $dir.$file_name;
						if(!file_exists($dir.$file_name))
						{
								move_uploaded_file($file_tmp=$_FILES["files"]["tmp_name"][$key], $dir.$file_name);
								//echo "move_uploaded";
								$stmnMultimediaInsert = 'INSERT INTO multimedia(content, name, extension, type, size, created_at, updated_at)
								VALUES("'. $dir.$file_name .'", "'. $file_name .'", "' . $ext . '" , "' . $type . '", "' . $size . '" , NOW(), NOW());';

								/*echo "<br/>";
									echo $stmnMultimediaInsert;
								echo "<br/>";*/

								$conn->query($stmnMultimediaInsert);
								$files_id[]=$conn->insert_id;
								/*echo "ID uploaded" . $conn->insert_id;

								echo "<pre>";
									print_r($files_id);
								echo "</pre>";
								exit;*/
						}
						else
						{
								$filename=basename($file_name,$ext);
								$newFileName=$filename.time().".".$ext;
								move_uploaded_file($file_tmp=$_FILES["files"]["tmp_name"][$key], $dir.$file_name);

								$stmnMultimediaInsert = 'INSERT INTO multimedia(content, name, extension, type, size, created_at, updated_at)
								VALUES("'. $dir.$file_name .'", "'. $file_name .'", "' . $ext . '" , "' . $type . '", "' . $size . '" , NOW(), NOW());';

								/*echo "<br/>";
									echo $stmnMultimediaInsert;
								echo "<br/>";*/

								$conn->query($stmnMultimediaInsert);
								$files_id[]=$conn->insert_id;
						}
						/*echo "<pre>";
							print_r($files_id);
						echo "</pre>";
						exit;*/
				} else {
						array_push($error, "$file_name, ");
				}
		}

		if( isset($files_id) && $files_id != NULL ) {

			if( isset($element1) || ($element1) && $element1!= NULL || $element1 != '' ) {
					$stmnReport = 'INSERT INTO report(name, description, description2, description3, description4, description5, 
							description6, description7, description8, description9, num, idEmployee, idReportType, dot_latitude, dot_longitude, created_at, updated_at) 
						VALUES ("'. $element1 .'", "'. $element2 .'", "'. $element3 .'", "'. $element4 .'", "'. $element5 .'", "'. $element6 .'", "'. $element7 .'", 
						"'. $element8 .'", "'. $element9 .'" , "'. $element10 .'", 3532, 1, 1, 132.352342, 23.234152, NOW(), NOW());';

					/*echo "<br/>";
						echo $stmnReport;
					echo "<br/>";*/

					$conn->query($stmnReport);
					$idReport = $conn->insert_id;
					//printf ("New Record User has id %d.\n", $conn->insert_id);
			}

			/*echo "<pre>";
				print_r($files_id);
			echo "</pre>";*/

			foreach( $files_id as $key ) {

					/*echo "<br/>";
						echo $key;
					echo "<br/>";*/

					$stmnReportMultimedia = 'INSERT INTO reportMultimedia(idReport, idMultimedia, created_at, updated_at)
							VALUES ("'. $idReport .'", "'. $key .'", NOW(), NOW());';

					//echo $stmnReportMultimedia;

					$conn->query($stmnReportMultimedia);

					//printf ("New Record Location has id %d.\n", $conn->insert_id);
			}

			$data=array();

			$data["status"] = 200;
			$data["response"] = "OK";

			$returnData[]=$data;

			echo json_encode($returnData);
		}
?>

<!DOCTYPE>
<html>
	<head>
		<title></title>
	</head>
	<body>
		<h1>Create Report</h1>
			<form method="POST" action='<?=$_SERVER['PHP_SELF']?>' enctype="multipart/form-data">
				<div id="">
					<p>element1</p>
					<input type="text" name="element1" placeholder="element1" required></input>
					<p>element2</p>
					<input type="text" name="element2" placeholder="element2" required></input>
					<p>element3</p>
					<input type="text" name="element3" placeholder="element3" required></input>
					<p>element4</p>
					<input type="text" name="element4" placeholder="element4" required></input>
					<p>element5</p>
					<input type="text" name="element5" placeholder="element5" required></input>
					<p>element6</p>
					<input type="text" name="element6" placeholder="element6" required></input>
					<p>element7</p>
					<input type="text" name="element7" placeholder="element7" required></input>
					<p>element8</p>
					<input type="text" name="element8" placeholder="element8" required></input>
					<p>element9</p>
					<input type="text" name="element9" placeholder="element9" required></input>
					<p>Fotograf&iacute;as</p>
					<td>Select Photo (one or multiple):</td>
						<td><input type="file" name="files[]" multiple/></td>
					<p>element10</p>
					<input type="text" name="element10" placeholder="element10" required></input>
				</div>
				<br/><br/>
				<input type="submit" value="Insert"></input>
			</form>
	</body>
</html>