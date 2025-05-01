<?php 
session_start();
include 'conexion.php';

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $correo = $_POST['correo'] ?? '';
  $contraseña = $_POST['contraseña'] ?? '';

  $stmt = $conexion->prepare("SELECT id, contraseña, nombre FROM usuarios WHERE correo = ? AND contraseña = ?");
  $stmt->bind_param("ss", $correo, $contraseña);
  $stmt->execute();
  $resultado = $stmt->get_result();

  if ($resultado->num_rows === 1) {
    $usuario = $resultado->fetch_assoc();

    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['usuario_correo'] = $correo;
    $_SESSION['usuario_nombre'] = $usuario['nombre'];
    header("Location: noticias.php");
    exit();

  } else {
    $error = "Correo o contraseña incorrectos";
  }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Inicio de Sesión</title>
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
      display: flex;
      align-items: center;
    }
    .logo img {
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

    main {
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    h2 {
      margin-bottom: 10px;
      font-family: 'Open Sans Bold';
    }

    p {
      font-family: 'Open Sans Regular';
    }

    .login {
      border: 2px solid black;
      padding: 30px;
      border-radius: 10px;
      max-width: 400px;
      width: 100%;
      text-align: left;
    }

    .login label {
      font-weight: bold;
      display: block;
      margin: 15px 0 5px;
      font-family: 'Open Sans Regular';
    }

    .login input[type="email"],
    .login input[type="password"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      background-color: #d9d9d9;
    }

    .password {
      font-size: 13px;
      color: red;
      margin-top: 5px;
      display: inline-block;
      font-family: 'Open Sans Regular';
    }

    .login button {
      margin-top: 50px;
      width: 100%;
      background-color: #0d5c9b;
      color: white;
      border: none;
      padding: 10px;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
      font-family: 'Open Sans Regular';
    }

    .registro {
      text-align: center;
      margin-top: 15px;
      font-size: 14px;
      font-family: 'Open Sans Regular';
    }

    .registro a {
      text-decoration: none;
      font-weight: bold;
      color: black;
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
      <a href="login.html">Iniciar Sesión</a>
    </div>
  </header>

  <main>
    <h2>Inicio de Sesión</h2>
    <p>Ingresa tus credenciales para acceder</p>

    <?php if ($error): ?>
      <div style="color: red; text-align: center; margin: 15px 0;">
        <?php echo htmlspecialchars($error); ?>
      </div>
    <?php endif; ?>

    <div class="login">
      <form method="POST" action="login.php">
        <label for="correo">Correo</label>
        <input type="email" id="correo" name="correo" required>

        <label for="contraseña">Contraseña</label>
        <input type="password" id="contraseña" name="contraseña" required>

        <a href="recuperar.php" class="password">Olvidé mi contraseña</a>

        <button type="submit">Ingresar</button>
      </form>

      <div class="registro">
        ¿No tienes una cuenta? <a href="registro.php">Regístrate</a>
      </div>
    </div>
  </main>

  <script>
    document.querySelector('form').addEventListener('submit', function(e) {
      if (!this.checkValidity()) {
        e.preventDefault();
        alert('Completa todos los campos');
      }
    });
  </script>
</body>
</html>