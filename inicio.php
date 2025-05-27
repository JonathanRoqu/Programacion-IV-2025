<?php
  session_start();
  include 'menu.php';
  include 'conexion.php';
  
  if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
  }
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
      background-color: #f5f5f5;
    }
    .encabezado {
      background-color: #0d5c9b;
      color: white;
      padding: 10px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1000;
    }
    
    .logo img {
      height: 50px;
    }
    .informacion {
      margin-right: 10px;
      display: flex;
      align-items: center;
    }
    nav.barra {
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
    nav.barra a {
      color: #000000;
      text-decoration: none;
      padding: 8px 15px;
      border-radius: 5px;
      transition: 0.3s;
    }
    nav.barra a.active {
      background-color: #0d5c9b;
      color: white;
    }
    .contenido-principal {
      margin-top: 130px;
      padding: 20px;
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
    .sin-noticias {
      text-align: center;
      padding: 50px;
      color: #666;
    }
    .Buscador {
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .Buscador img {
      width: 20px; 
      height: 20px;
    }
    .Buscador input {
      padding: 8px 12px;
      border: 1px solid #ccc;
      border-radius: 4px;
      font-size: 14px;
    }
    .Buscador button {
      padding: 8px 12px;
      background-color: #0d5c9b; 
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    .menu-configuracion {
      position: relative;
      display: inline-block;
      margin-left: 15px;
    }
    
    .icono-configuracion {
      width: 30px;
      height: 30px;
      cursor: pointer;
      transition: transform 0.3s;
    }
    
    .icono-configuracion:hover {
      transform: rotate(30deg);
    }
    
    .menu-desplegable {
      display: none;
      position: absolute;
      right: 0;
      background-color: white;
      min-width: 160px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.2);
      z-index: 1001;
      border-radius: 4px;
    }
    
    .menu-desplegable a {
      color: #333;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
      transition: background-color 0.3s;
    }
    
    .menu-desplegable a:hover {
      background-color: #f1f1f1;
    }
    
    .menu-configuracion:hover .menu-desplegable {
      display: block;
    }
    .resultados-busqueda {
      margin-bottom: 20px;
      padding: 10px;
      background-color: #f0f0f0;
      border-radius: 4px;
    }
    a {
      text-decoration: none;
    }
    .boton-publicar { 
      position: fixed; 
      bottom: 30px; right: 
      30px; background-color: #0d5c9b; 
      color: white; 
      border: none; 
      padding: 15px 25px; 
      border-radius: 50px; 
      font-weight: bold; 
      cursor: pointer; 
      box-shadow: 0 4px 8px rgba(0,0,0,0.2); 
      z-index: 1000; 
      text-decoration: none; 
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
    <?php if ($_SESSION['usuario_rol'] === 'Administrador'): ?>
      <a href="publicar_noticia.php" class="boton-publicar">Publicar Noticia</a>
    <?php endif; ?>

    <?php if ($_SESSION['usuario_rol'] === 'Poblador'): ?>
      <a href="enviar_noticia.php" class="boton-publicar">Enviar una noticia</a>
    <?php endif; ?>
</body>
</html>
