<?php
// Not used
class Client {
   /* Member variables */
   var $id;
   var $name;
   var $email;
   var $password;

   public function __construct($id, $name, $email, $password) {
       $this->id = $id;
       $this->name = $name;
       $this->email = $email;
       $this->password = $password;
   }
   
   /* Member functions */
   function setId($id) {
      $this->id = $id;
   }

   public function getId() {
      return $this->id;
   }

   function setNome($nome) {
      $this->nome = $nome;
   }

   function getNome() {
      return $this->nome;
   }

   function testPassword($password) {
       return $this->password == $password;
   }
}