<?php
class OpportunityDB{

    /**** Obtener y filtrar oportunidades ****/
    // Metodo estatico para obtener todas las oportunidades de la base de datos
    // aplica opcionalmente filtros al introducirse un texto de busqueda o activar el checkbox de "mostrar solo vencidas"
    public static function getAndFilterOpportunities($search = '', $showExpired = false, $sort = 'asc') {
        // Obtener la conexion PDO a la base de datos
        $db = Database::getDB();

        // Query base para obtener todas las oportunidades
        // Usar "WHERE 1 = 1" permite agregar condiciones AND de forma sencilla
        // simplificando la manera de manejar casos especiales
        $query = "SELECT * FROM opportunities WHERE 1 = 1";

        // Arreglo que almacena los parametros del query preparada
        $params = [];

        // Si el usuario escribe algo en la barra de busqueda
        if (!empty($search)) {
            // Limpiando espacios extra al inicio y al final del texto de busqueda
            $search = trim($search);

            // Llenando el placeholder :search con el texto entre % para una busqueda parcial
            $params[':search'] = "%$search%";

            // Intentando generar una version "singular" del termino
            $root = $search;

            // Usando funciones multibyte para manejar correctamente casos como el uso de acentos
            if (mb_strlen($search, 'UTF-8') > 3) {

                $lastChar = mb_substr($search, -1, 1, 'UTF-8'); // ultima letra
                $lastTwo  = mb_substr($search, -2, 2, 'UTF-8'); // ultimas dos

                // Caso de palabras terminando en "es" (investigaciones, investigacion)
                if ($lastTwo === 'es') {
                    $root = mb_substr($search, 0, -2, 'UTF-8');
                }
                // Caso de palabras terminando solo en "s" (empleos, empleo)
                elseif ($lastChar === 's') {
                    $root = mb_substr($search, 0, -1, 'UTF-8');
                }
            }

            // Buscando coincidencias con el texto en titulo, sponsor, descripcion y tipo
            $query .= " AND (title LIKE :search OR sponsor LIKE :search OR description LIKE :search OR type LIKE :search";

            // Si se encuentra una raiz distinta se incluye en el query para evitar hacer la misma busqueda
            if ($root !== $search) {
                $query .= " OR title LIKE :search_root OR sponsor LIKE :search_root OR description LIKE :search_root OR type LIKE :search_root";
                $params[':search_root'] = "%$root%";
            }

            // Cerrando el parentesis del AND
            $query .= ")";
        }

        // Si el filtro de solo vencidas esta activado
        if ($showExpired) {
            // Agregar condicion para que la fecha limite exista y sea pasada
            $query .= " AND deadline IS NOT NULL AND deadline < CURDATE()";
        }

        // Normalizar sort
        $sort = strtolower($sort) === 'asc' ? 'asc' : 'desc';

        // Solo ordenar si el usuario lo pidió
        if ($sort === 'asc' || $sort === 'desc') {
            // Solo ordenar por deadline cuando existe
                $query .= " ORDER BY 
                    (deadline IS NULL),
                    deadline $sort";
}


        // Preparando el query
        $statement = $db->prepare($query);
        // Asociando los parametros a sus valores
        foreach ($params as $key => $value) {
            $statement->bindValue($key, $value);
        }
        // Ejecutando el query
        $statement->execute();
        // Obteniendo todas las filas
        $rows = $statement->fetchAll();
        // Cerrando el cursor para liberar recursos
        $statement->closeCursor();

        // Crear un arreglo de objetos Opportunity
        $opportunities = [];
        foreach($rows as $row){
            $opportunities[] = new Opportunity(
                $row['title'],
                $row['description'],
                $row['sponsor'],
                $row['type'],
                $row['opp_id'],
                $row['url'],
                $row['attachment_path'],
                $row['deadline'],
                $row['date_posted'],
                $row['posted_by'],
            );
        }
        // Devolviendo el arreglo de oportunidades
        return $opportunities;
    }

    /**** Crear oportunidad ****/
    // Metodo estatico para insertar una nueva oportunidad en la base de datos
    public static function create_opportunity(Opportunity $opp){
        // Obteniendo la conexion a la base de datos
        $db = Database::getDB();

        // Query de insercion
        // en este query no se usa opp_id ya que esta como autoincrement en la tabla de la db
        $query = "INSERT INTO opportunities (title, description, sponsor, type, url, attachment_path, deadline, date_posted, posted_by)
            VALUES (:title, :description, :sponsor, :type, :url, :attachment_path, :deadline, :date_posted, :posted_by)";
        // Preparando el query
        $statement = $db->prepare($query);
        // Vinculando cada parametro con su valor correspondiente
        $statement->bindValue(':title', $opp->getTitle());
        $statement->bindValue(':description', $opp->getDescription());
        $statement->bindValue(':sponsor', $opp->getSponsor());
        $statement->bindValue(':type', $opp->getType());
        $statement->bindValue(':url', $opp->getUrl());
        $statement->bindValue(':attachment_path', $opp->getAttachmentPath());
        $statement->bindValue(':deadline', $opp->getDeadline());
        $statement->bindValue(':date_posted', $opp->getDatePosted());
        $statement->bindValue(':posted_by', $opp->getPostedBy());
        // Ejecutando el query
        $statement->execute();
        // Cerrando el cursor para liberar recursos
        $statement->closeCursor();
    }

