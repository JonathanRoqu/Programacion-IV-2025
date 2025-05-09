<?php 
session_start();
include 'conexion.php';

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $correo = $_POST['correo'] ?? '';
  $contraseña = $_POST['contraseña'] ?? '';

  // Consulta actualizada: incluye si la verificación del correo ya fue actualizada. 
  $stmt = $conexion->prepare("SELECT id, contraseña, nombre FROM usuarios WHERE correo = ? AND contraseña = ? AND email_verificacion = 1");
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
    // Verificamos si el correo y contraseña son correctos pero el correo no está verificado
    $stmt = $conexion->prepare("SELECT email_verificacion FROM usuarios WHERE correo = ? AND contraseña = ?");
    $stmt->bind_param("ss", $correo, $contraseña);
    $stmt->execute();
    $verifica = $stmt->get_result();

    if ($verifica->num_rows === 1) {
      $estado = $verifica->fetch_assoc();
      if ($estado['email_verificacion'] == 0) {
        $error = "Debes verificar tu correo electrónico antes de iniciar sesión.";
      } else {
        $error = "Correo o contraseña incorrectos.";
      }
    } else {
      $error = "Correo o contraseña incorrectos.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
      <a href="sobrenosotros.php">Sobre Nosotros</a>
      <a href="login.php">Iniciar Sesión</a>
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
  <?php if (isset($_SESSION['mensaje'])): ?>
  <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1055;">
    <div class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <?= htmlspecialchars($_SESSION['mensaje']) ?>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Cerrar"></button>
        </div>
    </div>
  </div>
<?php unset($_SESSION['mensaje']); ?>
<?php endif; ?>
  <script>
    document.querySelector('form').addEventListener('submit', function(e) {
      if (!this.checkValidity()) {
        e.preventDefault();
        alert('Completa todos los campos');
      }
    });
    document.addEventListener('DOMContentLoaded', function () {
        const toastEl = document.querySelector('.toast');
        if (toastEl) {
            const bsToast = new bootstrap.Toast(toastEl, {
                autohide: true,
                delay: 7000
            });
            bsToast.show();
        }
    });
  </script>
</body>
</html>