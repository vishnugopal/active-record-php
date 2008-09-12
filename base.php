<?php

namespace ActiveRecord;

class Base {
  protected static $database;
  protected static $table;
  protected static $primary_key_field = 'id';
  protected static $associations = array();
  
  public static function establish_connection($db_settings_name) {
    $obj_db = new ::PDO( 
      'mysql:host=' . $db_settings_name['host'] .
      ';dbname=' . $db_settings_name['database'],
      $db_settings_name['username'],
      $db_settings_name['password']
      );
    $obj_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    static::$database = $obj_db; 
  }
    
  protected static function table_name() {
    return isset(static::$table_name) ?: strtolower(static::$class) . 's';
  }
  
  protected static function primary_key_field() {
    return static::$primary_key_field;
  }
  
  protected static function associations() {
    return static::$associations;
  }
  
  protected static function database() {
    return static::$database;
  }
  
  public static function find_all($options = array()) {
    $sql = 'SELECT * from ' . self::table_name() .  
      (isset($options['conditions'])  ? ' WHERE ' . $options['conditions']  : '') . 
      (isset($options['limit'])       ? ' LIMIT ' . $options['limit']       : '') . ';';
    
    $statement = self::database()->prepare($sql);
    $statement->execute();
    
    return self::to_objects($statement->fetchAll(PDO::FETCH_ASSOC));
  }
  
  private static function to_objects($result_set_array) {
    $object_list = array();
    foreach($result_set_array as $result_set) {
      $object_list[] = self::to_object($result_set);
    }
    return $object_list;
  }
  
  private static function to_object($result_set) {
    $object = new static::$class;
    $object->from_array($result_set);
    return $object;
  }
  
  /* Instance Methods */
  protected $row;
  
  public function __construct() {
    
  }
  
  public function from_array($result_set) {
    $this->row = $result_set;
  }
  
  public function save() {    
    if(!isset($this->row) || 0 == count($this->row)) {
      throw new Exception("Can't save empty record.");
    }
    
    $sql_fields = '';
    foreach($this->row as $key => $value) {
      $value = self::database()->quote($value);
      $sql_fields .= $key . ' = ' . $value . ', ';
    }
    $sql_fields = substr($sql_fields, 0, strlen($sql_fields) - 2);
    if(!isset($this->row[self::primary_key_field()])) {
      throw new Exception("Primary key not set for row, cannot save.");
    }
    $primary_key_value = $this->row[self::primary_key_field()];
    
    $sql = 'UPDATE ' . self::table_name() . ' SET ' . $sql_fields . 
      ' WHERE ' . self::primary_key_field() . ' = ' . $primary_key_value;
      
    return $this->database()->exec($sql); 
  }
    
  public function __call($method, $arguments) {
    if(isset($this->row[$method])) {
      return $this->row[$method];
    } elseif("_set" == substr($method, -4)) {
      if((1 != count($arguments)) || !is_scalar($arguments[0])) {
        throw new Exception("Must have one (and just one) scalar value to set.");
      }
      $property = substr($method, 0, strlen($method) - 4);
      $this->row[$property] = $arguments[0];
    } elseif($this->association_exists($method)) {
      return $this->association_find($method);
    } else {
      throw new Exception("Property not found in record.");
    }
  }
  
  protected function association_exists($association_name) {
   $associations = $this->associations();
   return isset($associations['has_many'][$association_name]);
  }
  
  protected function association_foreign_key($association_name) {
    $associations = $this->associations();
    return $associations['has_many'][$association_name]['foreign_key_field'];
  }
  
  protected function association_table_name($association_name) {
    $associations = $this->associations();
    return $associations['has_many'][$association_name]['table_name'];
  }
  
  protected function association_model($association_name) {
    $associations = $this->associations();
    return $associations['has_many'][$association_name]['model'];
  }
  
  protected function association_find($association_name) {
    $primary_key_field = static::primary_key_field();
    $primary_key_value = static::database()->quote($this->$primary_key_field());
    $conditions = $this->association_foreign_key($association_name) . ' = ' . $primary_key_value;
    $association_model = $this->association_model($association_name);
    $find_array = call_user_func("$association_model::find_all", 
      array('conditions' => $conditions)
    );
    return $find_array;
  }
  
}