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
    	<div id = "header"><h1>checking In a book</h1></div>
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
  $connect = mysqli_connect("localhost", "root", "Utdmysql", "library","3306");    
    $query = "select count(*) as Count from book_loans where card_id ='$borrowerId' and isbn = '$isbn' and date_in is null;";
  	//echo $query;
  	$result = mysqli_query($connect,$query);
  	while($row = mysqli_fetch_array($result)){  
  		$count = $row['Count'];
  	}
  	//echo "count is ".$count;
  	if ($count >0) {
  	$query = "UPDATE BOOK_LOANS SET Date_in = STR_TO_DATE('$today', '%m-%d-%Y') WHERE card_id = '$borrowerId' and isbn = '$isbn';";
 	//echo $query;
   	$result = mysqli_query($connect,$query);
    $query = "update book_availability set availability = 1 where isbn = '$isbn';";
    $result = mysqli_query($connect,$query);
    //echo $query;
    echo "<h1>Check in done!</h1>";   	
     }
     else{ 
     	echo "<h1>Please enter a valid ISBN and Borrower ID!</h1>";
     }  	
mysqli_close($connect);
?>
</div>
</body>
</html>
