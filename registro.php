<?php
session_start();
include 'conexion.php';

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $contraseña = $_POST['contraseña'] ?? '';
    $repetir = $_POST['repetir'] ?? '';

    if ($contraseña !== $repetir) {
        $error = "Las contraseñas no coinciden";
    } else {

        $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE correo = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();

        if ($stmt->get_result()->num_rows > 0) {
            $error = "Este correo ya está registrado";
        } else {
            $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, correo, contraseña) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nombre, $correo, $contraseña);

            if ($stmt->execute()) {
                $_SESSION['usuario'] = $correo;
                $_SESSION['usuario_nombre'] = $nombre;
                header("Location: noticias.php");
                exit();
            } else {
                $error = "Error al registrar: " . $conexion->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device=width, initial-scale=1.0"/>
    <title>Registro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff;
            margin: 0;
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
            font-size: 24px;
            font-weight: bold;
        }
        .logo img{
            height: 50px;
            margin-right: 10px;
        }
        .informacion a {
            color: white;
            margin-left: 20px;
            text-decoration: none;
            font-size: 14px;
            font-family: 'Open Sans Regular';
        }
        .contenedor {
            display: flex;
            justify-content: center;
            align-items: start;
            gap: 40px;
            padding: 40px;
            margin-top: 20px;
        }
        .formulario, .admin{
            border: 2px solid #000;
            padding: 25px;
            border-radius: 10px;
            width: 350px;
        }
        .formulario h2 {
            text-align: center;
            margin-bottom: 10px;
            font-family: 'Open Sans Bold';
        }
        .formulario p{
            text-align: center;
            margin-bottom: 20px;
            font-family: 'Open Sans Regular';
        }
        .formulario label{
            font-family: 'Open Sans Bold';
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            background-color: #d9d9d9;
            border: none;
            border-radius: 5px;
        }
        .botones {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }
        .botones button {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            border: none;
            cursor: pointer;
            background-color: #f0f0f0;
            border-radius: 5px;
            font-weight: hold;
            font-family: 'Open Sans Regular';
        }
        .botones img {
            height: 20px;
            margin-right: 8px;
        }
        .admin h4 {
            margin-bottom: 15px;
        }
        .admin input {
            width: 100%;
            padding: 10px;
            background-color: #d9d9d9;
            border: none;
            border-radius: 5px;
        }
        .admin button {
            width: 100%;
            margin-top: 20px;
            padding: 10px;
            background-color: #0d5c9b;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
        }
        .terminos {
            display: flex;
            align-items: center;
            margin-top: 20px;
        }
        .terminos input {
            margin-right: 8px;
        }
        .terminos label {
            font-family: 'Open Sans Regular';
        }
        .formulario .continuar {
            width: 100%;
            background-color: #0d5c9b;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 6px;
            margin-top: 20px;
            font-weight: bold;
            cursor: pointer;
            font-family: 'Open Sans Regular';
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="imagenes/logo.png" alt="logo">
        </div>
        <div class="informacion">
            <a href="#">Contacto</a>
            <a href="sobrenosotros.html">Sobre Nosotros</a>
            <a href="login.php">Iniciar Sesión</a>
        </div>
    </header>

    <div class="contenedor">
        <form method='POST' action="registro.php" class="formulario"  id="formRegistro">
            <h2>Registro</h2>
            <p>Ingresa los siguientes datos para completar el registro</p>

            <label for="nombre">Nombre y Apellido</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="correo">Correo Electronico</label>
            <input type="email" id="correo" name="correo" required>

            <label for="contraseña">Contraseña</label>
            <input type="password" id="contraseña" name="contraseña" required>

            <label for="repetir">Repetir Contraseña</label>
            <input type="password" id="repetir" name="repetir" required>

            <?php if (isset($error)): ?>
                <div style="color:red; margin:10px 0;"><?= htmlspecialchars ($error) ?></div>
            <?php endif; ?>

            <div class="botones">
                <button type="button"><img src="imagenes/google.png" alt="Google">Continuar con Google</button>
                <button type="button"><img src="imagenes/outlook.png" alt="Outlook">Continuar con Outlook</button>
            </div>

            <div class="terminos">
                <input type="checkbox" required>
                <label>He leido y acepto los términos y condiciones</label>
            </div>

            <button type="submit" class="continuar" id="btnContinuar">Continuar</button>
        </form>

        <script>
            document.getElementById('formRegistro').addEventListener('submit', function(e) {
                const contraseña = document.getElementById('contraseña').value;
                const repetir = document.getElementById('repetir').value;

                if (contraseña !== repetir) {
                    e.preventDefault();
                    alert("¡Las contraseñas no coinciden!");
                    return;
                }

                const usuarios = JSON.parse(localStorage.getItem('usuarios')) || [];
                if (usuarios.some(user => user.correo === correo)) {
                    alert("Este correo ya esta registrado!");
                    return;
                }
            });
        </script>     
</body>     
</html>