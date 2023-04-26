<?php

$db_name = "test";
$table_name = "testing_table";
$insert_data = ["email@email.nl", "Bruno Bouwman", "123inkt.nl", "01234567890"];
$fetch_columns = ["email", "name", "company", "phone_number"];
$where_exception_fetch = "name='John Spice'";
$where_exception_delete = "name='John Spice'";

$sql_con = make_connection($db_name);

insert_data($sql_con, $table_name, $insert_data);
$fetch_data = fetch_data($sql_con, $table_name, $fetch_columns, $where_exception_fetch);
deleteData($sql_con, $table_name, $where_exception_delete);

var_dump($fetch_data);

$sql_con->close();

function make_connection($db_name): false|mysqli|null
{
    $server_name = "localhost";
    $user_name = "root";
    $password = "";
    $con = mysqli_connect($server_name, $user_name, $password, $db_name);
    if (mysqli_connect_errno()) {
        return null;
    }
    return $con;
}

function insert_data($con, $table, $array): bool
{
    $sql_query = "INSERT INTO " . $table . " VALUES (";
    foreach ($array as $item) {
        $sql_query .= "'" . $item . "'";
        if ($item != end($array)) {
            $sql_query .= ",";
        }
    }
    // If SQL function needs to be added please insert it yourself.
    $sql_query .= ")";
    try {
        $con->query($sql_query);
    } catch (mysqli_sql_exception) {
        return False;
    }
    return True;
}

function fetch_data($con, $table, $columns, $where = ""): array
{
    $data = [];
    $sql_query = "SELECT ";
    foreach ($columns as $item) {
        $sql_query .= $item;
        if ($item != end($columns)) {
            $sql_query .= ",";
        }
    }
    $sql_query .= " FROM " . $table;

    if ($where != "") {
        $sql_query .= " WHERE $where";
    }

    try {
        $result = $con->query($sql_query);
    } catch (mysqli_sql_exception) {
        return ["ERROR"];
    }

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    } else {
        return ["ERROR"];
    }
    return $data;
}

function deleteData($con, $table, $where): bool
{
    $sql_query = "DELETE FROM $table WHERE $where";
    try {
        $con->query($sql_query);
    } catch (mysqli_sql_exception) {
        return False;
    }
    return True;
}