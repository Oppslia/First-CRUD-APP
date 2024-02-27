<?php
include "db.inc.php";
include "utils.inc.php";

$activity = formRequest("activity");


$table = "tbPeople_Shawn";
$query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = ?";

// Prepare and execute the query
$stmt = $conn->prepare($query);
$stmt->execute([$table]);

// Fetch all field names
$fields = $stmt->fetchAll(PDO::FETCH_COLUMN);
$idIndex = array_search("id", $fields);
unset($fields[$idIndex]); $fields = array_values($fields);

$lastField = $fields[count($fields)-1]; // sets a value equal to the last value so i don't have to use rtrim 
// Query to retrieve all fields of the specified table


?>
<html>
<head><title>Shawns's CRUD App</title></head>
<body>
  <a href="index.php?activity=READ">Home</a> |
  <a href="index.php?activity=CREATE">Create</a> | 
  <a href="index.php?activity=DOWNLOAD">Download</a>

  
  <br><br>
  
  <?php
  //Table names = tbPeople_FirstName
  
  switch ($activity) {
    case "DOWNLOAD":
      createTable("DOWNLOAD");
      //header("Location: index.php?activity=READ");
      // header doesnt work here for some reason
      break;




    case "CREATE":
      // C of Crud
      //Insert (SQL Language) Data!
      writeForm($activity);
    break;

    case "CREATE-PROCESS":
      
      // (`FirstName`, `LastName`, `Email`, `Phone`)
      // VALUES (' Shawn','Seltner','smsracer10@bloob.gov','215-333-5543')
      echo $activity . " in INSERT Processing section";
      
      $sql = "INSERT INTO `$table` ("; // Initial formatting INSERT INTO `tbPeople_Shawn`
      foreach($fields as $field){
        if ($field == $lastField){
          $sql .= "`$field`"; //formats last field so that it doesn't have trailing characters
          break;
        }
        $sql .= "`$field`, "; //Append each field (`FirstName`, `LastName`, `Email`, `Phone`, 
      }
      //strip off trailing space
      $sql = rtrim($sql, ", ");//Remove trailers (`FirstName`, `LastName`, `Email`, `Phone`                                 
      echo "<BR><BR>"; // Seperator
      $sql .= ') VALUES (\''; // ) VALUES (' ------->First Parenth closes last field. 
     
      foreach($fields as $field){
        if ($field == $lastField){
          $sql .= formRequest($field) . "')"; //formats last field so that it doesn't have trailing characters and closes the statement
          break; //Shawn','Seltner','smsracer10@bloob.gov','215-333-5543')
        }
        $sql .= formRequest($field) . "','" ;
      }
      
                              //INSERT INTO `tbPeople_Bob` (`First Name`, `Last Name`, `Email`, `Phone`) VALUES ('Bubba','Mann','bubba.d.mann@gmail.com','814-555-5555')

                              //$sql = "INSERT INTO `$table` (`FirstName`, `LastName`, `Email`, `Phone`) VALUES ('" . $FirstName . "','" . $LastName . "','" . $Email . "','" . $Phone . "')";
      
      $conn->exec($sql); // database go brrrrr.
      //echo "<BR>".$sql."<BR>"; //echo statement cuz need to see that sweet SWEET formatting
      header("Location: index.php?activity=READ");
      
    break; // bye

    case "READ":
      // R of cRud
      //Select (SQL Language) Data!
     createTable("READ");// make table with read param, dummy
      
    break; //bye

    case "UPDATE":
      // U of crUd
      //Update Data!
      createTable("UPDATE"); //make table w update param, dummy
    break; //bye

    
    case "UPDATE-PROCESS":
    
    writeForm($activity);
    
    //$sql = "UPDATE `tbPeople_Shawn` SET `LastName` = ".formRequest("LastName")."WHERE id = ".formRequest("id");
   // echo $sql;
    //$conn->exec($sql);
  
    echo "<BR>UPDATE: " . formRequest("id") . "<BR><BR>";
    
    break;
    case "UPDATE-PROCESS-PROCESS":
      $sql = "UPDATE `$table` SET ";
//            INSERT INTO `tbPeople_Bob` (`First Name`, `Last Name`, `Email`, `Phone`) VALUES ('Bubba','Mann','bubba.d.mann@gmail.com','814-555-5555')
// UPDATE `tbPeople_Shawn` SET `FirstName` =' FirstNameVALUE',`LastName` =' LastNameVALUE',`Email` =' EmailVALUE',`Phone` =' PhoneVALUE' WHERE id = 56
    foreach ($fields as $field){
      if($field == $lastField){
        $sql .= "`$field` =' ". formRequest($field)."'";
        break;
      }
      $sql .= "`$field` =' ". formRequest($field)."', ";
    }
      $sql .= "WHERE id = ".formRequest("id");
      //echo "<BR>".$sql."<BR>";
      $conn->exec($sql);
      header("Location: index.php?activity=READ");
      
      break;
      
      


    case "DELETE-PROCESS":
      //D of cruD
      //PROCESS for the Deleting of Data!
      

      $sql = "DELETE FROM `tbPeople_Shawn` WHERE `id` = " .formRequest("id");
      $conn->exec($sql);
    
      //echo "<BR>DELETE: " . formRequest("id") . "<BR><BR>";
    
    case "DELETE":
      //D of cruD
      //Delete Data!
     createTable("DELETE");
      break;
  }
    ?>
  
  


</body>
</html>
  
<?php
include "dbclose.inc.php";
?>

