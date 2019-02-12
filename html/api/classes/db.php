<?php

require_once ROOT_FOLDER.'/utilities/lib.php';
require_once ROOT_FOLDER.'/DBCON/db_con.php';
require_once ROOT_FOLDER.'/DBCON/db_vars.php';

// Enable errors
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

class DB {
  private $mysqli;
  private static $instance;

  public static function Instance() {
    if (self::$instance === null) {
      $instance = new DB();
      try {
        $instance->mysqli = new mysqli(DBCON::VALUES['host'], DBCON::VALUES['username'],
          DBCON::VALUES['password'], DBCON::VALUES['database-name']);
      } catch (mysqli_sql_exception $e) {
        ThrowErrorSafe(7); // ERROR-7
      }

      if ($instance->mysqli->connect_errno) {
        ThrowErrorSafe(5); // ERROR-5
      }

      if (!$instance->mysqli->set_charset("utf8")) {
        ThrowErrorSafe(10); // ERROR-10
      }
    }
    return $instance;
  }

  private function __construct() { }

  function __destruct() {
    if ($this->mysqli) {
      $this->mysqli->close();
      $this->isOpen = FALSE;
    }
  }

  // Execute the string $query as an SQL query
  private function ExecuteQuery($Sql) {
    try {
      if ($result = $this->mysqli->query($Sql)) {
        $this->LogQuery($Sql, 1);
        return $result;
      }
    } catch (Exception $e) {
        $this->LogQuery($Sql, 0);
        ThrowError($e->getCode(), $e);
    }

    return 0;
  }

  // Log Queries
  private function LogQuery($Sql, $Success) {
    $old_data = '';
    $ip = GetClientIp();
    $user_id = GetGlobalRequest()->GetUserId();
    if (!$prep = $this->Prepare(DBVARS::PREPARED['log_query']['sql'])) {
      ThrowError(8); // ERROR-8
    }
    if (!$prep->bind_param(DBVARS::PREPARED['log_query']['types'], $Sql, $old_data,
        $ip, $user_id, $Success)) {
      ThrowError(12); // ERROR-12
    }
    if (!$prep->execute()) {
      ThrowError(15); // ERROR-15
    }
    $prep->close();
  }

  // Log errors
  function LogError($code, $error = "") {
    $error = str_replace('\\u0000', '', json_encode((array)$error, JSON_UNESCAPED_UNICODE));
    $ip = GetClientIp();
    $user_id = GetGlobalRequest()->GetUserId();
    $request = GetGlobalRequest()->GetString();
    if (!$prep = $this->Prepare(DBVARS::PREPARED['log_error']['sql'])) {
      ThrowErrorSafe(8); // ERROR-8
    }
    if (!$prep->bind_param(DBVARS::PREPARED['log_error']['types'], $code, $error,
        $ip, $user_id, $request)) {
      ThrowErrorSafe(12); // ERROR-12
    }
    if (!$prep->execute()) {
      ThrowErrorSafe(15); // ERROR-15
    }
    $prep->close();
  }

  function ExecutePreparedStatement($Prepared, $Parameters) {
      if (!$prep = $this->Prepare(DBVARS::PREPARED[$Prepared]['sql'])) {
        ThrowError(8); // ERROR-8
      }
      if (!is_array($Parameters)) {
        if (!$prep->bind_param(DBVARS::PREPARED[$Prepared]['types'], $Parameters)) {
          ThrowError(12); // ERROR-12
        }
      } else {
        if (!$prep->bind_param(DBVARS::PREPARED[$Prepared]['types'], ...$Parameters)) {
          ThrowError(12); // ERROR-12
        }
      }
      if (!$prep->execute()) {
        ThrowError(15); // ERROR-15
      }
      if (!$result = $prep->get_result()) {
        ThrowError(16); // ERROR-16
      }
      while ($row = $result->fetch_assoc()) {
        $parsedResult[] = $row;
      }
      $prep->close();
      if (isset($parsedResult)) {
        return $parsedResult;
      }
      return NULL;
  }
  // Escape string
  public function EscapeString($Str) {
    return $this->mysqli->escape_string($Str);
  }

