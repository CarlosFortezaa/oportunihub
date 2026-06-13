<?php
/* EN LUGARES DONDE SE USA ALGO PARECIDO A ESTO: "/index.php?c=opportunity&a=index"
POR EL MOMENTO SE VA A PONER ASI: "/index.php?action=index" EL ACTION UNO LO DEFINE
POR EJEMPLO COMO AQUI: <form action="index.php?action=create_opportunity" method="post" enctype="multipart/form-data"> <!-- enctype es para poder subir archivos --> */
session_start();

/* LOAD ENV FIRST */
$envPath = __DIR__ . '/../.env';

if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        if (strpos($line, '=') === false)
            continue;

        list($key, $value) = explode('=', $line, 2);

        $key = trim($key);
        $value = trim($value);

        putenv("$key=$value");
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../public/PHPMailer/PHPMailer-master/src/Exception.php';
require_once __DIR__ . '/../public/PHPMailer/PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../public/PHPMailer/PHPMailer-master/src/SMTP.php';
require_once __DIR__ . '/../app/config/config.php';
require_once APP_ROOT . '/config/database.php';
require_once APP_ROOT . '/models/user.php';
require_once APP_ROOT . '/models/opportunity.php';
require_once APP_ROOT . '/models/UserDB.php';
require_once APP_ROOT . '/models/OpportunityDB.php';
require_once APP_ROOT . '/models/Subscription.php';
require_once APP_ROOT . '/util/tags.php';


$action = filter_input(INPUT_POST, 'action');

if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action == NULL) {
        $action = '';
    }
}



