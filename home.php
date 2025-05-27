<?php
session_start();
include 'menu.php';
include 'conexion.php';

$nombreUsuario = isset($_SESSION['usuario_nombre']) ? htmlspecialchars($_SESSION['usuario_nombre']) : null;

$termino_busqueda = '';
$where = '';
$params = [];

if (isset($_GET['busqueda']) && !empty($_GET['busqueda'])) {
    $termino_busqueda = trim($_GET['busqueda']);
    $where = "WHERE titulo LIKE ? OR descripcion LIKE ? OR autor LIKE ?";
    $params = array_fill(0, 3, '%' . $termino_busqueda . '%');
}

// Consulta base con posibilidad de búsqueda
$query = "SELECT * FROM noticias $where ORDER BY fecha DESC";

$stmt = $conexion->prepare($query);

if (!empty($params)) {
    $types = str_repeat('s', count($params));
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$resultado = $stmt->get_result();
$noticias = $resultado->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conexion->close();
?>

<?php
echo "<pre>ROL ACTUAL: " . $_SESSION['usuario_rol'] . "</pre>";
?>
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Comunicado Digital</title> 
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    body {
      font-family: Arial, sans-serif;
      background-color: #fff;
    }
    .encabezado1 {
      background-color: #0d5c9b;
      color: white;
      padding: 10px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      position: fixed;
      top: 0;
      left: 0;
     right: 0;
     z-index: 1000;
    }
    .logo1 {
      display: flex;
      align-items: center;
    }
    .logo1 img {
      height: 50px;
      margin-right: 10px;
    }
    nav.barra1{
      background-color: #bebaba;
      display: flex;
      justify-content: space-around;
      padding: 10px;
      font-weight: bold;
      position: fixed;
      top: 70px;
      left: 0;
      right: 0;
      z-index: 999;
    }
    nav.barra1 a{
      color: #000000;
      text-decoration: none;
      padding: 8px 15px;
      border-radius: 5px;
      transition: 0.3s;
    }
    nav.barra1 a.active {
      background-color: #0d5c9b;
      color: white;
    }
    .redes1 {
      margin-left: 500px;
    }
    .informacion1 {
      margin-right: 15px;
    }
    .Buscador {
    position: relative;
    width: 200px;
  }

  .Buscador input {
    width: 100%;
    padding: 8px 8px 8px 35px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
  }

  .Buscador img {
    position: absolute;
    top: 50%;
    left: 10px;
    transform: translateY(-50%);
    width: 16px;
    height: 16px;
    pointer-events: none;
  }
  a {
  text-decoration: none;
  }
  .contenido-principal {
    margin-top: 30px;
    padding: 20px;
  }
  .resultados-busqueda {
    margin-bottom: 20px;
    padding: 10px;
    background-color: #f0f0f0;
    border-radius: 4px;
  }
  .sin-noticias {
    text-align: center;
    padding: 50px;
    color: #666;
  }
  .noticia-card {
    background: white;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }
  .noticia-titulo {
    color: #0d5c9b;
    margin-bottom: 10px;
  }
  .noticia-meta {
    color: #666;
    font-size: 14px;
    margin-bottom: 15px;
    display: flex;
    gap: 15px;
  }
  .imagen-contenedor {
    max-width: 100%;
    overflow: hidden;
    text-align: center;
    margin-bottom: 15px;
  }
  .noticia-imagen {
    max-width: 100%;
    height: auto;
    max-height: 400px;
    object-fit: contain;
    border-radius: 4px;
  }
  .noticia-resumen {
    line-height: 1.6;
    margin-bottom: 15px;
  }
  </style>
</head>
<body>
  <div class="contenido-principal">
      <?php if (!empty($termino_busqueda)): ?>
        <div class="resultados-busqueda">
          <p>Resultados de búsqueda para: <strong><?= htmlspecialchars($termino_busqueda) ?></strong></p>
          <?php if (empty($noticias)): ?>
            <p>No se encontraron noticias que coincidan con tu búsqueda.</p>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <?php if (empty($noticias)): ?>
        <div class="sin-noticias">
          <h2>No hay noticias publicadas aún</h2>
          <p>¡Sé el primero en compartir una noticia!</p>
        </div>
      <?php else: ?>
        <?php foreach ($noticias as $noticia): ?>
          <article class="noticia-card">
            <h2 class="noticia-titulo">
              <a href="ver_noticia.php?id=<?= $noticia['id'] ?>" style="text-decoration: none; color: inherit;">
                <?= htmlspecialchars($noticia['titulo']) ?>
              </a>
            </h2>
            <div class="noticia-meta">
              <span><?= htmlspecialchars($noticia['categoria']) ?></span>
              <span><?= htmlspecialchars($noticia['autor']) ?></span>
              <span><?= htmlspecialchars($noticia['fecha']) ?></span>           
            </div>
            <?php if ($noticia['imagen']): ?>
              <div class="imagen-contenedor">
                <img src="<?= htmlspecialchars($noticia['imagen']) ?>" class="noticia-imagen" alt="<?= htmlspecialchars($noticia['titulo']) ?>">
              </div>
            <?php endif; ?>
            <p class="noticia-resumen"><?= nl2br(htmlspecialchars($noticia['descripcion'])) ?></p>
          </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
</body>
</html>