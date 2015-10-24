<?php
    require_once("functions.php"); 
	
	if(isset($_SESSION['logged_in_user_id'])){
        header("Location: data.php");
		}
		
	// muuutujad errorite jaoks
	$email_error = "" ;
	$password_error = "" ;
	
	$email_2_error = "" ;
	$password_2_error = "" ;
	$age_error = "" ;
	$gender_error = "" ;
	
      //Muutujad väärtuste jaoks
	 $email = "";
	 $password = "";
	 $email_2 = "";
	 $password_2 = "";
	 $age = "";
	 $gender = "";
	 
	// kontrolli ainult siis, kui kasutaja vajutab "logi sisse" nuppu
	    if($_SERVER["REQUEST_METHOD"] == "POST"){
		
		//kontrollin kas muutuja $_POST["login"] ehk kas inimene tahab sisse logida
		if(isset($_POST["login"])){
			
			//kontrollime, et e-post ei oleks tühi		
			if(empty($_POST["email"])) { 
				$email_error = "Ei saa olla tühi";
			} else {
				//annan väärtuse
				$email = cleaninput($_POST["email"]);
			}
		
			//kontrollime parooli	
			if(empty($_POST["password"])) { 
				 $password_error = "Ei saa olla tühi";
			} else { 
			      $password = cleaninput($_POST["password"]);
			}
		  
			if($password_error == "" && $email_error == ""){
				//echo "Sisselogimine. Kasutajanimi on ".$email." ja parool on ".$password;
			   
				$hash = hash("sha512", $password);	
                loginUser($email, $hash);
            
            }
		}
		
				//See tuleks siit ära tõsta
				
        		$stmt = $mysqli->prepare("SELECT id, email FROM users WHERE email=? AND password=?");
				// küsimärkide asendus
				$stmt->bind_param("ss", $email, $hash);
				//ab tulnud muutujad
				$stmt->bind_result($id_from_db, $email_from_db);
				$stmt->execute();
				
				// teeb päringu ja kui on tõene (st et ab oli see väärtus)
				if($stmt->fetch()){
					
					// Kasutaja email ja parool õiged
					echo " Kasutaja logis sisse id=".$id_from_db;
					
				}else{
					echo "Valed andmed!";
				}
				
				$stmt->close();
               
			} 
		
		
		} 
		
		
		 if(isset($_POST["create"])) {
		
			if(empty($_POST["email_2"])) { 
				$email_2_error = "Ei saa olla täitmata";
			} else {
				  $email_2 = cleanInput($_POST["email_2"]);
				
			}
			
			if(empty($_POST["password_2"])) { 
				$password_2_error = "Ei saa olla täitmata";
		    } else {
				if(strlen($_POST["password_2"]) < 8) {
					$password_2_error = "Peab olema vähemalt 8 tähemärki pikk!";
				}else{
					$password_2 = cleanInput($_POST["password_2"]);
				}
			}
					
			if(empty($_POST["age"])) { 
				$age_error = "Ei saa olla tühi";
			} else {
				$age = cleanInput($_POST["age"]);
			}
			
			if(empty($_POST["gender"])) { 
				$gender_error = "Ei saa olla tühi";
			} else {
				$gender = cleanInput($_POST["gender"]);
			
			}
			
			
			if(	$email_2_error == "" && $password_2_error == "" && $age_error == "" && $gender_error == ""){
				//echo hash("sha512", $password_2);
				//echo " Kasutaja loomine. Kasutajanimi on ".$email_2." ja parool on ".$password_2.". Vanus on ".$age.". Sugu on ".$gender.".";
			
				$hash = hash("sha512", $password_2);
			// see asemele: 
			createUser($email_2, $hash, $age, $gender);	
				// see ka functions faili viia
				
				$stmt = $mysqli->prepare("INSERT INTO users (email, password, age, gender) VALUES (?,?,?,?)");
		    	$stmt->bind_param("ssss", $email_2, $hash, $age, $gender); //iga string on s
					
				//käivitab sisestuse
				$stmt->execute();
				$stmt->close();
					
			}
			
		}
			
	}	
     
	
		
	function cleanInput($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
	}
	
?>
<?php
 //lehe nimi
 $page_title = "Login leht";
 // faili nimi
 $page_file_name = "login.php"

?>
<?php require_once("header.php"); ?>
	
		<h2>Log in</h2>

	     <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
			<input name="email" type="email" placeholder="E-post" value="<?php echo $email; ?>"> <?php echo $email_error; ?><br><br>
			<input name="password" type="password" placeholder="parool" value="<?php echo $password; ?>"> <?php echo $password_error; ?><br><br>	
			<input type="submit" name="login" value="Log in">
		</form>
		
		<h2>Create user</h2>
	        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" >
			<input name="email_2" type="email" placeholder="E-post" value="<?php echo $email_2; ?>"> <?php echo $email_2_error; ?><br><br>
			<input name="password_2" type="password" placeholder="parool"> <?php echo $password_2_error; ?> <br> <br>
			<input name="age" type="text" placeholder="vanus"> <br> <br> 
			<input name="gender" type="text" placeholder="sugu mees/naine"> <br> <br> 
			<input type="submit" name="create" value="Create user"> 
		</form>	
		<body>
<html>
		
		
<?php
		//laeme footer.php faili sisu
		require_once("footer.php"); 
?>
		