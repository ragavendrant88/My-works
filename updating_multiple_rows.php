<? php

// Update values
$update_values = Array(
  '1034786' => Array('column1' => 0, 'column2' => NULL, 'column3'=> 'Text One'),
  '1037099' => Array('column1' => 0, 'column2' => 1034789 , 'column3'=> 'Text Two'),
  '1034789' => Array('column1' => 3, 'column2' => 1034789 , 'column3'=> 'Text Three')
);

// Table Name
$table_name = "sample";

// Updating the columns
$columns = Array('column1' => '`column1` = CASE ', 'column2' => '`column2` = CASE ', 'column3' => '`column3` = CASE ');

// Execute the query
$update_query = $this->updatingMultipleRows($table_name, $columns);
mysql_query($update_query) or die(mysql_error());

    /**
     * Updating Multiple rows
     * @param string $table_name
     * @param array  $columns
     * @return string
     */
    function updatingMultipleRows ($table_name, $columns) {
        // Start the query
        $update_query = "UPDATE {$table_name} SET ";
        
        // Constructing the CASE statement
        foreach($update_values as $id => $values){
            $columns['column1'] .= "WHEN `id`='" . mysql_real_escape_string($id) . "' THEN '" . mysql_real_escape_string($values['column1']) . "' ";
            $columns['column2'] .= "WHEN `id`='" . mysql_real_escape_string($id) . "' THEN "  . ($values['column2'] === NULL ? "NULL" : "'".mysql_real_escape_string($values['column1'])."'") . " ";
            $columns['column3'] .= "WHEN `id`='" . mysql_real_escape_string($id) . "' THEN '" . mysql_real_escape_string($values['column3']) . "' ";
        }

        // Existing values
        foreach($columns as $column_name => $query_part){
            $columns[$column_name] .= " ELSE `$column_name` END ";
        }
      
        // Where clause
        $where = " WHERE `id`='" . implode("' OR `id`='", array_keys($update_values)) . "'";
      
        // Join
        $update_query .= implode(', ',$columns) . $where;
      
        // return
        return $update_query;
    }

?>
