<?php
class Database
{
    private static $dsn;
    private static $username;
    private static $password;
    private static $db;

    public static function getDB(){
        if(!isset(self::$dsn)){
            self::$dsn = getenv('DB_DSN');
            self::$username = getenv('DB_USERNAME');
            self::$password = getenv('DB_PASSWORD');
        }
        // Si la conexión aún no ha sido creada
        if (!isset(self::$db)) {

            try {
                // Creando la instancia PDO usando el DSN, el usuario y la contraseña
                self::$db = new PDO(self::$dsn, self::$username, self::$password);
            } catch (PDOException $e) {
                // Capturando cualquier error de conexión
                $error_message = $e->getMessage();
                // queda el include a la pagina de error, ANADIR PAGINA DE ERROR EN EL FUTURO
                exit("error con la conexion de la base de datos: $error_message");
            }
        }
        // Devolviendo la conexión activa
        // Si ya existía, se devuelve inmediatamente sin volver a crearla
        return self::$db;
    }
}