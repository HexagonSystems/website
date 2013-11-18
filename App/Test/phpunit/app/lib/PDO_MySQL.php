<?php

namespace app\lib;

use \PDO;
use \PDOException;

class PDO_MySQL {

   /**
    *  Number of rows affected by MySQL query.
    *
    *  @var int
    *  @access protected
    */
    protected $_affected_rows = 0;

   /**
    *  The db handle.
    *
    *  @var object
    *  @access protected
    */
    protected $_dbh;

   /**
    *  The error messages generated by PDOExceptions.
    *
    *  @var array
    *  @access protected
    */
    protected $_errors = array();

   /**
    *  The result set associated with a prepared statement.
    *
    *  @var PDOStatement
    *  @access protected
    */
    protected $_statement;

   /**
    *  Returns the number of rows affected by the last DELETE,
    *  INSERT, or UPDATE query.
    *
    *  @access public
    *  @return int
    */
    public function affected_rows() {
    	return $this->_affected_rows;
    }

   /**
    *  Closes the connection.
    *
    *  @access public
    *  @return bool
    */
    public function close() {
        $this->_dbh = null;
        return true;
    }

   /**
    *  Connects and selects database.
    *
    *  @param array $options    Contains the connection information.  Takes the
    *                           following options:
    *                           'database' - The name of the database.
    *                           'host'     - The database host.
    *                           'port'     - The database port.
    *                           'username' - The database username.
    *                           'password' - The database password.
    *  @access public
    *  @return bool
    */
    public function connect($options = array()) {
        $dsn = "mysql:host={$options['host']};port={$options['port']}"
            . ";dbname={$options['database']}";
        try {
            $this->_dbh = new PDO($dsn, $options['username'], $options['password']);
            $this->_dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return true;
        } catch ( PDOException $e ) {
            $this->_errors[] = $e->getMessage();
            return false;
        }
    }

   /**
    *  Fetches the next row from the result set in memory (i.e., the one
    *  that was created after running query()).
    *
    *  @param string $fetch_style    Controls how the rows will be returned.
    *  @param obj $obj               The object to be fetched into if
    *                                $fetch_style is set to 'into'.
    *  @access public
    *  @return mixed
    */
    public function fetch($fetch_style = null, $obj = null) {
        $this->_set_fetch_mode($fetch_style, $obj);
        $row = $this->_statement->fetch();
        $this->_statement->closeCursor();
        return $row;
    }

   /**
    *  Returns an array containing all of the result set rows.
    *
    *  @param string $fetch_style    Controls how the rows will be returned.
    *  @access public
    *  @return mixed
    */
    public function fetch_all($fetch_style = null) {
        $this->_set_fetch_mode($fetch_style);
        $rows = $this->_statement->fetchAll();
        $this->_statement->closeCursor();
        return $rows;
    }

   /**
    *  Returns a single column from the next row of a result set or false
    *  if there are no more rows.
    *
    *  @param int $column_number    Zero-index number of the column to
    *                               retrieve from the row.
    *  @access public
    *  @return mixed
    */
    public function fetch_column($column_number = 0) {
        $column = $this->_statement->fetchColumn($column_number);
        $this->_statement->closeCursor();
        return $column;
    }

   /**
    *  Returns the errors generated by PDOExceptions.
    *
    *  @access public
    *  @return array
    */
    public function get_errors() {
        return $this->_errors;
    }

   /**
    *  Inserts a record into the database.
    *
    *  @param string $table    The table containing the record to be inserted.
    *  @param array $data      An array containing the data to be inserted.
    *                          Format should be as follows:
    *                          array('column_name' => 'column_value');
    *  @access public
    *  @return bool
    */
    public function insert($table, $data) {
        $sql = "INSERT INTO {$table} ";

        $key_names = array_keys($data);
    	$fields = implode(', ', $key_names);
        $values = ':' . implode(', :', $key_names);

    	$sql .= "({$fields}) VALUES ({$values})";

    	$statement = $this->_dbh->prepare($sql);

        try {
            $statement->execute($data);
        } catch ( PDOException $e ) {
            $this->_errors[] = $e->getMessage();
            return false;
        }

    	$this->_affected_rows = $statement->rowCount();
        return true;
    }

