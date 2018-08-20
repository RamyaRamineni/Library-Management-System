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
    	<div id = "header"><h1>checking out a book</h1></div>
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

  $isbn = $_POST['isbn'];
  $borrowerId = $_POST['borrowerId'];
  $today = date("m-d-Y");
  $plus = strtotime("+14 day", time());
  $estimate = date('m-d-Y', $plus);
  $connect = mysqli_connect("localhost", "root", "Utdmysql", "library","3306"); 
  $query = "select count(*) as count1 from book where isbn ='$isbn';";  
  $result = mysqli_query($connect,$query);	
  while($row = mysqli_fetch_array($result)){  
  		
  		$count1 = $row['count1'];
  	}
  $query = "select count(*) as count2 from borrower where card_id ='$borrowerId';";  
  $result = mysqli_query($connect,$query);	
  while($row = mysqli_fetch_array($result)){ 
  		 
  		$count2 = $row['count2'];
  	}
  if ($count1 > 0 && $count2 > 0){
	  $result = mysqli_query($connect,$query);
	  $query = "select availability from book_availability where isbn ='$isbn';";
	  	//echo $query;
	  	$result = mysqli_query($connect,$query);
	  	while($row = mysqli_fetch_array($result)){  
	  		$available = $row['availability'];
	  	}

	 if ($available == 1){
	  	$query = "select count(*) as Count from book_loans where card_id ='$borrowerId'and date_in is null;";
	  	//echo $query;
	  	$result = mysqli_query($connect,$query);
	  	while($row = mysqli_fetch_array($result)){  
	  		$count = $row['Count'];
	  	}	  	
	  	if ($count <3) {
	  	$query = "INSERT INTO BOOK_LOANS (Isbn, Card_id, Date_out, Due_date) VALUES ('$isbn', $borrowerId, STR_TO_DATE('$today', '%m-%d-%Y'), STR_TO_DATE('$estimate', '%m-%d-%Y'));";
	 	//echo $query;
	 	$result = mysqli_query($connect,$query);
	 	$query = "update book_availability set availability = 0 where isbn = '$isbn';";
	 	$result = mysqli_query($connect,$query);
	 	//echo $query;
	 	echo "<h1>Check out done!</h1>";
	 }
	 else{
	 	echo "<h1>You have already borrowed three books!</h1>";
	 }
	 }
	 else{
	 	echo "<h1>This book is not available!</h1>";
	 }	
}
else{
	echo "<h1>Please enter a valid ISBN and Borrower Id!</h1>";
}

mysqli_close($connect);


?>
</div>
</body>
</html>
