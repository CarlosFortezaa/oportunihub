<?php
class UserDB {

    public static function create_user(User $user) {
        $db = Database::getDB();
        // Verificar si el usuario existe
        if(self::findByUserId($user->getUserId())){
            return false; // El usuario o ya existe
        }
        if(self::findByEmail($user->getEmail())){
            return false; // El email ya esta registrado
        }
        // el query insert inserta valores especificos
        $query = "INSERT INTO users (user_id, password_hash, role, email)
                  VALUES (:user_id, :password_hash, :role, :email)";
         //se prepara la data para ejecutarse
        $statement = $db->prepare($query);
        $statement->bindValue(':user_id', $user->getUserId());
        $statement->bindValue(':password_hash', $user->getPasswordHash());
        $statement->bindValue(':role', $user->getRole());
        $statement->bindValue(':email', $user->getEmail());

        $ok = $statement->execute();

        $statement->closeCursor();

        return $ok;
    }

  //busca y presenta todos los users
    public static function get_users() {
        $db = Database::getDB();

        $query = "SELECT user_id, password_hash, role, email
                  FROM users
                  ORDER BY user_id ASC";
        $statement = $db->prepare($query);
        $statement->execute();
        //se guardan las filas en un array     
        $rows = $statement->fetchAll();
        $statement->closeCursor();

        $users = [];
        foreach ($rows as $row) {
            $users[] = new User(
                $row['user_id'],
                $row['password_hash'],
                $row['role'],
                $row['email']
            );
        }
        return $users;
    }
/// el query busca el usaurio de la tabla user 
    public static function findByUserId($user_id) {
        $db = Database::getDB();

        $query = "SELECT * FROM users WHERE user_id = :user_id";
        $statement = $db->prepare($query);
        $statement->bindValue(':user_id', $user_id);
        $statement->execute();
        $row = $statement->fetch();
        $statement->closeCursor();

        if ($row) {
            return new User(
                $row['user_id'],
                $row['password_hash'],
                $row['role'],
                $row['email']
            );
        } else {
            return null;
        }
    }

    /// el query busca el email de la tabla user 
    public static function findByEmail($email) {
        $db = Database::getDB();

        $query = "SELECT * FROM users WHERE email = :email";
        $statement = $db->prepare($query);
        $statement->bindValue(':email', $email);
        $statement->execute();
        $row = $statement->fetch();
        $statement->closeCursor();

        if ($row) {
            return new User(
                $row['user_id'],
                $row['password_hash'],
                $row['role'],
                $row['email']
            );
        } else {
            return null;
        }
    }

    public static function emailInUseByAnotherUser($email, $user_id) {
        $db = Database::getDB();

    if (self::emailInUseByAnotherUser($user_id->getEmail(), $user_id->getUserId())) {
        return false; // Email en uso por otro usuario
    }


        $query = "SELECT user_id FROM users WHERE email = :email AND user_id != :user_id";
        $statement = $db->prepare($query);
        $statement->bindValue(':email', $email);
        $statement->bindValue(':user_id', $user_id);
        $statement->execute();
        
        $exists = $statement->fetch();
        $statement->closeCursor();

        return $exists ? true : false;
}


  
    public static function update_user(User $user) {
        $db = Database::getDB();

        if(self::findByEmail($user->getEmail())){
            return false; // El email ya esta registrado
        }

        if ($user->getPasswordHash() === null) {
            // si no hubo hashing se conserva igual
            $query = "UPDATE users
                      SET email   = :email,
                          role    = :role
                      WHERE user_id = :user_id";
            $statement = $db->prepare($query);
            $statement->bindValue(':user_id', $user->getUserId());
            $statement->bindValue(':email', $user->getEmail());
            $statement->bindValue(':role', $user->getRole());
        } else {
            // else actualiza la data
            $query = "UPDATE users
                      SET email          = :email,
                          role           = :role,
                          password_hash  = :password_hash
                      WHERE user_id = :user_id";
            $statement = $db->prepare($query);
            $statement->bindValue(':user_id', $user->getUserId());
            $statement->bindValue(':email', $user->getEmail());
            $statement->bindValue(':role', $user->getRole());
            $statement->bindValue(':password_hash', $user->getPasswordHash());
        }

        $statement->execute();
        $statement->closeCursor();
    }


public static function update_profile(User $user) {
    $db = Database::getDB();

    if ($user->getPasswordHash()=== null) {
        // actualizar solo email si no hbuo hash
        $query = "UPDATE users
                  SET email = :email
                  WHERE user_id = :user_id";
        $statement = $db->prepare($query);
        $statement->bindValue(":email", $user->getEmail());
        $statement->bindValue(":user_id", $user->getUserId());
    } else {
        // actualizar ambos si hubo hash 
        $query = "UPDATE users
                  SET email = :email,
                      password_hash = :password_hash
                  WHERE user_id = :user_id";
        $statement = $db->prepare($query);
        $statement->bindValue(":email", $user->getEmail());
        $statement->bindValue(":password_hash", $user->getPasswordHash());
        $statement->bindValue(":user_id", $user->getUserId());
    }

    return $statement->execute();
}

    //la funcion delete carga el queery que busca el id en especiico y lo elimi9nas
    public static function delete_user($user_id) {
        $db = Database::getDB();

        $query = "DELETE FROM users WHERE user_id = :user_id";
        $statement = $db->prepare($query);
        $statement->bindValue(':user_id', $user_id);
        $statement->execute();
        $statement->closeCursor();
    }
}