   /**
    *  Returns the ID of the last inserted row or sequence value.
    *
    *  @access public
    *  @return int
    */
    public function insert_id() {
        return $this->_dbh->lastInsertId();
    }

   /**
    *  Executes SQL query.
    *
    *  @param string $sql          The SQL query to be executed.
    *  @param array $parameters    An array containing the parameters to be
    *                              bound.
    *  @access public
    *  @return bool
    */
    public function query($sql, $parameters = array()) {
        $statement = $this->_dbh->prepare($sql);

        foreach ( $parameters as $index => $parameter ) {
            $statement->bindValue($index + 1, $parameter);
        }

        try {
            $statement->execute();
        } catch ( PDOException $e ) {
            $this->_errors[] = $e->getMessage();
            return false;
        }

    	$this->_affected_rows = $statement->rowCount();
        $this->_statement = $statement;
    	return true;
    }

   /**
    *  Sets the fetch mode.
    *
    *  @param string $fetch_style    Controls how the rows will be returned.
    *  @param obj $obj               The object to be fetched into for use with
    *                                FETCH_INTO.
    *  @access protected
    *  @return int
    */
    protected function _set_fetch_mode($fetch_style, $obj = null) {
        switch ( $fetch_style ) {
            case 'assoc':
                $this->_statement->setFetchMode(PDO::FETCH_ASSOC);
                break;
            case 'into':
                $this->_statement->setFetchMode(PDO::FETCH_INTO, $obj);
                break;
            default:
                $this->_statement->setFetchMode(PDO::FETCH_ASSOC);
                break;
        }
    }

   /**
    *  Updates a record in the database.
    *
    *  @param string $table    The table containing the record to be inserted.
    *  @param array $data      An array containing the data to be inserted.
    *                          Format should be as follows:
    *                          array('column_name' => 'column_value');
    *  @param array $where     The WHERE clause of the SQL query.
    *  @access public
    *  @return bool
    */
    public function update($table, $data, $where = null) {
        $sql = "UPDATE {$table} SET ";

        $key_names = array_keys($data);
    	foreach ( $key_names as $name ) {
            $sql .= "{$name}=:{$name}, ";
    	}

        $sql = rtrim($sql, ', ');

        if ( !is_null($where) ) {
    	    $sql .= ' WHERE ';
            foreach ( $where as $name => $val ) {
                $sql .= "{$name}=:{$name}_where, ";
                $data["{$name}_where"] = $val;
            }
        }
    	$statement = $this->_dbh->prepare($sql);

        try {
            $statement->execute($data);
        } catch ( PDOException $e ) {
            $this->_errors[] = $e->getMessage();
            return false;
        }

    	$this->_affected_rows = $statement->rowCount();
    	return true;
    }

   /**
    *  Inserts or updates (if exists) a record in the database.
    *
    *  @param string $table    The table containing the record to be inserted.
    *  @param array $data      An array containing the data to be inserted.
    *                          Format should be as follows:
    *                          array('column_name' => 'column_value');
    *  @access public
    *  @return bool
    */
    public function upsert($table, $data) {
        $sql = "INSERT INTO {$table}";

        $key_names = array_keys($data);
    	$fields = implode(', ', $key_names);
        $values = ':' . implode(', :', $key_names);


        $sql .= "({$fields}) VALUES ({$values}) ON DUPLICATE KEY UPDATE ";

    	foreach ( $key_names as $name ) {
            $sql .= "{$name}=:{$name}, ";
    	}

        $sql = rtrim($sql, ', ');
    	$statement = $this->_dbh->prepare($sql);

        try {
            $statement->execute($data);
        } catch ( PDOException $e ) {
            $this->_errors[] = $e->getMessage();
            return false;
        }

    	$this->_affected_rows = $statement->rowCount();
        return true;
    }
}

?>
