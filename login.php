<?php
	// See all errors and warnings
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);

	// Your database details might be different
	$mysqli = mysqli_connect("localhost", "root", "", "dbUser");

	$email = isset($_POST["email"]) ? $_POST["email"] : false;
	$pass = isset($_POST["pass"]) ? $_POST["pass"] : false;
?>

<!DOCTYPE html>
<html>
<head>
	<title>IMY 220 - Assignment 3</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css" />
	<meta charset="utf-8" />
	<meta name="author" content="Jonathan Bertram">
	<!-- Replace Name Surname with your name and surname -->
</head>
<body>
	<div class="container">
		<?php
			if($email && $pass){
				$query = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$pass'";
				$res = $mysqli->query($query);
				if($row = mysqli_fetch_array($res)){
					echo 	"<table class='table table-bordered mt-3'>
								<tr>
									<td>Name</td>
									<td>" . $row['name'] . "</td>
								<tr>
								<tr>
									<td>Surname</td>
									<td>" . $row['surname'] . "</td>
								<tr>
								<tr>
									<td>Email Address</td>
									<td>" . $row['email'] . "</td>
								<tr>
								<tr>
									<td>Birthday</td>
									<td>" . $row['birthday'] . "</td>
								<tr>
							</table>";

					echo 	"<form enctype='multipart/form-data' method='POST'>
								<div class='form-group'>
									<input name='email' type='hidden' value='$email' />
									<input name='pass' type='hidden' value='$pass' />
									<input name='picture' type='hidden' value='1' />
									<input type='file' class='form-control' name='piccy' id='piccy' /><br/>
									<input type='submit' class='btn btn-standard' value='Upload Image' name='submit1' />
								</div>
						  	</form>";

								if(isset($_POST["submit1"]) && isset($_POST["picture"]))
								{
									$email2 = $_POST["email"];
									$pass2 = $_POST["pass"];
									$picture = $_FILES["piccy"];
									$saveTo= "gallery/";

									if($picture["type"] == "image/jpeg"|| $picture["type"] == "image/jpg")
									{
										if($picture["size"] < 1000000)
										{
											move_uploaded_file($picture["tmp_name"], $saveTo . $picture["name"]);
										//	echo "Stored in: " . $saveTo . $picture["name"];
											$filename = $saveTo . $picture["name"];

											$query = "SELECT user_id FROM tbusers WHERE email = '$email2' AND password = '$pass2'";
											$res = $mysqli->query($query);
											if($row = mysqli_fetch_array($res)){

												//echo $row[0];

												$toSend = "INSERT INTO tbgallery (user_id, filename)
												VALUES ('$row[0]','$filename')";
												if(mysqli_query($mysqli, $toSend))
												{
													echo '<h2> Image Gallery </h2>
																	<div class= "row imageGallery">';

													$query2 = "SELECT filename FROM tbgallery WHERE user_id = '$row[0]'";
													$res2 = $mysqli->query($query2);
													while($row2 = mysqli_fetch_array($res2)){
														$url = $row2['filename'];
														echo "<div class='col-3' style='background-image: url($url)'></div>";
													}
													echo '</div>';
												}
											}
										}
										else{
											echo '<div class="alert alert-danger mt-3" role="alert">
							  							Your file is too large. Choose a file smaller than 1MB.
							  						</div>';
										}
									}
									else{
										echo '<div class="alert alert-danger mt-3" role="alert">
						  							Please select an image.
						  						</div>';
									}
								}
				}
				else{
					echo 	'<div class="alert alert-danger mt-3" role="alert">
	  							You are not registered on this site!
	  						</div>';
				}
			}
			else{
				echo 	'<div class="alert alert-danger mt-3" role="alert">
	  						Could not log you in
	  					</div>';
			}

			$mysqli->close();
      die();
		?>
	</div>
</body>
</html>
