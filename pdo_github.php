<?php 

//$host = "localhost";
//$db = "MyDB";
//$charset = "cp1251";
//$user = "root";
//$pass = "";
//
//$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
//
//$pdo = new PDO($dsn, $user, $pass);




################################################################################
# returns the last row in the table $table under conditions $needle
################################################################################
function in_table($table, $needle) {
    
    $conditions = keys_values_to_sql(" AND ", $needle);
    $sql = "SELECT * FROM $table WHERE $conditions ORDER BY id DESC";
    
    global $pdo;
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);

    while($row = $stmt->fetch()) return $row;

}


################################################################################
# inserts $array as a new line in the table $table
################################################################################
function new_line($array, $table) {
    
    $columns = keys_to_sql($array);
    $values = values_to_sql($array);
    $sql = "INSERT INTO $table ($columns) VALUES ($values)";
    
    global $pdo;
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
}


################################################################################
# updates the line under conditions $needle 
# with $array as new data in the table $table
################################################################################
function update_line($array, $table, $needle) {
    
    $settings = keys_values_to_sql(" , ", $array);
    $conditions = keys_values_to_sql(" AND ", $needle);
    $sql = "UPDATE $table SET $settings WHERE $conditions";
    
    global $pdo;
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
}



################################################################################
# converts an associative array $array into a string "key1, key2, key3..."
################################################################################
function keys_to_sql($array) {
    
    $arr = [];
    foreach($array as $key => $value) array_push($arr, $key);
    return implode($arr, ", ");
    
}


################################################################################
# converts an associative array $array into a string "value1, value2, value3..."
################################################################################
function values_to_sql($array) {
    
    $arr = [];
    foreach($array as $key => $value) array_push($arr, "'$value'");
    return implode($arr, ", ");
    
}



################################################################################
# converts an associative array $array into a string 
# "key1 = 'value1', key2 = 'value2', key3 = 'value3'..." or
# "key1 = 'value1' AND key2 = 'value2' AND key3 = 'value3' AND..."
################################################################################
function keys_values_to_sql($glue, $array) {
    
    $arr = [];
    foreach($array as $key => $value) array_push($arr, "$key = '$value'");
    return implode($glue, $arr);
}

?>
