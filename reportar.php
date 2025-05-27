<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
  $_SESSION['reportar'] = "Para poder reportar una noticia, debes iniciar sesión primero.";
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: noticias.php");
    exit();
}

$id_noticia = intval($_GET['id']);

$stmt = $conexion->prepare("SELECT titulo, fecha FROM noticias WHERE id = ?");
$stmt->bind_param("i", $id_noticia);
$stmt->execute();
$resultado = $stmt->get_result();
$noticia = $resultado->fetch_assoc();
$stmt->close();

if (!$noticia) {
    header("Location: noticias.php");
    exit();
}

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['motivo'])) {
        $errores[] = "Debes seleccionar un motivo";
    }
    
    if (empty($_POST['comentario'])) {
        $errores[] = "Debes agregar un comentario";
    } elseif (strlen($_POST['comentario']) < 10) {
        $errores[] = "El comentario debe tener al menos 10 caracteres";
    }
    
    if (empty($errores)) {
        $stmt = $conexion->prepare("INSERT INTO reportes 
                                   (noticia_id, usuario_id, motivo, comentario, fecha_reporte) 
                                   VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("iiss", 
            $id_noticia,
            $_SESSION['usuario_id'],
            $_POST['motivo'],
            $_POST['comentario']
        );
        
        if ($stmt->execute()) {
            header("Location: notificacion_reporte.php");
            exit();
        } else {
            $errores[] = "Error al guardar el reporte: " . $conexion->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Reportar Noticia</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      background-color: white;
    }

    header {
      background-color: #0d5c9b;
      color: white;
      padding: 15px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    header .logo img {
      height: 50px;
    }

    header nav a {
      color: white;
      text-decoration: none;
      margin-left: 20px;
      font-size: 14px;
    }

    .container {
      padding: 40px;
    }

    .container h2 {
      margin-bottom: 10px;
      margin-left: 150px;
    }

    hr {
      margin-bottom: 30px;
      border: none;
      height: 2px;
      background-color: black;
      margin-left: 150px;
      margin-right: 150px;
    }

    .report-box {
      border: 2px solid black;
      border-radius: 10px;
      padding: 30px;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
      max-width: 900px;
      margin: 0 auto;
    }

    .report-box label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }

    .report-box input,
    .report-box select,
    .report-box textarea {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      background-color: #e5e5e5;
      border: none;
      border-radius: 4px;
    }

    .report-box textarea {
      height: 100px;
      resize: none;
    }

    .full-width {
      grid-column: span 2;
    }

    .btn-submit {
      background-color: #0d5c9b;
      color: white;
      padding: 10px 20px;
      border: none;
      font-weight: bold;
      cursor: pointer;
      border-radius: 4px;
      justify-self: end;
      transition: background-color 0.3s;
    }

    .btn-submit:hover {
      background-color: #0a4a7a;
    }

    .error {
      color: red;
      margin-bottom: 15px;
      padding: 10px;
      background-color: #ffeeee;
      border: 1px solid #ffcccc;
      border-radius: 4px;
      grid-column: span 2;
    }
  </style>
</head>
<body>

  <header>
    <div class="logo">
      <img src="imagenes/logo.png" alt="Logo">
    </div>
    <nav>
      <a href="noticias.php">Volver a Noticias</a>
      <a href="logout.php">Cerrar Sesión</a>
    </nav>
  </header>

  <div class="container">
    <h2>Reportar noticia</h2>
    <hr>
    
    <form class="report-box" method="POST" action="reportar.php?id=<?= $id_noticia ?>">
      <?php if (!empty($errores)): ?>
        <div class="error">
          <?php foreach ($errores as $error): ?>
            <p><?= htmlspecialchars($error) ?></p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
      
      <div>
        <label for="titulo">Título de la noticia</label>
        <input type="text" id="titulo" value="<?= htmlspecialchars($noticia['titulo']) ?>" readonly>
      </div>

      <div>
        <label for="fecha">Fecha de publicación</label>
        <input type="text" id="fecha" value="<?= htmlspecialchars($noticia['fecha']) ?>" readonly>
      </div>

      <div>
        <label for="motivo">Motivo*</label>
        <select id="motivo" name="motivo" required>
          <option value="" disabled selected>Selecciona un motivo</option>
          <option value="Contenido falso" <?= isset($_POST['motivo']) && $_POST['motivo'] == 'Contenido falso' ? 'selected' : '' ?>>Contenido falso</option>
          <option value="Lenguaje ofensivo" <?= isset($_POST['motivo']) && $_POST['motivo'] == 'Lenguaje ofensivo' ? 'selected' : '' ?>>Lenguaje ofensivo</option>
          <option value="Plagio" <?= isset($_POST['motivo']) && $_POST['motivo'] == 'Plagio' ? 'selected' : '' ?>>Plagio</option>
          <option value="Información personal" <?= isset($_POST['motivo']) && $_POST['motivo'] == 'Información personal' ? 'selected' : '' ?>>Información personal expuesta</option>
        </select>
      </div>

      <div>
        <label for="usuario">Tu nombre</label>
        <input type="text" id="usuario" value="<?= htmlspecialchars($_SESSION['usuario_nombre'] ?? '') ?>" readonly>
      </div>

      <div class="full-width">
        <label for="comentario">Comentario*</label>
        <textarea id="comentario" name="comentario" required><?= htmlspecialchars($_POST['comentario'] ?? '') ?></textarea>
      </div>

      <div class="full-width" style="text-align: right;">
        <button type="submit" class="btn-submit">Enviar Reporte</button>
      </div>
    </form>
  </div>

</body>
</html>