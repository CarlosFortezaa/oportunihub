<?php
// Adjust BASE_URL according to your folder name under htdocs
// Example: if you use http://localhost/oportunihub/public

// Ruta absoluta en el sistema de archivos hacia la raíz del proyecto
define("APP_ROOT", realpath(__DIR__ . '/..'));

// URL base utilizada en toda la aplicación para generar enlaces.
// define("BASE_URL", "https://136.145.29.193/~carforhe/CCOM4019/Project/CCOM_4019_OportuniHub/public");
define("BASE_URL", "http://localhost/Coding/oportunihub/public");




// Ruta absoluta en el sistema de archivos donde se almacenan los archivos subidos (PDFs, imágenes, etc.).
define('UPLOAD_DIR', __DIR__ . '/../../public/uploads/');