<?php

session_start();

if(isset($_POST['showFines']))
{      
    $connect = mysqli_connect("localhost", "root", "Utdmysql", "library","3306");    
    
    $query = "insert into fines(loan_id,fine_amt,paid) select b.loan_id, DATEDIFF(curdate(),b.due_date)*0.25 as fine_amt,0 from BOOK_LOANS as b where DATEDIFF(curdate(),due_date) > 0 and date_in is null and not exists (select f.loan_id from fines as f where f.loan_id = b.loan_id );";
     $result = mysqli_query($connect, $query);

     $query = "insert into fines(loan_id,fine_amt,paid) select b.loan_id, DATEDIFF(b.date_in, b.due_date)*0.25 as fine_amt,0 from BOOK_LOANS as b where DATEDIFF(b.date_in,due_date) > 0 and date_in is not null and not exists (select f.loan_id from fines as f where f.loan_id = b.loan_id );";
     $result = mysqli_query($connect, $query);     


     $query = "update fines as f set fine_amt = (select DATEDIFF(curdate(),due_date)*0.25 from book_loans as b where DATEDIFF(curdate(), due_date) > 0 and date_in is null and f.loan_id = b.loan_id) where f.loan_id in (select loan_id from book_loans where date_in is null);";     
     $result = mysqli_query($connect, $query);


    $query = "select f.loan_id as Loan_id, Isbn, Card_id, Fine_amt, Paid from fines as f, book_loans as b where b.loan_id = f.loan_id;";  
    $search_result = mysqli_query($connect, $query);


    
}
 
if(isset($_POST['showTotalFines'])){
    $connect = mysqli_connect("localhost", "root", "Utdmysql", "library","3306"); 
    $query ="select b.card_id as card_id, ssn, first_name, last_name, sum(Fine_amt) as total_fine from fines as f, book_loans as b,borrower as bo where b.loan_id = f.loan_id and bo.card_id = b.card_id group by b.card_id;";
     $total_paid_result = mysqli_query($connect, $query);
}

if(isset($_POST['showPaidFines']))
{  
    $connect = mysqli_connect("localhost", "root", "Utdmysql", "library","3306"); 
    
     $query = "select f.loan_id as Loan_id, Isbn, Card_id, Fine_amt, Paid from fines as f, book_loans as b where b.loan_id = f.loan_id and Paid = 1;";
     
     $paid_result = mysqli_query($connect, $query);
     


}
if(isset($_POST['updateFine']))
{  
    $connect = mysqli_connect("localhost", "root", "Utdmysql", "library","3306");  
    $loanid = $_POST['loanId'];
    $query = "select count(*) as count from fines as f, book_loans as b where f.paid = 0 and f.loan_id = '$loanid' and f.loan_id = b.loan_id and date_in is not null;";
     
     $result = mysqli_query($connect, $query);
     while($row = mysqli_fetch_array($result)){        
        $count = $row['count'];
    }
    if ($count > 0){
     $query = "update fines set paid = 1 where loan_id = '$loanid';";     
     $result = mysqli_query($connect, $query);
    }
    else{
        echo '<script language="javascript">';
        echo 'alert("Cannot be updated because of one of the following reasons! Invalid loan ID entered or record is already updated or book hasn not yet checked in!")';
        echo '</script>';
    }    


}

function filterTable($query)
{
    $connect = mysqli_connect("localhost", "root", "Utdmysql", "library","3306");    
    $filter_Result = mysqli_query($connect, $query);
    $count = mysqli_num_rows($filter_Result);    
    return $filter_Result;
}
function setSessionVariable($isbn){   
    $_SESSION['Isbn'] = $isbn;
}


?>

<!DOCTYPE html>
<html>
    <head>
        <title>PHP HTML TABLE DATA SEARCH</title>
        
        <link rel="stylesheet" href="layout.css">  
    </head> 
    <body>
        <div id = "header"><h1>Fines</h1></div>
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
        
        <form action="fines.php" method="post">
            <h1>Calculate fines</h1>
            <input type="submit" name="showFines" value="Calculate Fines"><br><br>
            
            <table>
                <tr>
                    <th>Loan_id</th>
                    <th>Isbn</th>
                    <th>Card_id</th>                    
                    <th>Fine_amt</th>
                    <th>Paid</th>                    
                </tr>                   
      
                <?php while($row = mysqli_fetch_array($search_result)){  
                                
                    echo "<tr><td>".$row['Loan_id']."</td>";
                     echo "<td>".$row['Isbn']."</td>";
                      echo "<td>".$row['Card_id']."</td>";      
                      echo "<td>".$row['Fine_amt']."</td>";
                      echo "<td>".$row['Paid']."</td></tr>";          
                    }                
                ?>
            </table>
            <h1>Update paid fines</h1>
            <input type = "text" name ="loanId" placeholder = "Loan ID">
            <input type="submit" name="updateFine" value="Update Fine"><br><br>

            <h1>Show paid fines</h1>
            <input type="submit" name="showPaidFines" value = "Show Paid fines">
            <table>
                <tr>
                    <th>Loan_id</th>
                    <th>Isbn</th>
                    <th>Card_id</th>                    
                    <th>Fine_amt</th>
                    <th>Paid</th>                    
                </tr>                   
     
                <?php while($row = mysqli_fetch_array($paid_result)){                                  
                    echo "<tr><td>".$row['Loan_id']."</td>";
                     echo "<td>".$row['Isbn']."</td>";
                      echo "<td>".$row['Card_id']."</td>";      
                      echo "<td>".$row['Fine_amt']."</td>";
                      echo "<td>".$row['Paid']."</td></tr>";           
                     }                
                ?>
            </table>

            <h1>Show total fines</h1>
            <input type="submit" name="showTotalFines" value = "Show Total fine">
            <table>
                <tr>                   
                    <th>Card_id</th>  
                    <th>Ssn</th> 
                    <th>First Name</th> 
                    <th>Lastname</th>                   
                    <th>Total fine paid</th>                     
                </tr>                   
     
                <?php while($row = mysqli_fetch_array($total_paid_result)){                                  
                    echo "<tr><td>".$row['card_id']."</td>";      
                      echo "<td>".$row['ssn']."</td>";
                      echo "<td>".$row['first_name']."</td>";
                      echo "<td>".$row['last_name']."</td>";
                      echo "<td>".$row['total_fine']."</td></tr>";           
                     }                
                ?>
            </table>
        </form>
        </div>
    </body>
</html>