<?php

/***
 * The singleton used to interact with the database
 */
class DB {
    private static $_instance = null;
    private $_pdo,          //The PDO object
        $_query,            //Last query
        $_error = false,    //Query errors
        $_results,          //Results set
        $_count = 0;        //Count of results

    /**
     * Construct the Database Singleton
     */
    private function __construct(){
        try{
            $this->_pdo = new PDO('mysql:host='. Config::get('mysql/host') .';dbname='. Config::get('mysql/db'), Config::get('mysql/username') , Config::get('mysql/password'),
            array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
        }catch(PDOException $e){
            die($e->getMessage());
        }
    }

    /**
     *  Get an instance of the database
     * @return DB
     */
    public static function getInstance(){
        if(!isset(self::$_instance)){
            self::$_instance = new DB();
        }
        return self::$_instance;
    }

    /**
     * Perform a query
     * @param $sql
     * @param array $params
     * @return $this
     */
    public function query($sql, $params = array()){
        $this->_error = false;
        $this->_results = null;
        if($this->_query = $this->_pdo->prepare($sql)){
            $x = 1;
            if(count($params)){
                foreach($params as $param){
                        $this->_query->bindValue($x, $param);
                        $x++;
                }
            }
            //Perform the query
            $this->_query->execute();
            //If the query is INSERT, UPDATE, DELETE
            //It will not return objects so we must check for these statements
            $statement = substr($sql, 0, 6);
            $dataChange = ['INSERT', 'UPDATE', 'DELETE'];
            //errorCode() == 0000 means success
            if($this->_query->errorCode() == 0000 && in_array($statement, $dataChange)){
                $this->_count = $this->_query->rowCount();
            }
            else if($this->_query->errorCode() == 0000){
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                $this->_count = $this->_query->rowCount();
            }else{
                $this->_count = 0;
                $this->_error = true;
            }
        }
        return $this;
    }

    /**
     * Abstract queries
     * Return the object with the results|errors from the query
     * If an incorrect amount of arguments are given return false
     * @param $action
     * @param $table
     * @param array $where
     * @return $this|false
     */
    private function action($action, $table, $where = array()){
        if(count($where) == 3) {
            $operators = array('=', '>', '<', '>=', '<=');
            $field = $where[0];
            $operator = $where[1];
            $value = $where[2];
            if (in_array($operator, $operators)) {
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
                if (!$this->query($sql, array($value))->error()) {
                    return $this;
                }
            }
        }
            return false;
    }

    /**
     * A get query
     * @param $table
     * @param array $where
     * @return $this|DB|false
     */
    public function get($table, $where = array()){
        return $this->action('SELECT *', $table, $where);
    }

    /**
     * Delete a record
     * @param $table
     * @param array $where
     * @return $this|DB|false
     */
    public function delete($table, $where = array()){
        return $this->action('DELETE', $table, $where);
    }

    /**
     * Insert data into a table.
     * @param $table
     * @param $fields; An associative array of field name and value pairings
     * @return true if successful | false if not
     */
    public function insert($table, $fields = array()){
        if(count($fields)){
            $keys = array_keys($fields);
            $values = '';
            $x = 1;
            foreach($fields as $field){
                $values .= '?';
                if($x < count($fields)){
                    $values .= ', ';
                }
                $x++;
            }
            $sql = "INSERT INTO {$table} (".implode(', ', $keys).") VALUES ({$values})";
            $this->query($sql, $fields);
            if(empty($this->error())){
                return true;
            }
        }
        return false;
    }

    /**
     *  Update a record
     * @param $table
     * @param $id
     * @param $fields; An associative array of field names and value pairings
     * @return true if successful | false if not
     */
    public function update($table, $id, $fields){
        $set = '';
        $x = 1;
        foreach($fields as $name => $value){
            $set .= "{$name} = ?";
            if($x < count($fields)) {
                $set .= ', ';
            }
            $x++;
        }
        $sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";
        if(!$this->query($sql, $fields)->error()){
            return true;
        }
    }

    /**
     * Is there an error?
     * @return bool
     */
    public function error(){
        return $this->_error;
    }

    /**
     * Get the number of rows in query
     * @return int
     */
    public function count(){
        return $this->_count;
    }

    /**
     * Get the query results
     * @return NULL| array of objects
     */
    public function results(){
        return $this->_results;
    }

    /**
     * Get the first query result
     * @return NULL | object
     */
    public function first(){
        return $this->_results[0];
    }


}