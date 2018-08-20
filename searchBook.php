<?php

session_start();

if(isset($_POST['valueToSearch']))
{      
    $valueToSearch = $_POST['valueToSearch'];
    // search in all table columns
    // using concat mysql function
    //$query = "SELECT dname FROM department WHERE dname LIKE '%".$valueToSearch."%';";
    //$query = "select b.Isbn,b.Title,b.Cover,b.Publisher,b.Pages,ba.availability,GROUP_CONCAT(a.author_name) as authors from book as b,book_availability as ba, authors as a, book_authors as bau where CONCAT(b.Isbn,b.Title,b.Cover,b.Publisher,b.Pages) Like '%".$valueToSearch."%' and b.isbn = ba.isbn;"; 
    $query = "select b.isbn,title,authors_list,availability from book as b left join temp_book_authors_list as tb on (b.isbn = tb.isbn ),  book_availability as ba where CONCAT(b.Isbn,b.Title,tb.authors_list) Like '%".$valueToSearch."%' and b.isbn = ba.isbn;"; 
    echo $query;  
    $search_result = filterTable($query);
    
}
 else {
   
}
// function to connect and execute the query
function filterTable($query)
{
    $connect = mysqli_connect("localhost", "root", "Utdmysql", "library","3306");    
    $filter_Result = mysqli_query($connect, $query);
    $count = mysqli_num_rows($filter_Result);    
    return $filter_Result;
}
function setSessionVariable($isbn){
    //echo "setting session variable";
    $_SESSION['Isbn'] = $isbn;
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>SEARCH BOOK</title>
        
        <link rel="stylesheet" href="layout.css">  
    </head> 
    <body>
        <div id = "header"><h1>Library Management System</h1></div>
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
        
        <form action="searchBook.php" method="post">
            <br><br>
            <input type="text" name="valueToSearch" placeholder="Value To Search"><br><br>
            <input type="submit" name="search" value="Filter"><br><br>
            
            <table>
                <tr>
                    <th>Isbn</th>
                    <th>Title</th>
                    <th>Authors</th>                    
                    <th>Availability</th>

                    
                </tr>                   
      <!-- populate table from mysql database -->
                <?php while($row = mysqli_fetch_array($search_result)){  
                                
                    echo "<tr><td>".$row['isbn']."</td>";
                     echo "<td>".$row['title']."</td>";
                      echo "<td>".$row['authors_list']."</td>";                     
                       If ($row['availability'] == 1){
                            $availability = "Available";
                            //echo "<td><form action = 'test2.php' method = 'POST'><input type='submit' name = '".$row['isbn']."' value='".$availability."'/></form></td></tr>";
                            echo "<td><form action = 'checkOut.html' method = 'POST'><input type='submit' value='".$availability."'/></form></td></tr>";
                        }
                        elseif ($row['availability'] == 0) {
                            
                        
                            $availability = "Not Available";
                            //echo "<td><form action = 'test3.php' method = 'POST'><input type='submit' name = '".$row['isbn']."' value='".$availability."'/></form></td></tr>";
                            echo "<td><form action = 'checkIn.html' method = 'POST'><input type='submit' value='".$availability."'/></form></td></tr>";
                        }
                        //echo "in while isbn is".$row['isbn'];                   
                }                
                ?>
            </table>
        </form>
        </div>
    </body>
</html>