    /**** Editar Oportunidad ****/
    // Metodo estatico para actualizar los datos de una oportunidad existente
    public static function edit_opportunity(Opportunity $opp){
        // Obteniendo la conexion a la base de datos
        $db = Database::getDB();

        // Query de actualizacion
        // Actualiza todos los campos excepto date_posted y posted_by que permanecen sin cambios
        $query = "UPDATE opportunities
                    SET title = :title,
                        description = :description,
                        sponsor = :sponsor,
                        url = :url,
                        attachment_path = :attachment_path,
                        deadline = :deadline,
                        type = :type
                    WHERE opp_id = :opp_id";
        // Preparando el query
        $statement = $db->prepare($query);
        // Vinculando cada parametro con su valor correspondiente
        $statement->bindValue(':opp_id', $opp->getOppId());
        $statement->bindValue(':title', $opp->getTitle());
        $statement->bindValue(':description', $opp->getDescription());
        $statement->bindValue(':sponsor', $opp->getSponsor());
        $statement->bindValue(':url', $opp->getUrl());
        $statement->bindValue(':attachment_path', $opp->getAttachmentPath());
        $statement->bindValue(':deadline', $opp->getDeadline());
        $statement->bindValue(':type', $opp->getType());
        // Ejecutando el query
        $statement->execute();
        // Cerrando el cursor para liberar recursos
        $statement->closeCursor();
    }

    /**** Borrar Oportunidad ****/
    // Metodo estatico para eliminar una oportunidad de la base de datos
    public static function delete_opportunity($opp_id){
        // Obteniendo la conexion a la base de datos
        $db = Database::getDB();

        // Query de eliminacion basado en el ID de la oportunidad
        $query = "DELETE FROM opportunities
                WHERE opp_id = :opp_id";
        // Preparando el query
        $statement = $db->prepare($query);
        // Vinculando el parametro opp_id con su valor
        $statement->bindValue(':opp_id', $opp_id);
        // Ejecutando el query
        $statement->execute();
        // Cerrando el cursor para liberar recursos
        $statement->closeCursor();
    }

    /**** Borrar archivo adjunto de oportunidad ****/
    // Metodo estatico para remover el archivo adjunto de una oportunidad especifica
    // Establece el campo attachment_path como NULL sin eliminar la oportunidad completa
    public static function delete_file_from_opportunity($opp_id, $attachment_path){
        // Obteniendo la conexion a la base de datos
        $db = Database::getDB();

        // Query de actualizacion que establece attachment_path como NULL
        // Se verifica tanto el opp_id como el attachment_path para mayor seguridad
        $query = "UPDATE opportunities
                SET attachment_path = NULL
                WHERE opp_id = :opp_id
                AND attachment_path = :attachment_path";
        // Preparando el query
        $statement = $db->prepare($query);
        // Vinculando los parametros con sus valores correspondientes
        $statement->bindValue(':attachment_path', $attachment_path);
        $statement->bindValue(':opp_id', $opp_id);
        // Ejecutando el query
        $statement->execute();
        // Cerrando el cursor para liberar recursos
        $statement->closeCursor();
    }

    /**** Obtener oportunidad por ID (util para mantener los campos con la info de la respetiva oportunidad al editarla) ****/
    // Metodo estatico que busca y devuelve una oportunidad especifica por su ID
    // Util para llenar formularios de edicion con los datos actuales
    public static function findOpportunityById($opp_id){
        // Obteniendo la conexion a la base de datos
        $db = Database::getDB();

        // Query para seleccionar una oportunidad especifica por su ID
        $query = "SELECT * FROM opportunities WHERE opp_id = :opp_id";
        // Preparando el query
        $statement = $db->prepare($query);
        // Vinculando el parametro opp_id con su valor
        $statement->bindValue(':opp_id', $opp_id);
        // Ejecutando el query
        $statement->execute();
        // Obteniendo una sola fila como arreglo asociativo
        $row = $statement->fetch();
        // Cerrando el cursor para liberar recursos
        $statement->closeCursor();

        // Si se encontro una oportunidad con ese ID
        if($row) {
            // Creando y devolviendo un objeto Opportunity con los datos obtenidos
            return new Opportunity(
                $row['title'],
                $row['description'],
                $row['sponsor'],
                $row['type'],
                $row['opp_id'],
                $row['url'],
                $row['attachment_path'],
                $row['deadline'],
                $row['date_posted'],
                $row['posted_by']
            );
        } else {
            // Si no se encuentra la oportunidad devolvemos null
            return null;
        }
    }
}