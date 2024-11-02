<?php
// Habilita CORS si es necesario
header(header: 'Access-Control-Allow-Origin: *');
header(header: 'Content-Type: application/json; charset=UTF-8');

// Autoload de clases o inclusión de archivos necesarios
require_once 'includes.php';

// Crear instancia de Database y comprobar la conexión
$database = new Database();
list($dbConnection, $dbError) = $database->getConnection(); // Descomponemos el array retornado

if (!$dbConnection) {
    // Si no hay conexión, mostrar el mensaje de error
    mostrarMensajeAPIEnEjecucion(mensajeAdicional: $dbError ?? "Error al conectar a la base de datos.");
    exit;
}


// Define las rutas de los controladores
$routes = [
    'login' => 'controllers/loginController.php',
    'usuario/obtenerporid' => 'controllers/usuarioController.php'
];

// Obtener la ruta solicitada (ejemplo: `http://tudominio.com/index.php?route=login`)
$route = isset($_GET['route']) ? $_GET['route'] : null;

// Si no se proporciona ninguna ruta, mostrar HTML con mensaje y fondo
if (is_null(value: $route) || empty($route)) {
    mostrarMensajeAPIEnEjecucion();
    exit;
}

// Verificar si la ruta existe
if (array_key_exists(key: $route, array: $routes)) {
    // Incluye el archivo del controlador correspondiente
    require_once $routes[$route];

    // Instanciar el controlador y llamar al método apropiado
    switch ($route) {
        case 'login':
            $controller = new LoginController();
            $controller->login();
            break;
        case 'usuarios/obtenerporid':
            $controller = new UsuarioController();
            $id = $_GET['id'] ?? null; // Suponiendo que el ID viene como parámetro GET
            if ($id) {
                $controller->obtenerPorId(id: $id);
            } else {
                echo json_encode(value: [
                    'status' => 'error',
                    'message' => 'ID es requerido',
                    'data' => null
                ]);
            }
            break;

        default:
            echo json_encode(value: ['status' => 'error', 'message' => 'Ruta no válida']);
            break;
    }
} else {
    // Ruta no encontrada
    echo json_encode(value: ['status' => 'error', 'message' => 'Recurso no encontrado']);
}

// Función para mostrar el mensaje de "API en ejecución"
function mostrarMensajeAPIEnEjecucion($mensajeAdicional = null): void
{
    header(header: 'Content-Type: text/html; charset=UTF-8');

    echo '<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>API en Ejecución</title>
            <style>
                body, html {
                    margin: 0;
                    padding: 0;
                    height: 100%;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    font-family: Arial, sans-serif;
                    color: white;
                    text-align: center;
                    position: relative; /* Para el pseudo-elemento */
                }

                /* Pseudo-elemento para la imagen de fondo */
                body::before {
                    content: "";
                    position: fixed; /* Usar fixed para cubrir toda la pantalla */
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background-image: url("src/img/Inicio.jpg");
                    background-size: cover; /* La imagen cubre todo el área */
                    background-position: center; /* Centra la imagen */
                    background-repeat: no-repeat; /* Evita que la imagen se repita */
                    opacity: 0.7; /* Ajusta la opacidad según sea necesario */
                    z-index: 1; /* Asegura que esté detrás del contenido */
                }

                /* Capa oscura encima de la imagen */
                .overlay {
                    position: fixed; /* Usar fixed para cubrir toda la pantalla */
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(0, 0, 0, 0.76); /* Capa negra con opacidad */
                    z-index: 2; /* Asegura que esté por encima de la imagen */
                }

                .container {
                    position: relative;
                    z-index: 3; /* Asegura que el contenido esté por encima */
                    display: flex;
                    flex-direction: column; /* Alineación vertical */
                    justify-content: center; /* Centra el contenido verticalmente */
                    align-items: center; /* Centra el contenido horizontalmente */
                    height: 100%; /* Asegura que el contenedor ocupe toda la altura */
                }

                h1 {
                    font-size: 3em;
                    border-radius: 10px;
                }

                h2 {
                    font-size: 1em;
                    background: rgb(176 112 112 / 75%);
                    border-radius: 10px;
                    padding: 1px 8px 1px 8px;
                    color: black;
                }
            </style>
        </head>
        <body>
            <div class="overlay"></div>
            <div class="container">
                <h1>API en ejecución</h1>';

    // Mostrar el h2 solo si $mensajeAdicional no es null
    if ($mensajeAdicional !== null) {
        echo '<h2>' . htmlspecialchars(string: $mensajeAdicional) . '</h2>';
    }

    echo '</div>
        </body>
        </html>';
}






