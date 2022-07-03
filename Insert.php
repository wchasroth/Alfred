<?php 

namespace alfred;

class Insert {
    private   $connection;   // MySQL db connection
    protected $tableName;    // name of MySQL table
    private   $logger;       // optional instance of alfred\Logger
    
    protected $fields;
    
    function __construct($connection, $tableName, $logger=null) {
        $this->connection = $connection;
        $this->tableName  = $tableName;
        $this->logger     = $logger;
        $this->fields = array();
    }
    
    function addValue ($name, $value) {
        $this->fields[$name] = $value;
        return $this;
    }
    
    function addText ($name, $value) {
        $this->fields[$name] = "'$value'";  // need to make it 'safe' somehow.
        return $this;
    }
    
    function toSql() {
        $comma = "";
        $names = "";
        $values = "";
        foreach ($this->fields as $name => $value) {
            $names  .= $comma . $name;
            $values .= $comma . $value;
            $comma = ", ";
        }
        return "INSERT INTO $this->tableName ($names) VALUES ($values)";
    }
    
    function execute() {
        $sql = $this->toSql();

        $result = mysqli_query($this->connection, $this->toSql());
        if ($logger != null)  $logger->log("$result  $this->toSql");
        return $result;
    }
}

?>