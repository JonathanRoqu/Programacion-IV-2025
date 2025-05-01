<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] != 'admin') {
    header("Location: login.php");
    exit();
}

$query = "SELECT r.id, n.titulo, r.fecha_reporte, u.nombre as reportero, 
          r.motivo, r.comentario, r.estado, n.id as noticia_id
          FROM reportes r
          JOIN noticias n ON r.noticia_id = n.id
          JOIN usuarios u ON r.usuario_id = u.id
          ORDER BY r.fecha_reporte DESC";

$resultado = $conexion->query($query);
$reportes = $resultado->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cambiar_estado'])) {
    $reporte_id = intval($_POST['reporte_id']);
    $nuevo_estado = $conexion->real_escape_string($_POST['nuevo_estado']);
    
    $stmt = $conexion->prepare("UPDATE reportes SET estado = ? WHERE id = ?");
    $stmt->bind_param("si", $nuevo_estado, $reporte_id);
    $stmt->execute();
    $stmt->close();
    
    header("Location: notificacion_reporte.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Notificación de Reportes</title>
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
      margin-bottom: 5px;
    }

    .container p {
      color: #555;
      margin-bottom: 30px;
    }
    
    hr {
      margin-bottom: 30px;
      border: none;
      height: 2px;
      background-color: black;
      margin-left: 5px;
      margin-right: 10px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      border: 2px solid black;
    }

    th, td {
      padding: 12px;
      text-align: left;
      border-right: 1px solid black;
    }

    th:last-child, td:last-child {
      border-right: none;
    }

    th {
      background-color: #f2f2f2;
    }

    tr:nth-child(even) {
      background-color: #e5e5e5;
    }

    tr:nth-child(odd) {
      background-color: white;
    }
    
    .estado-pendiente {
      color: #d35400;
      font-weight: bold;
    }
    
    .estado-revisado {
      color: #3498db;
      font-weight: bold;
    }
    
    .estado-resuelto {
      color: #27ae60;
      font-weight: bold;
    }
    
    select {
      padding: 5px;
      border-radius: 4px;
      border: 1px solid #ccc;
    }
    
    .btn-accion {
      padding: 5px 10px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      color: white;
      font-weight: bold;
    }
    
    .btn-ver {
      background-color: #0d5c9b;
    }
    
    .btn-ver:hover {
      background-color: #0a4a7a;
    }
    
    .mensaje-exito {
      padding: 15px;
      background-color: #dff0d8;
      color: #3c763d;
      border: 1px solid #d6e9c6;
      border-radius: 4px;
      margin-bottom: 20px;
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
    <h2>Notificación de reportes de noticias</h2>
    <hr>
    
    <?php if (isset($_GET['exito'])): ?>
      <div class="mensaje-exito">
        <?= htmlspecialchars($_GET['exito']) ?>
      </div>
    <?php endif; ?>
    
    <p>Listado de noticias reportadas por los usuarios</p>

    <table>
      <thead>
        <tr>
          <th>Título de la noticia</th>
          <th>Fecha del reporte</th>
          <th>Reportado por</th>
          <th>Motivo</th>
          <th>Comentario</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($reportes)): ?>
          <tr>
            <td colspan="7" style="text-align: center; font-style: italic; color: #888;">
              No hay reportes disponibles.
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($reportes as $reporte): ?>
            <tr>
              <td><?= htmlspecialchars($reporte['titulo']) ?></td>
              <td><?= htmlspecialchars($reporte['fecha_reporte']) ?></td>
              <td><?= htmlspecialchars($reporte['reportero']) ?></td>
              <td><?= htmlspecialchars($reporte['motivo']) ?></td>
              <td><?= htmlspecialchars($reporte['comentario']) ?></td>
              <td class="estado-<?= htmlspecialchars($reporte['estado']) ?>">
                <form method="POST" style="display: inline;">
                  <input type="hidden" name="reporte_id" value="<?= $reporte['id'] ?>">
                  <select name="nuevo_estado" onchange="this.form.submit()">
                    <option value="pendiente" <?= $reporte['estado'] == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                    <option value="revisado" <?= $reporte['estado'] == 'revisado' ? 'selected' : '' ?>>Revisado</option>
                    <option value="resuelto" <?= $reporte['estado'] == 'resuelto' ? 'selected' : '' ?>>Resuelto</option>
                  </select>
                  <input type="hidden" name="cambiar_estado" value="1">
                </form>
              </td>
              <td>
                <a href="ver_noticia.php?id=<?= $reporte['noticia_id'] ?>" class="btn-accion btn-ver">Ver Noticia</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

</body>
</html>