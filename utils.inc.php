<?php
function formRequest($formName){
  if(isset($_REQUEST[$formName])) {
    return trim($_REQUEST[$formName]);
  } else {
    return "";
}
}
function sortData($field){
  global $conn, $table;
  $sql="SELECT * FROM `$table` ORDER BY $field;";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  return array($stmt,$sql);
}
function createTable($directive){
  global  $ordering, $fieldsALL;
  if ($directive == "ORDER"){
    list($stmt,$sql) = sortData($ordering);
    echo $sql;
    }
  elseif($directive == "READ" | $directive == "DOWNLOAD"){
    list($stmt,$sql) = sortData($fieldsALL[0]." ASC");
    
  }
  $stmt->setFetchMode(PDO::FETCH_ASSOC);
    echo "<table border=1>";   // Start Table
    $firstRowPrinted = false;
   
    
    $myfile = fopen("myfile.csv", "w");
    if ($directive == "DOWNLOAD"){
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      //DOWNLOAD--------------------------------->
      
        if($firstRowPrinted == false){
          $fileWriteString = "";
          foreach($row as $col_name => $val) {
            $fileWriteString .= $col_name . ",";
          } //-For
          fwrite($myfile, rtrim($fileWriteString, ","));
          fwrite($myfile, "\n");
          $firstRowPrinted = true;
        } //-If
      //-First Row Done


      $fileWriteString = "";
      foreach($row as $col_name => $val) {
          $fileWriteString .= $val . ",";
        //else { echo "<td>$val</td>"; // Print Each VALUE
      } //For loop end
      fwrite($myfile, rtrim($fileWriteString, ","));
      fwrite($myfile, "\n" );
    } //-If
    fclose($myfile);
    
    return; // exit faster without doing addition checks
  } //<--------------------------DOWNLOAD
  


  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if($firstRowPrinted == false) {
          echo "<tr>";// Start HEADER Row
          echo "<th>UPDATE</th>";
          echo "<th>DELETE</th>";
        foreach($row as $col_name => $val) {
          if ($col_name." ASC" == $ordering){ 
            echo "<th><a href='index.php?order=$col_name DESC'>$col_name</a></th>"; 
          }   // Print Each Column Name
          else{
            if ($ordering == "id"){ // Since this starts as the initial order of the data
                                    // It has this to cirvumvent having to click it twice to change the sorting.
                                    // The ordering would have no sort method, since and order request wasn't made yet.
              echo "<th><a href='index.php?order=$col_name DESC'>$col_name</a></th>";
            }    
            echo "<th><a href='index.php?order=$col_name ASC'>$col_name</a></th>";
          }
        }
        echo "</tr>";               // END Header Row
        $firstRowPrinted = true;
      }
      // fIRST ROW BS
      echo "<tr>";               // Start Row
      echo "<td><a href='index.php?activity="."UPDATE"."-PROCESS&id=".$row["id"]."'>"."UPDATE"."</a></td>";
      echo "<td><a href='index.php?activity="."DELETE"."-PROCESS&id=".$row["id"]."'>"."DELETE"."</a></td>";
      // RUNS EVERY WHILE LOOP ITERATION AND IS THE FIRST DATA ENTRY
      foreach($row as $col_name => $val) {
        echo "<td>$val</td>"; // Print Each VALUE
      } //For loop end
      echo "</tr>"; // Start Row
    }// While loop end
    echo "</table>";   
  }// Function Close
    


function writeForm($activity){
  global $conn, $table, $fields;
  echo $activity." section";
  if ($activity == "CREATE"){
    echo '<form action="index.php">';
    echo '<input type="hidden" name="activity" value="'.$activity.'-PROCESS">';
  foreach ($fields as $fieldName ){
      echo '<input type="text" name="'.$fieldName.'" placeholder="'.$fieldName.'"><br>';
  }
    echo '<input type="submit" value="GO!"><br>';
    echo '</form>';
  }
  
  if($activity == "UPDATE-PROCESS"){
    $stmt = $conn->prepare("SELECT * FROM `$table` WHERE id = ".formRequest("id"));
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC); // sets the structure of the return value
    //$sql = "UPDATE `tbPeople_Shawn` SET ";
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    //html
      echo '<form action="index.php">';
      echo '<input type="hidden" name="activity" value="'.$activity.'-PROCESS">';
      echo '<input type="hidden" name="id" value="'.$row["id"].'">';
    foreach ($fields as $fieldName){
        echo '<input type="text" name="'.$fieldName.'" value="'.$row[$fieldName].'" placeholder="'.$fieldName.'"><br>';
    }
      echo '<input type="submit" value="GO!"><br>';
      echo '</form>';
    }   
  }
    




 //$WHERE = strpos($sql, "WHERE");
     // $sql = substr_replace($sql, '', $WHERE - 2 , 1);
      //echo '<input type="hidden" name="sql" value="'.$sql.'">';
     // echo $sql;
?>
