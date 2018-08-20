<?php

session_start();

?>
<!DOCTYPE html>
<html>
    <head>
        <title>PHP HTML TABLE DATA SEARCH</title>
        
        <link rel="stylesheet" href="layout.css">  
    </head>
    <body>
    	<div id = "header"><h1>Add Borrower</h1></div>
        <div id = "navigation">
        	<ul>
                <li><a href="searchBook.php">Search a book</a></li>
                <li><a href="checkOut.html">Check out a book</a></li>
                <li><a href="checkIn.html">Check in a book</a></li>
                <li><a href="addBorrower.html">Add borrower</a></li>
                <li><a href="fines.php">Fines</a></li>
            </ul>
        </div>
        <div id = content>
<?php
if ((!empty($_POST['fname'])) && (!empty($_POST['lname'])) && (!empty($_POST['ssn'])) && (!empty($_POST['email'])) && (!empty($_POST['address'])) && (!empty($_POST['phone']))) {
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];	
	$ssn = $_POST['ssn'];	
	$email = $_POST['email'];	
	$address = $_POST['address'];
	$phone = $_POST['phone'];
  $len_ssn = strlen($ssn);
	$connect = mysqli_connect("localhost", "root", "Utdmysql", "library","3306");
  if ($len_ssn == 10 && is_numeric($ssn)){
    $ssn = preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "$1-$2-$3", $ssn);
    
    
  	$query = "select count(*) as ssn_count from BORROWER where ssn ='$ssn';";
  	$result = mysqli_query($connect,$query);
    	while($row = mysqli_fetch_array($result)){  
    		$count = $row['ssn_count'];
    	}
    	if ($count == 0) {
    		$query = "INSERT INTO BORROWER (ssn, first_name, last_name, email, address, phone) VALUES ('$ssn', '$fname', '$lname', '$email', '$address', '$phone');";
    		//echo $query;	 	
    	 	if (!mysqli_query($connect,$query)){ 
    	 		
    	 		echo "<h1>".mysqli_error($connect)."</h1>";
    	 		//die('Error: ' . mysqli_error($connect));
    	 	}
    	 	else{
    	 		echo "<h1>User successfully added!</h1>";
    	 	}

    	}
    	else{
    		echo "<h1>Borrower with the ssn, ".$ssn." already exists!</h1>";
    	}
  }
  else{
    echo "<h1>Ssn is invalid. It shold be of 10 digits. Please do not add -,/ or any other characters to it.</h1>";
  }

}
else{
	echo "<h1>Please enter all the values!</h1>";
}

mysqli_close($connect);
?>
		</div>
		</body>
		</html>
