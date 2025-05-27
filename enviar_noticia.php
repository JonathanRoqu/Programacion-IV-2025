<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$mensaje = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoria = $_POST['categoria'] ?? '';
    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $autor = $_POST['autor'] ?? '';
    $fecha = date('Y-m-d H:i:s');
    $usuario_id = $_SESSION['usuario_id'] ?? null;

    $imagen_nombre = null;
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $imagen_nombre = uniqid() . '.' . $extension;
        $ruta_destino = 'imagenes/noticias/' . $imagen_nombre;

        // Crear la carpeta si no existe
        if (!file_exists('imagenes/noticias')) {
            mkdir('imagenes/noticias', 0777, true);
        }

        // Mover la imagen al destino
        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
            $mensaje = "Error al subir la imagen.";
            $imagen_nombre = null;
        }
    }

    $stmt = $conexion->prepare("INSERT INTO propuestas_noticias (categoria, titulo, descripcion, imagen, autor, fecha, usuario_id, estado)
                                VALUES (?, ?, ?, ?, ?, ?, ?, 'pendiente')");
    $stmt->bind_param("ssssssi", $categoria, $titulo, $descripcion, $imagen_nombre, $autor, $fecha, $usuario_id);

    if ($stmt->execute()) {
        $mensaje = "Noticia enviada correctamente. Sera revisada por un administrador.";
    } else {
        $mensaje = "Error al enviar la noticia: " . $conexion->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enviar Noticia - Periodico Digital Comunitario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff;
            margin: 0;
            color: #333;
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
        .logo img {
            height: 50px;
            margin-right: 20px;
        }
        .informacion a {
            color: white;
            margin-left: 20px;
            text-decoration: none;
            font-size: 14px;
        }
        .contenedor {
            max-width: 800px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .font-noticia {
            background-color: #f9f9f9;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .form-noticia h1 {
            color: #0d5c9b;
            margin-top: 0;
            text-align: center;
        }
        .form-noticia p.description {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #0d5c9b;
        }
        .form-group input[type="text"],
        .form-group input[type="date"],
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .form-group textarea {
            min-height: 150px;
            resize: vertical;
        }
        .radio-group {
            display: flex;
            gap: 20px;
            margin-top: 10px;
        }
        .radio-option {
            display: flex;
            align-items: center;
        }
        .radio-option input {
            margin-right: 8px;
        }
        .file-upload {
            border: 2px dashed #ddd;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .file-upload:hover {
            border-color: #0d5c9b;
            background-color: #ddd;
        }
        .file-upload input {
            display: none;
        }
        .file-upload-label {
            display: block;
            font-size: 16px;
            color: #666;
        }
        .file-upload-icon {
            font-size: 40px;
            color: #0d5c9b;
            margin-bottom: 10px;
        }
        .btn-enviar {
            background-color: #0d5c9b;
            color: white;
            border: none;
            padding: 12px 25px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            width: 100%;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .btn-enviar:hover {
            background-color: #0d5c9b;
        }
        .mensaje {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }
        .mensaje.exito {
            background-color: #ddd;
            color: #155724;
        }
        .mensaje.error {
            background-color: #ddd;
            color: #721c24;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="imagenes/logo.png" alt="logo">
        </div>
        <div class="informacion">
            <a href="noticias.php">Inicio</a>
            <a href="enviar_noticia.php">Enviar Noticia</a>
            <a href="perfil.php">Mi perfil</a>
            <a href="logout.php">Cerrar Sesión</a>
        </div>
    </header>

    <div class="contenedor">
        <form class="form-noticia" method="POST" action="enviar_noticia.php" enctype="multipart/form-data">
            <h1>Enviar Noticia</h1>
            <p class="descripcion">Los administradores revisarán la noticia que compartas. Si se aprueba será publicada.</p>

            <?php if ($mensaje): ?>
                <div class="mensaje <?php echo strpos($mensaje,'Error') !== false ? 'error' : 'exito'; ?>">
                    <?php echo htmlspecialchars($mensaje); ?>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label>Selecciona la categoría</label>
                <div class="radio-group">
                    <label class="radio-option"><input type="radio" name="categoria" value="Deportes" required> Deportes</label>
                    <label class="radio-option"><input type="radio" name="categoria" value="Clima" required> Clima</label>
                    <label class="radio-option"><input type="radio" name="categoria" value="Educacion" required> Educación</label>
                    <label class="radio-option"><input type="radio" name="categoria" value="Turismo" required> Turismo</label>
                </div>
            </div>

            <div class="form-group">
                <label for="titulo">Título de la Noticia</label>
                <input type="text" id="titulo" name="titulo" required>
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción de la noticia</label>
                <textarea id="descripcion" name="descripcion" required></textarea>
            </div>

            <div class="form-group">
                <label>Cargar una imagen</label>
                <div class="file-upload" onclick="document.getElementById('imagen').click()">
                    <span class="file-upload-label">Selecciona una imagen</span>
                    <input type="file" id="imagen" name="imagen" accept="image/*">
                </div>
                <div id="nombre-archivo" style="margin-top: 5px; font-size: 14px; color: #666;"></div>
            </div>

            <div class="form-group">
                <label for="autor">Autor</label>
                <input type="text" id="autor" name="autor" required>
            </div>

            <div class="form-group">
                <label for="fecha">Fecha</label>
                <input type="date" id="fecha" name="fecha" required>
            </div>

            <p style="text-align: center; color: #666; margin: 20px 0;">La noticia será revisada por un administrador</p>
            <button type="submit" class="btn-enviar">Enviar</button>
        </form>
    </div>

    <script>
        document.getElementById('imagen').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'No se seleccionó archivo';
            document.getElementById('nombre-archivo').textContent = fileName;
        });

        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('fecha').value = today;
        });
    </script>
</body>
</html>
