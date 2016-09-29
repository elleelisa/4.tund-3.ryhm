<?php

	// functions.php
	session_start();

	$database = "if16_elleivan";

	//var_dump($GLOBALS);
	
	function signup($email, $password) {
		
	
		$mysqli = new mysqli(

		$GLOBALS["serverHost"],
		$GLOBALS["serverUsername"],
		$GLOBALS["serverPassword"],
		$GLOBALS["database"]
		
		);
              
		$stmt = $mysqli->prepare("INSERT INTO user_sample (email, password) VALUES (?, ?)");
		echo $mysqli->error;

		$stmt->bind_param("ss", $email, $password );

		if ( $stmt->execute() ) {
			echo "salvestamine õnnestus";
		} else {
			echo "ERROR ".$stmt->error;	

		}
		
		
		
	}
	

	function login($email, $password) {

		$notice = "";

		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);

		$stmt = $mysqli->prepare("SELECT id, email, password, created FROM user_sample WHERE email = ?");

		//asendan ?
		$stmt->bind_param("s", $email);

		// määran muutujad reale mis kätte saan
		$stmt->bind_result($id, $emailFromDb, $passwordFromDb, $created);
		
		$stmt->execute();
		//ainult SELECTI puhul
		if ($stmt->fetch()) {

			//vähemalt üks rida tuli
			//kasutaja sisselogimis parool räsiks
			$hash = hash("sha512", $password);
			if($hash == $passwordFromDb) {

				// õnnestus
				echo "Kasutaja ".$id." logis sisse";

				$_SESSION["userId"] = $id;
				$_SESSION["userEmail"] = $emailFromDb;

				header("Location: data.php");
				

			} else {

				$notice = "Vale parool!";
			}


		} else {

			//ei leitud ühtegi rida
			$notice = "Sellist emaili ei ole!";
		}

		return $notice;
	}


	/*function sum($x, $y) {
		$answer = $x+$y;
	
		return $answer;
	}
	
	function hello($firstname, $lastname) {
	
		return "Tere Tulemast ".$firstname." ".$lastname."!";
	}
	
	
	echo sum(123467162, 16235173476);
	echo "<br>";
	echo sum(1,2);
	echo "<br>";
	echo hello("Elle", "I.");
	*/
?>