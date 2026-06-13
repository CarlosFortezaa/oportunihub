<?php

class Subscription
{
    /**
     * Devuelve un arreglo de strings con todos los correos suscritos.
     */
    public static function allEmails()
    {
        $db = Database::getDB();
        $query = "SELECT email FROM distribution_list";
        $statement = $db->prepare($query);
        $statement->execute();
        $rows = $statement->fetchAll();
        $statement->closeCursor();

        $emails = [];
        foreach($rows as $row){
            $emails[] = $row['email'];
        }

        return $emails;
    }

    /**
     * Inserta un email en la lista 
     */
    public static function subscribe($email)
    {
        $db = Database::getDB();
        // Revisar si el email ya existe
        $query = "SELECT * FROM distribution_list WHERE email = :email";
        $statement = $db->prepare($query);
        $statement->bindValue(':email', $email);
        $statement->execute();
        $row = $statement->fetch();
        $statement->closeCursor();

        if($row){
            return false;
        }

        // Si no existe, entonces le hacemos sub
        $query = "INSERT IGNORE INTO distribution_list (email) 
                VALUES (:email)";
        $statement = $db->prepare($query);
        $statement->bindValue(':email', $email);

        $ok = $statement->execute();
        $statement->closeCursor();

        return $ok;
    }

    /**
     * Elimina un email de la lista
     */
    public static function unsubscribe($email)
    {
        $db = Database::getDB();
        $query = "DELETE FROM distribution_list 
                    WHERE email = :email";
        $statement = $db->prepare($query);
        $statement->bindValue(':email', $email);

        $ok = $statement->execute();
        $statement->closeCursor();

        return $ok;
    }
}
