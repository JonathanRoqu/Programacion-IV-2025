<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: noticias.php");
    exit();
}

$id_noticia = intval($_GET['id']);
$stmt = $conexion->prepare("SELECT * FROM noticias WHERE id = ?");
$stmt->bind_param("i", $id_noticia);
$stmt->execute();
$resultado = $stmt->get_result();
$noticia = $resultado->fetch_assoc();
$stmt->close();

if (!$noticia) {
    header("Location: noticias.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($noticia['titulo']) ?> - Comunicado Digital</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            line-height: 1.6;
        }
        header {
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
        .barra-navegacion {
            background-color: #bebaba;
            display: flex;
            justify-content: space-around;
            padding: 10px;
            position: fixed;
            top: 70px;
            left: 0;
            right: 0;
            z-index: 999;
        }
        .contenido-principal {
            margin-top: 130px;
            padding: 20px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .noticia-titulo {
            color: #0d5c9b;
            margin-bottom: 20px;
            font-size: 28px;
        }
        .noticia-meta {
            color: #666;
            font-size: 16px;
            margin-bottom: 20px;
            display: flex;
            gap: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        .imagen-contenedor {
            max-width: 100%;
            overflow: hidden;
            text-align: center;
            margin-bottom: 25px;
        }
        .noticia-imagen {
            max-width: 100%;
            height: auto;
            max-height: 500px;
            object-fit: contain;
            border-radius: 4px;
        }
        .noticia-contenido {
            font-size: 16px;
            line-height: 1.8;
            margin-bottom: 30px;
        }
        .noticia-contenido p {
            margin-bottom: 15px;
        }
        .acciones-noticia {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .botones-accion {
            display: flex;
            gap: 15px;
        }
        .boton-accion {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 8px 15px;
            background-color: #f0f0f0;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
            border: 1px solid #ddd;
        }
        .boton-accion:hover {
            background-color: #e0e0e0;
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .boton-accion img {
            width: 20px;
            height: 20px;
        }
        .boton-volver {
            padding: 8px 20px;
            background-color: #0d5c9b;
            color: white;
            border-radius: 4px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .boton-volver:hover {
            background-color: #0a4a7a;
        }
        .informacion a {
            color: white;
            text-decoration: none;
            margin-left: 15px;
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
        .barra-navegacion a {
            color: #333;
            text-decoration: none;
            padding: 5px 10px;
        }
        .barra-navegacion a:hover {
            color: #0d5c9b;
        }
        @media (max-width: 600px) {
            .acciones-noticia {
                flex-direction: column;
                gap: 15px;
            }
            .botones-accion {
                width: 100%;
                justify-content: space-between;
            }
            .boton-accion {
                flex-grow: 1;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="imagenes/logo.png" alt="Logo">
        </div>
        <nav class="informacion">
            <a href="logout.php" id="btnSesion">Cerrar Sesión</a>
            </nav>
    </header>

    <nav class="barra-navegacion">
        <a href="home.php">Inicio</a>
        <a href="#">Destacado</a>
        <a href="#">Deportes</a>
        <a href="#">Educación</a>
        <a href="#">Turismo</a>
        <div class="Buscador">
            <img src="imagenes/lupa.png" alt="Buscar">
            <input type="text" placeholder="Buscar">
        </div>
    </nav>

    <div class="contenido-principal">
        <h1 class="noticia-titulo"><?= htmlspecialchars($noticia['titulo']) ?></h1>
        
        <div class="noticia-meta">
            <span><strong>Categoría:</strong> <?= htmlspecialchars($noticia['categoria']) ?></span>
            <span><strong>Autor:</strong> <?= htmlspecialchars($noticia['autor']) ?></span>
            <span><strong>Fecha:</strong> <?= htmlspecialchars($noticia['fecha']) ?></span>
        </div>

        <?php if ($noticia['imagen']): ?>
            <div class="imagen-contenedor">
                <img src="<?= htmlspecialchars($noticia['imagen']) ?>" class="noticia-imagen" alt="<?= htmlspecialchars($noticia['titulo']) ?>">
            </div>
        <?php endif; ?>
        
        <div class="noticia-contenido">
            <?= nl2br(htmlspecialchars($noticia['descripcion'])) ?>
        </div>

            <div class="acciones-noticia">
                <a href="noticia.php" class="boton-volver">Volver a Noticias</a>

                <div class="botones-accion">
                    <a href="reportar.php?id=<?= $noticia['id'] ?>" class="boton-accion">
                        <img src="imagenes/reportar.png" alt="Reportar Noticia">
                        <span>Reportar</span>
                    </a>

                    <?php if ($noticia['bloquear_comentarios'] == 1): ?>
                        <span class="boton-accion" style="opacity: 0.6; cursor: not-allowed;">
                            <img src="imagenes/comentar.png" alt="Comentar">
                            <span>Comentarios bloqueados</span>
                        </span>
                    <?php else: ?>
                        <a href="comentar.php?id=<?= $noticia['id'] ?>" class="boton-accion">
                            <img src="imagenes/comentar.png" alt="Comentar">
                            <span>Comentar</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <script>
            document.getElementById('btnSesion').addEventListener('click', function(e) {
                e.preventDefault();
                window.location.href = "login.php";
            }) ;
    </script>
</body>
</html>