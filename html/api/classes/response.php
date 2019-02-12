<?php
/**
 *  Object responsible creating the response key-value array
 *
 * @category      Classes
 * @author        Luchev <luchevz@gmail.com>
 * @version       1.0
 * @since         0.1.0
 */

 class Response {
   private $string;
   private $object;

   public function __construct() {
     $object = array();
   }

   function GetObject() {
     return $this->object;
   }

   function GetString() {
     $this->string = json_encode($this->object);
     return $this->string;
   }

   function SetValue($Key, $Value) {
     $this->object[$Key] = $Value;
   }

   function GetValue($Key) {
     if (isset($this->object[$Key])) {
       return $this->object[$Key];
     }
     return NULL;
   }

 }

?>
