<?php

//loome AB �henduse
   require_once("../config_global.php");
   $database = "if15_merit26_1";
 
  function getAllData($keyword=""){
	  if($keyword== ""){
		  //ei otsi
		 $search = "%%"; 
	  } else {
		 	// otsime	 
		  $search = "%".$keyword."%";
	  }
				 			 
   			 $mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);  
			   
			   $stmt = $mysqli->prepare("SELECT training_id, user_id, begin, ending, sports, distance FROM training WHERE deleted IS NULL AND (sports LIKE ? OR distance LIKE ?)");
    		   $stmt->bind_param("ss", $search, $search);
			   $stmt -> bind_result($training_id_from_db, $user_id_from_db, $begin_from_db, $ending_from_db, $sports_from_db, $distance_from_db);
			   $stmt->execute();
				// massiiv, kus hoiame autosid
				$array = array();
				
				
				
				while($stmt->fetch()){
					//saime andmed k�tte
					//andmed saada transporditavale kujule
					
					// suvaline muutuja, kus hoiame andmeid massiivi lisamiseni
					$training = new StdClass();
				    $training-> training_id = $training_id_from_db;
					$training-> user_id = $user_id_from_db; 
					$training-> begin = $begin_from_db;
				    $training-> ending = $ending_from_db;
					$training-> sports = $sports_from_db;
				    $training-> distance = $distance_from_db;
					
					//lisan massiivi
					array_push($array, $training);
					
				}
				return $array;	
				   
					
	          $stmt->close(); 
		      $mysqli->close();      
		   } 
		   
  function deleteTrainingData($training_id){
	  
	 	     $mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);  		
             $stmt = $mysqli->prepare("UPDATE training SET deleted=NOW() WHERE training_id=?");
			 $stmt -> bind_param("i", $training_id);
			 $stmt->execute();
			 // t�hjendame aadressirea
			 header("Location: table.php");
             $stmt->close(); 
		     $mysqli->close();
	}
   function updateTrainingData($training_id, $begin, $ending, $sports, $distance) {			 
            
			$mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]); 
   
             $stmt = $mysqli->prepare("UPDATE training SET begin=?, ending=?, sports=?, distance=? WHERE training_id=?");
			 $stmt -> bind_param("ssssi", $begin, $ending, $sports, $distance, $training_id);
			 $stmt->execute();
			 // t�hjendame aadressirea
			 header("Location:table.php");
			 
             $stmt->close(); 
		     $mysqli->close();
     }   
	 
	 
?>