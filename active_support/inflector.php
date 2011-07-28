<?php
namespace ActiveSupport;

class Inflector {
  
  public static function singularize($value) {
    $value = strtolower(substr($value, 0, strlen($value) - 1));
    $value = preg_replace("/ie$/", "y", $value);
    return $value;
  }
  
  public static function pluralize($value) {
     $value = preg_replace("/y$/", "ie", $value);
     $value = strtolower($value . 's');
     return $value;
  }
  
}