switch ($action) {

    //====================================================================== \\
    //                          REGISTER                                     \\
    //====================================================================== \\
    case 'register':
        if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header("Location: index.php?action=opportunities_list");
            exit;
        }

        $errores = []; // para mostrar los mensajes de errores adecuados. solo lo pongo al momento porsiacaso pero no he hecho nada relacionado con los errores
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = trim($_POST['user_id'] ?? '');
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $role = $_POST['role'] ?? 'contributor'; // si no es admin, es contributor. lo puse asi para que por si pasa algun error, el user siempre tenga un rol por default, en este caso default -> contributor

            // Validaciones
            if (!preg_match('/^(?=.*[a-z]\.[a-z])[a-z0-9.]+$/', $user_id)) { // solo permite minusculas, numeros y puntos(.) y asegura que haya almenos un punto con letras a ambos lados
                $errores[] .= "Formato incorrecto usado. Ejemplo: john.doe";
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errores[] .= "Email invalido.";
            }

            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*(\d|[^A-Za-z0-9])).{8,}$/', $password)) { // al menos una minuscula, al menos una mayuscula, almenos un numero o simbolo y minimo 8 caracteres
                $errores[] .= "El password debe tener almenos 8 caracteres e incluir mayusculas, minusculas y un numero o simbolo.";
            }

            if (!$errores) {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);

                // Crear el usuario en la db
                $newUser = new User($user_id, $password_hash, $role, $email);
                if (!userDB::create_user($newUser)) {
                    $errores[] .= "El usuario o correo ya existe.";
                    include APP_ROOT . '/views/auth/register.php';
                    exit;
                } else {
                    $_SESSION['success'] = "Usuario '$user_id' creado exitosamente!";
                    header("Location: index.php?action=manage_users");
                    exit;
                }
            }
        }
        include APP_ROOT . '/views/auth/register.php';
        break;


    //====================================================================== \\
    //                          LOGIN                                        \\
    //====================================================================== \\
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = trim($_POST['user_id']);
            $password = trim($_POST['password']);
            $user = userDB::findByUserId($user_id);

            if ($user && password_verify($password, $user->getPasswordHash())) {
                $_SESSION['user'] = [
                    'id' => $user->getUserId(),
                    'email' => $user->getEmail(),
                    'role' => $user->getRole()
                ];
                header("Location: index.php?action=opportunities_list");
                exit; // despues del redirect no se ejecuta codigo adicional
            } else {
                $errores[] = "Credenciales Incorrectos";
            }
        }
        include APP_ROOT . '/views/auth/login.php';
        break;


    //====================================================================== \\
    //                          LOGOUT                                       \\
    //====================================================================== \\
    case 'logout':
        unset($_SESSION['user']);
        session_destroy();
        header('Location: index.php?action=opportunities_list'); // maybe dejarlo en el home como tal no el login? solo una idea idk
        exit;
        break;


    //====================================================================== \\
    //                          MANAGE_USERS                                  \\
    //====================================================================== \\
    case 'manage_users':
        // verifica si el usuario es admin si no es asi lo devuelve al login
        if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header("Location: index.php?action=opportunities_list");
            exit;
        }
        // obtiene llos usarios
        $users = UserDB::get_users();
        // carga el layout de manage.php
        include APP_ROOT . '/views/users/manage.php';
        break;


    // se define la accion edit o edit_user para editar un usuario
    // si es admin permite editar usuarios
    case 'edit_user_form':
        if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header("Location: index.php?action=opportunities_list");
            exit;
        }

        $user_id = $_GET['id'] ?? null;
        // verifica que el user id no este vacio y que exista el usuario
        if (!$user_id) {
            die("User ID faltante");
        }

        $user = UserDB::findByUserId($user_id);

        if (!$user) {
            die("Usuario no encontrado");
        }
        // si todo esta bien muestra edit.php
        include APP_ROOT . '/views/users/edit.php';
        break;


    //====================================================================== \\
    //                          UPDATE_USER                                   \\
    //====================================================================== \\

    case 'update_user':
        $users = [];
        $errores = [];
        // si el usuario es admin permite  
        if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header("Location: index.php?action=opportunities_list");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_POST['user_id'];
            $email = trim($_POST['email']);
            $role = $_POST['role'];
            $password = trim($_POST['password']);

            $user = UserDB::findByUserId($user_id);

            // validar email repetido
            $emailExistente = UserDB::findByEmail($email);

            // si el email existe y NO es el mismo usuario -> error
            if ($emailExistente && $emailExistente->getUserId() !== $user_id) {
                $errores[] = "El correo ya está en uso.";
            }

            $user->setEmail($email);
            $user->setRole($role);

            // si password no fue cambiado, se queda igual
            if (!empty($password)) {
                if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*(\d|[^A-Za-z0-9])).{8,}$/', $password)) { // al menos una minuscula, al menos una mayuscula, almenos un numero o simbolo y minimo 8 caracteres
                    $errores[] = "El password debe tener almenos 8 caracteres e incluir mayusculas, minusculas y un numero o simbolo.";
                } else {
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);
                    $user->setPasswordHash($password_hash);
                }
            } else {
                $password_hash = null;
            }

            if ($errores) {
                include APP_ROOT . '/views/users/edit.php';
                break;
            }

            UserDB::update_user($user);

            $_SESSION['success'] = "Perfil '$user_id' actualizado exitosamente.";
            header("Location: index.php?action=manage_users");
            exit;
        }
        break;


    //====================================================================== \\
    //                          DELETE_USER                                   \\
    //====================================================================== \\
    //primero se verifica que el usuario sea admin para que pueda borrar usuarios
    //(medio redundante?)
    case 'delete_user':
        if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header("Location: index.php?action=opportunities_list");
            exit;
        }

        $user_id = $_GET['id'] ?? null;
        //si el user id no esta vacio borra el usuario (aunque parece redundante funciona para que no lo traten borrar desde el url)
        if ($user_id) {
            UserDB::delete_user($user_id);
            $_SESSION['success'] = "Usuario '$user_id' fue borrado exitosamente.";
        }

        header("Location: index.php?action=manage_users");
        exit;


    //====================================================================== \\
    //                          CREATE_OPPORTUNITY                           \\
    //====================================================================== \\

    case 'create_opportunity':
        if (empty($_SESSION['user'])) {
            header("Location: index.php?action=opportunities_list");
            exit;
        }

        $errores = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $sponsor = $_POST['sponsor'] ?? '';
            $url = $_POST['url'] ?? NULL;
            $deadline = !empty($_POST['deadline']) ? $_POST['deadline'] : NULL; // aqui se usa !empty ya que aunque el usuario no envie nada, el "default value" es mm/dd/yyyy. entonces al intentar crear la oportunidad se enviaba un string vacio("") PERO con el !empty, un string vacio se convierte en NULL y NULL si es aceptado por la db, los strings vacios no
            $date_posted = date('Y-m-d H:i:s');
            $posted_by = $_SESSION['user']['id'] ?? null; // aqui se usa session ya que basicamente posted_by = user_id
            $type = $_POST['type'] ?? 'Other';
            $attachment_path = NULL; // default value


            // Subir el archivo
            if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
                $tmp_name = $_FILES['attachment']['tmp_name']; // guarda el file en una carpeta temporal del servidor
                $fileName = $_FILES['attachment']['name']; // nombre del file ej. document.pdf 
                $destPath = UPLOAD_DIR . DIRECTORY_SEPARATOR . $fileName;  // la ruta completa en donde el file se va a guardar

                if (move_uploaded_file($tmp_name, $destPath)) {
                    $attachment_path = 'uploads/' . $fileName;
                }
            }

            if (empty($title)) {
                $errores[] = "El titulo es obligatorio.";
            }
            if (empty($description)) {
                $errores[] .= "La descripcion es obligatoria.";
            }
            if (empty($sponsor)) {
                $errores[] .= "El patrocinador es obligatorio.";
            }
            if ($deadline !== NULL && $date_posted > $deadline) {
                $errores[] .= 'La fecha publicada no puede ser posterior o igual a la fecha limite.';
            }

            if (empty($errores)) {
                $opp_created = new Opportunity($title, $description, $sponsor, $type, null, $url, $attachment_path, $deadline, $date_posted, $posted_by);
                OpportunityDB::create_opportunity($opp_created);
                $_SESSION['success'] = "Oportunidad '$title' creada exitosamente.";

                $emails = Subscription::allEmails();
                $unsubLink = "http://localhost/Coding/OportuniHub/public/index.php?action=unsubscribe_email&email=";
                $descriptionTags = add_tags($description);

                foreach ($emails as $email) {
                    $mail = new PHPMailer(true);
                    try {
                        // Server settings
                        $mail->isSMTP();
                        $mail->Host = getenv('MAILER_HOST');
                        $mail->SMTPAuth = true;
                        $mail->Username = getenv('MAILER_USERNAME');
                        $mail->Password = getenv('MAILER_PASSWORD');
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = getenv('MAILER_PORT');

                        // Recipients
                        $mail->setFrom('oportunihub@gmail.com', 'OportuniHub');
                        $mail->addAddress($email);

                        // Content y tuve que poner el css en cada misma linea pq gmail y lo demas me los ignora si hago una clase desde .css 
                        $mail->isHTML(true);
                        $mail->CharSet = 'UTF-8';
                        $mail->Subject = "Nueva Oportunidad: $title";
                        $mail->Body = "
                         <html>
                         <head>
                             <meta charset='UTF-8'>
                       </head>
                        <body>
                             <h1>Nueva Oportunidad Disponible</h1>
                             <h2 style='font-weight: normal;'><strong>Título:</strong> $title</h2>
                             <p style='font-weight: normal;'><strong>Descripción:</strong>$descriptionTags</p>
                             <h4 style='font-weight: normal;'><strong>Patrocinador:</strong> $sponsor</h4>
                         ";
                        if ($url !== '' && $url !== NULL) {
                            $mail->Body .= "<h4 style='font-weight: normal;'><strong>Link:</strong> $url</h4>";
                        }

                        $mail->Body .= "<h4 style='font-weight: normal;'><strong>Fecha de Publicación:</strong> $date_posted</h4>";

                        if ($deadline !== '' && $deadline !== NULL) {
                            $mail->Body .= "<h4 style='font-weight: normal;'><strong>Fecha de Vencimiento:</strong> $deadline</h4>";
                        }

                        $mail->Body .= "<h4 style='font-weight: normal;'><strong>Tipo: </strong> $type </h4>
                                         <h4 style='font-weight: normal;'><strong>Publicado por:</strong> $posted_by</h4>
                                         <p style='font-weight: normal;'>Si ya no deseas recibir estas oportunidades, puedes <a href='{$unsubLink}" . urlencode($email) . "' style='color: #1a73e8; text-decoration: none;'>darte de baja aquí</a>.</p>
                                     </body>
                                     </html>";

                        $mail->send();
                    } catch (Exception $e) {
                        error_log("El correo a $email no se pudo enviar. Mailer Error: {$mail->ErrorInfo}");
                    }
                }

                header("Location: index.php?action=opportunities_list");
                exit;
            }
        }
        include APP_ROOT . '/views/opportunities/opportunity_form.php';
        break;


    //====================================================================== \\
    //                          EDIT_OPPORTUNITY                             \\
    //====================================================================== \\
    case 'edit_opportunity':
        $errores = [];
        $opp_id = $_GET['opp_id'] ?? NULL;
        $opportunity = OpportunityDB::findOpportunityById($opp_id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $opp_id = $_POST['opp_id'] ?? '';
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $sponsor = $_POST['sponsor'] ?? '';
            $url = $_POST['url'] ?? NULL;
            $deadline = !empty($_POST['deadline']) ? $_POST['deadline'] : NULL; // aqui se usa !empty ya que aunque el usuario no envie nada, el "default value" es mm/dd/yyyy. entonces al intentar crear la oportunidad se enviaba un string vacio("") PERO con el !empty, un string vacio se convierte en NULL y NULL si es aceptado por la db, los strings vacios no
            $date_posted = date('Y-m-d H:i:s');
            $type = $_POST['type'] ?? 'Other';
            $attachment_path = $opportunity->getAttachmentPath(); // para mantener el mismo file por si no suben uno nuevo


            // Subir el archivo
            if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
                $tmp_name = $_FILES['attachment']['tmp_name']; // guarda el file en una carpeta temporal del servidor
                $fileName = $_FILES['attachment']['name']; // nombre del file ej. document.pdf 
                $destPath = UPLOAD_DIR . DIRECTORY_SEPARATOR . $fileName;  // la ruta completa en donde el file se va a guardar

                if (move_uploaded_file($tmp_name, $destPath)) {
                    $attachment_path = 'uploads/' . $fileName;
                }
            }

            if (empty($title)) {
                $errores[] .= "El titulo es obligatorio.";
            }
            if (empty($description)) {
                $errores[] .= "La descripcion es obligatoria.";
            }
            if (empty($sponsor)) {
                $errores[] .= "El patrocinador es obligatorio.";
            }
            if ($deadline !== NULL && $date_posted > $deadline) {
                $errores[] .= 'La fecha publicada no puede ser posterior o igual a la fecha limite.';
            }

            if (empty($errores)) {
                $opportunity->setTitle($title);
                $opportunity->setDescription($description);
                $opportunity->setSponsor($sponsor);
                $opportunity->setType($type);
                $opportunity->setUrl($url);
                $opportunity->setAttachmentPath($attachment_path);
                $opportunity->setDeadline($deadline);

                OpportunityDB::edit_opportunity($opportunity);
                $_SESSION['success'] = "Oportunidad '$title' editada exitosamente.";
                header("Location: index.php?action=opportunities_list");
                exit;
            }
        }
        include APP_ROOT . '/views/opportunities/edit_opportunity_form.php';
        break;




    //====================================================================== \\
    //                          DELETE_FILE_FROM_OPPORTUNITY                 \\
    //====================================================================== \\
    case 'delete_file_from_opportunity':
        $opp_id = $_GET['opp_id'];
        $opp = OpportunityDB::findOpportunityById($opp_id);
        if ($_SESSION['user']['id'] === $opp->getPostedBy() || $_SESSION['user']['role'] === 'admin') {
            OpportunityDB::delete_file_from_opportunity($opp_id, $opp->getAttachmentPath());
            if ($opp->getAttachmentPath()) {
                unlink($opp->getAttachmentPath()); // para borrar el attachment de la db
            }
            header("Location: index.php?action=edit_opportunity&opp_id=$opp_id");
            exit;
        }
        include APP_ROOT . '/views/opportunities/opportunities_list.php';
        break;


    //====================================================================== \\
    //                          SUBSCRIPTION_LIST                            \\
    //====================================================================== \\
    // (traer los emails desde el modelo)
    case 'subscriptions_list':
        $emails = Subscription::allEmails();
        include APP_ROOT . '/views/subscriptions/manage.php';
        break;


    //====================================================================== \\
    //                          SUBSCRIBE_EMAIL                              \\
    //====================================================================== \\
    case 'subscribe_email':
        $emails = [];
        $errores = [];
        $message = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errores[] = 'Correo inválido.';
            } else if (!Subscription::subscribe($email)) {
                $errores[] = 'El correo ya está registrado.';
            } else {
                $_SESSION['success'] = "Correo '$email' suscrito exitosamente.";
                header("Location: index.php?action=subscriptions_list");
                exit;
            }
        }

        // Siempre cargar todos los emails, aunque haya errores
        $emails = Subscription::allEmails();

        include APP_ROOT . '/views/subscriptions/manage.php';
        break;


    //====================================================================== \\
    //                          UNSUBSCRIBE_EMAIL                            \\
    //====================================================================== \\
    case 'unsubscribe_email':
        $emails = Subscription::allEmails(); // para mostrar lista si es admin
        $errores = [];

        // Obtener email desde POST o GET
        $email = trim($_POST['email'] ?? $_GET['email'] ?? '');

        if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Subscription::unsubscribe($email);
            $_SESSION['success'] = "Correo '$email' dado de baja exitosamente.";

            // Si viene por GET (desde link en email), podemos mostrar un mensaje simple
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                include APP_ROOT . '/views/subscriptions/unsub_success.php';
                exit;
            } else {
                // Si es POST (desde admin) redirige a la lista
                header("Location: index.php?action=subscriptions_list");
                exit;
            }
        } elseif ($email) {
            $errores[] = 'E-mail inválido.';
        }

        include APP_ROOT . '/views/subscriptions/manage.php';
        break;


    //====================================================================== \\
    //                          DELETE_OPPORTUNITY                           \\
    //====================================================================== \\
    case 'delete_opportunity':
        $opp_id = $_GET['opp_id'] ?? NULL;
        $opp = OpportunityDB::findOpportunityById($opp_id); // para validar en el backend los permisos, sin esto no se puede usar $opp->getPostedBy()
        $opp_title = $opp->getTitle();
        if ($_SESSION['user']['id'] === $opp->getPostedBy() || $_SESSION['user']['role'] === 'admin') {
            OpportunityDB::delete_opportunity($opp_id);
            if ($opp->getAttachmentPath()) {
                unlink($opp->getAttachmentPath()); // para borrar el attachment de la db
            }
        }
        $_SESSION['success'] = "Oportunidad '$opp_title' eliminada exitosamente.";
        header("Location: index.php?action=opportunities_list");
        exit;
        break;




    //====================================================================== \\
    //                          PROFILE                                      \\
    //====================================================================== \\
    // Perfil se muestra a cualquier ususario logeado
    case 'profile':
        if (empty($_SESSION['user'])) {
            header("Location: index.php?action=opportunities_list");
            exit;
        }

        $user_id = $_SESSION['user']['id'];
        $user = UserDB::findByUserId($user_id);

        include APP_ROOT . "/views/users/profile.php";
        break;


    //====================================================================== \\
    //                          UPDATE_PROFILE                               \\
    //====================================================================== \\
    case 'update_profile':
        if (empty($_SESSION['user'])) {
            header("Location: index.php?action=opportunities_list");
            exit;
        }

        $user_id = $_SESSION['user']['id'];
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        $user = UserDB::findByUserId($user_id);

        // validar email repetido
        $emailExistente = UserDB::findByEmail($email);

        if ($emailExistente && $emailExistente->getUserId() !== $user_id) {
            $errores[] = "El correo ya está en uso.";
        }

        $user->setEmail($email);

        // si password no fue cambiado, se queda igual
        if (!empty($password)) {
            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*(\d|[^A-Za-z0-9])).{8,}$/', $password)) { // al menos una minuscula, al menos una mayuscula, almenos un numero o simbolo y minimo 8 caracteres
                $errores[] = "El password debe tener almenos 8 caracteres e incluir mayusculas, minusculas y un numero o simbolo.";
            } else {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $user->setPasswordHash($password_hash);
            }
        } else {
            $password_hash = null;
        }

        if ($errores) {
            include APP_ROOT . '/views/users/profile.php';
            break;
        }

        UserDB::update_profile($user);

        // Actualizar sesion
        $_SESSION['user']['email'] = $email;

        // Mensaje de exito
        $_SESSION['success'] = "Perfil '$user_id' actualizado exitosamente.";

        header("Location: index.php?action=profile");
        exit;
        break;



    //====================================================================== \\
    //                          DEFAULT                                      \\
    //====================================================================== \\
    default:
        // Obteniendo el termino de busqueda desde el query string si existe
        // de lo contrario usa un string vacio
        // trim() elimina espacios innecesarios al inicio y al final
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        // Determinando si el checkbox "mostrar solo vencidas" fue seleccionado
        // Esto es TRUE solamente cuando el parametro 'expired' existe y su valor es exactamente '1'
        $showExpired = isset($_GET['expired']) && $_GET['expired'] === '1';

        // Si no hay filtros activos (no hay texto de busqueda y no se selecciona "mostrar solo vencidas")
        // se cargan todas las oportunidades sin filtros
        $sort = $_GET['sort'] ?? 'asc';
        $opportunities = OpportunityDB::getAndFilterOpportunities($search, $showExpired, $sort);

        // Cargando la vista correspondiente
        include APP_ROOT . '/views/opportunities/opportunities_list.php';
        break;
}
