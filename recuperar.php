<?php
// librerias PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "comunicado_digital");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];

    $consulta = "SELECT * FROM usuarios WHERE correo='$correo'";
    $resultado = $conexion->query($consulta);

    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();

        // Crear instancia de PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = ''; // correo que permitira mandar mensajes a distintos correos electronicos.
            $mail->Password   = ''; // contraseña generada por gmail del correo.
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            // Remitente y destinatarios
            $mail->setFrom('TUCORREO@gmail.com', 'Comunicado Digital');
            $mail->addAddress($correo);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Recuperacion de contraseña';
            $mail->Body    = 'Hola, tu contraseña es: <b>' . $fila['contraseña'] . '</b>';

            $mail->send();
            $mensaje = "Te hemos enviado un correo con tu contraseña.";
        } catch (Exception $e) {
            $mensaje = "Error al enviar el correo: {$mail->ErrorInfo}";
        }
    } else {
        $mensaje = "Correo no encontrado. Verifica e intenta de nuevo.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #fff;
    }

    header {
      background-color: #0d5c9b;
      color: white;
      padding: 15px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .logo {
      font-size: 20px;
      font-weight: bold;
    }
    .logo img {
      height: 50px;
      margin-right: 10px;
    }

    .redes {
      margin-right: 250px;
    }
    a {
        text-decoration: none;
    }
    .contenedor {
      height: 75vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .formulario-recuperar {
      border: 3px solid black;
      padding: 40px;
      border-radius: 10px;
      text-align: center;
      max-width: 400px;
      width: 100%;
    }

    .formulario-recuperar h2 {
      color: #0056a1;
      margin-bottom: 10px;
      font-family: 'Open Sans Bold';
    }

    .formulario-recuperar p {
      font-size: 14px;
      color: #555;
      margin-bottom: 20px;
      font-family: 'Open Sans Regular';
    }

    .formulario-recuperar label {
      display: block;
      font-weight: bold;
      text-align: left;
      margin-bottom: 5px;
      font-family: 'Open Sans Bold';
    }

    .formulario-recuperar input[type="email"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 14px;
      font-family: 'Open Sans Regular';
    }

    .formulario-recuperar button {
      width: 100%;
      padding: 10px;
      background-color: #0056a1;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
      font-family: 'Open Sans Regular';
    }

    .formulario-recuperar button:hover {
      background-color: #004080;
    }

    .formulario-recuperar .enlace-login {
      display: block;
      margin-top: 15px;
      font-size: 14px;
      color: #0056a1;
      text-decoration: none;
      font-family: 'Open Sans Regular';
    }

    #mensaje {
      margin-top: 15px;
      font-size: 14px;
      color: red;
    }
</style>
</head>
<body>
<header>
<div class="logo">
        <img src="imagenes/logo.png" alt="logo">
      </div>
      <div class="redes">
        <a target="_blank" href="https://www.instagram.com/"><img src="imagenes/instagram.png" height="35"/></a>
        <a target="_blank" href="https://www.facebook.com/"><img src="imagenes/facebook.png" height="35" style="margin-left: 12px;"/></a>
        <a target="_blank" href="https://x.com/?lang=es"><img src="imagenes/X.png" height="35" style="margin-left: 10px;"/></a>
     </div>
</header>

<div class="contenedor">
    <div class="formulario-recuperar">
        <h2>Recupera tu contraseña</h2>
        <p>Ingresa el correo del usuario registrado para recuperar la contraseña</p>
        <form method="POST" action="">
            <label for="correo">Correo Electrónico</label>
            <input type="email" name="correo" placeholder="tu@correo.com" required>
            <button type="submit">Enviar</button>
            <a class="enlace-login" href="login.php">¿Recordaste tu contraseña? Inicia Sesión</a>
        </form>
        <p id="mensaje"><?php echo $mensaje; ?></p>
    </div>
</div>
</body>
</html>
