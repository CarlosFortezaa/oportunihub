<?php
//declaracion clase usario
class User {
    private $user_id, $password_hash, $role, $email;
    // Constructor 

    public function __construct($user_id, $password_hash, $role, $email) {
        $this->user_id = $user_id;
        $this->password_hash = $password_hash;
        $this->role = $role;
        $this->email = $email;
    }
    //metodo para obtener el ID del usuario
    public function getUserId() { 
        return $this->user_id; 
    }
    //metodo para obtener el hash de la contraseña
    public function getPasswordHash() { 
        return $this->password_hash; 
    }
    //metodo para obtener el rol del usuario
    public function getRole() { 
        return $this->role; 
    }
    //metodo para obtener el email del usuario
    public function getEmail() { 
        return $this->email; 
    }

    public function setUserId($user_id){
        $this->user_id = $user_id;
    }

    public function setPasswordHash($password_hash){
        $this->password_hash = $password_hash;
    }

    public function setRole($role){
        $this->role = $role;
    }

    public function setEmail($email){
        $this->email = $email;
    }
}
