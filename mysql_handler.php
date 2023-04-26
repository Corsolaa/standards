<?php

$db_name = "test";
$table_name = "testing_table";

$insert_data = ["email@email.nl", "Bruno Bouwman", "123inkt.nl", "01234567890"];

$fetch_columns = ["email", "name", "company", "phone_number"];

$where_exception_fetch =  ["name='Bruno Bouwman'", "email='email@email.nl'"];
$where_exception_delete = ["name='Bruno Bouwman'", "email='email@email.nl'"];
$where_exception_update = ["name='Bruno Bouwman'", "email='email@email.nl'"];

$update_set = ["email='Bruno@hvdz.nl'", "company='bedrijfsnaam'"];

$sql_con = make_connection($db_name);

insert_data($sql_con, $table_name, $insert_data);
fetch_data($sql_con, $table_name, $fetch_columns, $where_exception_fetch);
deleteData($sql_con, $table_name, $where_exception_delete);
updateData($sql_con, $table_name, $update_set, $where_exception_update);

$sql_con->close();

/**
 * Makes the mysqli connection and returns it.
 *
 * @param String $db_name The name of the database that you want to use.
 * @return false|mysqli|null
 */
function make_connection(String $db_name): false|mysqli|null
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

/**
 * Inserts data into the table with the correct properties.
 * Return True if worked, returns False when something went wrong.
 *
 * @param mysqli $con A SQL connection that has already been established.
 * @param string $table A table name that exists in database that ahas been set in SQL connection.
 * @param array $values Example: ["email@email.nl", "Bruno Bouwman"] NEEDS TO HAVE ALL COLUMNS FILLED
 * @return bool
 */
function insert_data(mysqli $con, string $table, array $values): bool
{
    $sql_query = "INSERT INTO " . $table . " VALUES (";
    foreach ($values as $item) {
        $sql_query .= "'" . $item . "'";
        if ($item != end($values)) {
            $sql_query .= ",";
        }
    }
    // If SQL function needs to be added please insert it yourself.
    $sql_query .= ")";
    try {
//        Uncomment echo when testing query's
//        echo $sql_query;
        $con->query($sql_query);
    } catch (mysqli_sql_exception) {
        return False;
    }
    return True;
}

/**
 * Fetches data from database with the right conditions
 * Returns fetched data if good, returns [ERROR] if something went wrong.
 *
 * @param mysqli $con A SQL connection that has already been established.
 * @param string $table A table name that exists in database that ahas been set in SQL connection.
 * @param array $columns Example: ["email", "name", "company", "phone_number"]
 * @param array $where Example: ["name='Bruno Bouwman'", "email='email@email.com'"]
 * @return array
 */
function fetch_data(mysqli $con, string $table, array $columns, array $where = []): array
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

    if ($where != []) {
        $sql_query .= " WHERE ";
        foreach ($where as $item) {
            $sql_query .= $item;
            if ($item != end($where)) {
                $sql_query .= " AND ";
            }
        }
    }

    try {
//        Uncomment echo when testing query's
//        echo $sql_query;
        $result = $con->query($sql_query);
    } catch (mysqli_sql_exception) {
        return ["ERROR"];
    }

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    } else {
        return [];
    }
    return $data;
}

/**
 * This function deletes data when the conditions are met.
 * Return True if worked, returns False when something went wrong.
 *
 * @param mysqli $con A SQL connection that has already been established.
 * @param string $table A table name that exists in database that ahas been set in SQL connection.
 * @param array $where Example: ["name='Bruno Bouwman'", "email='email@email.com'"]
 * @return bool
 */
function deleteData(mysqli $con, string $table, Array $where): bool
{
    $sql_query = "DELETE FROM $table WHERE ";
    foreach ($where as $item) {
        $sql_query .= $item;
        if ($item != end($where)) {
            $sql_query .= " AND ";
        }
    }
    try {
//        Uncomment echo when testing query's
//        echo $sql_query;
        $con->query($sql_query);
    } catch (mysqli_sql_exception) {
        return False;
    }
    return True;
}

/**
 * This function updates rows when the conditions are met.
 * Return True if worked, returns False when something went wrong.
 *
 * @param mysqli $con A SQL connection that has already been established.
 * @param string $table A table name that exists in database that ahas been set in SQL connection.
 * @param array $set Example: ["name='John Spice'", "email='john@email.com'"]
 * @param array $where Example: ["name='Bruno Bouwman'", "email='email@email.com'"]
 * @return bool
 */
function updateData(mysqli $con, string $table, Array $set, array $where): bool
{
    $sql_query = "UPDATE $table SET ";
    foreach ($set as $item) {
        $sql_query .= $item;
        if ($item != end($set)) {
            $sql_query .= ",";
        }
    }
    $sql_query .= " WHERE ";
    foreach ($where as $item) {
        $sql_query .= $item;
        if ($item != end($where)) {
            $sql_query .= " AND ";
        }
    }
    try {
//        Uncomment echo when testing query's
//        echo $sql_query;
        $con->query($sql_query);
    } catch (mysqli_sql_exception) {
        return False;
    }
    return True;
}