<?php 

namespace alfred;

class Upsert extends Insert {
    
    function __construct($connection, $tableName, $logger=null) {
        parent::__construct($connection, $tableName, $logger);
    }
    
    function toSql() {
        $comma = "";
        $names = "";
        $values = "";
        $updates = "";
        foreach ($this->fields as $name => $value) {
            $names   .=  $comma . $name;
            $values  .=  $comma . $value;
            $updates .= "$comma $name = $value";
            $comma = ", ";
        }
        return "INSERT INTO petitions ($names) VALUES ($values) "
           .   " ON DUPLICATE KEY UPDATE $updates";
    }
}

?>