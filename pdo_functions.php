<?php
include "../common/pdo_connection.php";

/////////////////////////////////////////////////////////////////////////////
//
// функции наружу
//
/////////////////////////////////////////////////////////////////////////////

/**
* возвращает последнюю строку по подстроке или NULL
*/
function in_table($table, $search)
{
    $conditions =  keys_values_to_sql($search, " AND ");
    $sql = "SELECT * FROM $table WHERE $conditions ORDER BY id DESC LIMIT 1";
    $stmt = go_pdo($sql);
    while($row = $stmt->fetch()) return resize_output($row);    
}

/**
* добавляет строку
*/
function new_line($table, $data)
{
    $columns = keys_to_sql($data);
    $values = values_to_sql($data);
    $sql = "INSERT INTO $table ($columns) VALUES ($values)";
    go_pdo($sql);
}

/**
* обновляет строки, удовлетворяющие условиям
*/
function update_line($table, $data, $search)
{
    $updates = keys_values_to_sql($data, ", ");
    $conditions = keys_values_to_sql($search, " AND ");
    $sql = "UPDATE $table SET $updates WHERE $conditions";
    go_pdo($sql);
}


/////////////////////////////////////////////////////////////////////////////
//
// внутренние функции модуля
//
/////////////////////////////////////////////////////////////////////////////

/**
* выполняет запрос к базе
*/
function go_pdo($sql)
{
    global $pdo;
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    return $stmt;
}

/**
* склеивает ключи в строку
*/
function keys_to_sql($array)
{
    $arr = array_keys($array);
    return implode($arr, ", ");
}

/**
* склеивает значения в строку
*/
function values_to_sql($array)
{
    $array = resize_input($array);
    $str = implode($array, "', '");
    return "'$str'";
}

/**
* склеивает пары ключ='значение' в строку с разделителем
*/
function keys_values_to_sql($array, $glue)
{
    $array = resize_input($array);
    $arr = [];
    foreach($array as $key => $value) array_push($arr, "$key = '$value'");
    return implode($arr, $glue);
}

/**
* обеззараживание и перекодировка данных
*/
function resize_input($array)
{
    foreach($array as $key => $value) 
    {
        $array[$key] = htmlspecialchars($value, ENT_QUOTES);
        $array[$key] = iconv("utf-8", "cp1251", $value);
    }
    return $array;
}

/**
* перекодировка данных
*/
function resize_output($array)
{
    foreach($array as $key => $value) 
    {
        $array[$key] = iconv("cp1251", "utf-8", $value);
    }
    return $array;
}

?>