  // Prepare statement
  public function Prepare($Sql) {
    try {
      $return = $this->mysqli->prepare($Sql);
      return $return;
    } catch (Exception $e) {
      ThrowErrorSafe(8);
    }
  }
  //
  // // Returns the column names of $tableName or an empty array if some error occurs
  // private function GetTableColumns($tableName) {
  //   $result = $this->ExecuteQuery("DESCRIBE " . $this->mysqli->escape_string($tableName));
  //
  //   while ($row = $result->fetch_assoc()) {
  //     $columns[] = $row['Field'];
  //   }
  //
  //   return $columns;
  // }
  //
  // // Select
  // public function Select($TableName, $KeyList, $WhereKeyValueList) {
  //   $allColumnNames = $this->GetTableColumns($TableName);
  //
  //   $columns = ""; // Columns e.g 'col1', 'col2' ... OR *
  //   if (is_string($KeyList)) {
  //     if ($KeyList === "*") {
  //       $columns = "*";
  //     }
  //     else {
  //       ThrowError(14); // ERROR-14
  //     }
  //   }
  //   else {
  //     // Check if the keys from $keys are valid keys for the $tableName
  //     $listIsEmpty = TRUE;
  //     foreach ($KeyList as $key) {
  //       $listIsEmpty = FALSE;
  //       if (!in_array($key, $allColumnNames, TRUE)) {
  //         ThrowError(9); // ERROR-9
  //       }
  //     }
  //
  //     // Check if the list has any valid items
  //     if ($listIsEmpty) {
  //       ThrowError(11); // ERROR-11
  //     }
  //
  //     foreach ($KeyList as $key) {
  //       $columns .= $key . ',';
  //     }
  //     $columns = rtrim($columns, ',');
  //   }
  //
  //   // Update where
  //   $where = "";
  //   foreach ($WhereKeyValueList as $key => $value) {
  //     $where .= $key . ' = ';
  //     if (is_string($value)) {
  //       $where .= '"' . $this->mysqli->escape_string($value) . '"';
  //     }
  //     else {
  //       $where .= $value;
  //     }
  //     $where .= ' AND ';
  //   }
  //   $where = preg_replace('/ AND $/', '', $where);
  //   $sql = "SELECT {$columns} FROM {$TableName} WHERE {$where}";
  //   // Check if the sql was successful
  //   if ($result = $this->ExecuteQuery($sql)) {
  //     $returnArr = array();
  //     while ($row = $result->fetch_assoc()) {
  //       $returnArr[] = $row;
  //     }
  //     return $returnArr;
  //   }
  //   else {
  //     return 0;
  //   }
  // }
  // // Insert
  // public function Insert($TableName, $KeyValueList) {
  //   $allColumnNames = $this->GetTableColumns($TableName);
  //   // Check if the keys from $keys are valid keys for the $tableName
  //   $listIsEmpty = TRUE;
  //   foreach ($KeyValueList as $key => $value) {
  //     $listIsEmpty = FALSE;
  //     if (!in_array($key, $allColumnNames, TRUE)) {
  //       ThrowError(9); // ERROR-9
  //     }
  //   }
  //
  //   // Check if the list has any valid items
  //   if ($listIsEmpty) {
  //     ThrowError(11); // ERROR-11
  //   }
  //
  //   $columns = ""; // Columns e.g 'col1', 'col2' ...
  //   $values = ""; // Values e.g 'val1', 'val2' ...
  //   foreach ($KeyValueList as $key => $value) {
  //     $columns .= $key . ',';
  //     //$values .= '\'' . $this->mysqli->escape_string($value) . '\',';
  //     if (is_string($value)) {
  //       $values .= '"' . $this->mysqli->escape_string($value) . '"';
  //     }
  //     else {
  //       $values .= $value;
  //     }
  //     $values .= ',';
  //   }
  //   $columns = rtrim($columns, ',');
  //   $values = rtrim($values, ',');
  //
  //   $sql = "INSERT INTO {$TableName} ({$columns}) VALUES ({$values})";
  //   // Check if the sql was successful
  //   if ($this->ExecuteQuery($sql)) {
  //     return 1;
  //   }
  //   else {
  //     return 0;
  //   }
  // }
  // // Update
  // public function Update($TableName, $KeyValueList, $WhereKeyValueList) {
  //   $allColumnNames = $this->GetTableColumns($TableName);
  //   // Check if the keys from $keys are valid keys for the $tableName
  //   $listIsEmpty = TRUE;
  //   foreach ($KeyValueList as $key => $value) {
  //     $listIsEmpty = FALSE;
  //     if (!in_array($key, $allColumnNames, TRUE)) {
  //       ThrowError(9); // ERROR-9
  //     }
  //   }
  //
  //   // Check if the list has any valid items
  //   if ($listIsEmpty) {
  //     ThrowError(11); // ERROR-11
  //   }
  //
  //   // Update values
  //   $update = "";
  //   foreach ($KeyValueList as $key => $value) {
  //     $update .= $key . ' = ';
  //     if (is_string($value)) {
  //       $update .= '"' . $this->mysqli->escape_string($value) . '"';
  //     }
  //     else {
  //       $update .= $value;
  //     }
  //     $update .= ',';
  //   }
  //   $update = rtrim($update, ',');
  //
  //   // Update where
  //   $where = "";
  //   foreach ($WhereKeyValueList as $key => $value) {
  //     $where .= $key . ' = ';
  //     if (is_string($value)) {
  //       $where .= '"' . $this->mysqli->escape_string($value) . '"';
  //     }
  //     else {
  //       $where .= $value;
  //     }
  //     $where .= ' AND ';
  //   }
  //   $where = preg_replace('/ AND $/', '', $where);
  //
  //   // TODO after SELECT - backup data
  //
  //   $sql = "UPDATE {$TableName} SET {$update} WHERE {$where}";
  //
  //   // Check if the sql was successful
  //   if ($this->ExecuteQuery($sql)) {
  //     return 1;
  //   }
  //   else {
  //     return 0;
  //   }
  // }
  // // Delete
  // public function Delete($TableName, $WhereKeyValueList) {
  //   // WHERE
  //   $where = "";
  //   foreach ($WhereKeyValueList as $key => $value) {
  //     $where .= $key . ' = ';
  //     if (is_string($value)) {
  //       $where .= '"' . $this->mysqli->escape_string($value) . '"';
  //     }
  //     else {
  //       $where .= $value;
  //     }
  //     $where .= ' AND ';
  //   }
  //   $where = preg_replace('/ AND $/', '', $where);
  //
  //   // TODO after SELECT - backup data
  //
  //   $sql = "DELETE FROM {$TableName} WHERE {$where}";
  //
  //   // Check if the sql was successful
  //   if ($this->ExecuteQuery($sql)) {
  //     return 1;
  //   }
  //   else {
  //     return 0;
  //   }
  // }
  // // Get the maximum field of a table (useful for finding the last index)
  // public function GetMax($tableName, $field, $WhereKeyValueList = null) {
  //   $tableName = $this->mysqli->escape_string($tableName);
  //   if (is_string($field)) {
  //     $field = $this->mysqli->escape_string($field);
  //   }
  //
  //   // Update where
  //   $sql = "";
  //   if ($WhereKeyValueList !== null) {
  //     $where = "";
  //     foreach ($WhereKeyValueList as $key => $value) {
  //       $where .= $key . ' = ';
  //       if (is_string($value)) {
  //         $where .= '"' . $this->mysqli->escape_string($value) . '"';
  //       }
  //       else {
  //         $where .= $value;
  //       }
  //       $where .= ' AND ';
  //     }
  //     $where = preg_replace('/ AND $/', '', $where);
  //
  //     $sql = "SELECT * FROM {$tableName} WHERE {$where} ORDER BY {$field} DESC LIMIT 1";
  //   }
  //   else {
  //     $sql = "SELECT * FROM {$tableName} ORDER BY {$field} DESC LIMIT 1";
  //   }
  //
  //   $query = $this->ExecuteQuery($sql);
  //   $dbRow = $query->fetch_assoc();
  //   return $dbRow[$field];
  // }

}
?>
