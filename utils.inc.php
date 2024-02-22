<?php
function formRequest($formName){
  if(isset($_REQUEST[$formName])) {
    return trim($_REQUEST[$formName]);
  } else {
    return "";
}
}
function createTable($colum){
  global $activity, $conn, $table,$fields;
  echo $activity . " in ".$colum.  "section";
  $stmt = $conn->prepare("SELECT * FROM `$table` ORDER BY `$fields[1]` ASC;");
  $stmt->execute();

  $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

  // Check if $result has anything in it or not (Returns a FALSE if no data in there).
  if($result) {
    echo "<table border=1>";   // Start Table
    $firstRowPrinted = false;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      if($firstRowPrinted == false) {
        echo "<tr>";// Start HEADER Row
        if ($colum != "READ"){
        echo "<th>UPDATE</th>";}
        foreach($row as $col_name => $val) {
          echo "<th>$col_name</th>";    // Print Each Column Name
        }
        echo "</tr>";               // END Header Row
        $firstRowPrinted = true;
      }
      if ($colum !="READ"){
      echo "<tr>";               // Start Row
      echo "<td><a href='index.php?activity=".$colum."-PROCESS&id=".$row["id"]."'>".$colum."</a></td>";}

      foreach($row as $col_name => $val) {
        echo "<td>$val</td>";    // Print Each VALUE
      }
      echo "</tr>";               // Start Row
    }
    echo "</table>";
  }
}

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
      //$sql= $sql."`$fieldName` =' ".$fieldName."VALUE',";
    }
     // $sql .= " WHERE id = ".formRequest('id');
      //$WHERE = strpos($sql, "WHERE");
     // $sql = substr_replace($sql, '', $WHERE - 2 , 1);
      //echo '<input type="hidden" name="sql" value="'.$sql.'">';
     // echo $sql;
      echo '<input type="submit" value="GO!"><br>';
      echo '</form>';
      ///////////////////////////////////////////////////////////////////////$sql = str_replace(", WHERE", " WHERE", $sql); //bob
      }
      
    }
    

?>
