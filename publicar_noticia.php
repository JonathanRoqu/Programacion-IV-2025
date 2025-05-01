<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ruta_imagen = '';

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $nombre_imagen = uniqid() . '_' . basename($_FILES['imagen']['name']);
        $ruta_destino = "imagenes/" . $nombre_imagen;

        if (!file_exists('imagenes')) {
            mkdir('imagenes', 0777, true);
        }

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
            $ruta_imagen = $ruta_destino;
        } else {
            $error = "Error al subir la imagen";
        }
    }

    if (empty($_POST['titulo']) || empty($_POST['categoria']) || empty($_POST['descripcion']) || empty($_POST['autor']) || empty($_POST['fecha'])) {
        $error = "Todos los campos marcados como obligatorios deben completarse";
    } else {
        $stmt = $conexion->prepare("INSERT INTO noticias (titulo, categoria, descripcion, autor, fecha, imagen, bloquear_comentarios) VALUES (?, ?, ?, ?, ?, ?, ?)");

        if ($stmt) {
            $bloquear_comentarios = isset($_POST['bloquear_comentarios']) ? 1 : 0;
            $stmt->bind_param("ssssssi",
                $_POST['titulo'],
                $_POST['categoria'],
                $_POST['descripcion'],
                $_POST['autor'],
                $_POST['fecha'],
                $ruta_imagen,
                $bloquear_comentarios
            );

            if ($stmt->execute()) {
                header("Location: noticias.php?exito=1");
                exit();
            } else {
                $error = "Error al guardar en la base de datos: " . $conexion->error;
            }
            $stmt->close();
        } else {
            $error = "Error al preparar la consulta: " . $conexion->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicar Noticia - Comunicado Digital</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #fff;
            margin: 0;
        }
        header {
            background-color: #0d5c9b;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo img {
            height: 50px;
        }
        .contenedor-principal {
            max-width: 800px;
            margin: 30px auto;
            padding: 0 20px;
        }
        h1 {
            color: #0d5c9b;
            margin-bottom: 20px;
        }
        .campo {
            margin-bottom: 20px;
        }
        .campo label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .campo input[type="text"],
        .campo input[type="date"],
        .campo textarea,
        .campo select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .campo textarea {
            min-height: 150px;
        }
        .boton-publicar {
            background-color: #0d5c9b;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        .boton-publicar:hover {
            background-color: #0a4a7a;
        }
        #preview-imagen {
            max-width: 100%;
            max-height: 200px;
            margin-top: 10px;
            display: none;
        }
        .error {
            color: red;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #ffeeee;
            border: 1px solid #ffcccc;
            border-radius: 4px;
        }
        .requerido:after {
            content: " *";
            color: red;
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
    </style>
</head>
<body>
<header>
        <div class="logo">
            <img src="imagenes/logo.png" alt="logo">
        </div>
        <nav class="informacion">
            <a href="noticias.php" style="color: white;">Volver a Noticias</a>
            <div class="menu-configuracion">
                <img src="imagenes/configurar.png" class="icono-configuracion" alt="Configuración">
                <div class="menu-desplegable">
                    <a href="actualizar_perfil.php">Configurar Perfil</a>
                    <a href="logout.php">Cerrar Sesión</a>
                </div>
            </div>
        </nav>
    </header>

    <div class="contenedor-principal">
        <h1>Publicar Nueva Noticia</h1>

        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="publicar_noticia.php" method="POST" enctype="multipart/form-data">
            <div class="campo">
                <label for="categoria" class="requerido">Categoria:</label>
                <select id="categoria" name="categoria" required>
                    <option value="">-- Selecciona una categoria --</option>
                    <option value="Deportes">Deportes</option>
                    <option value="Clima">Clima</option>
                    <option value="Educacion">Educacion</option>
                    <option value="Turismo">Turismo</option>
                </select>
            </div>

            <div class="campo">
                <label for="titulo" class="requerido">Titulo:</label>
                <input type="text" id="titulo" name="titulo" required placeholder="Escribe el titulo de la noticia">
            </div> 

            <div class="campo">
                <label for="descripcion" class="requerido">Descripcion:</label>
                <textarea id="descripcion" name="descripcion" required placeholder="Escribe la descripcion de la noticia"></textarea>
            </div> 

            <div class="campo">
                <label for="autor" class="requerido">Autor:</label>
                <input type="text" id="autor" name="autor" required placeholder="Nombre del autor">
            </div> 

            <div class="campo">
                <label for="fecha" class="requerido">Fecha:</label>
                <input type="date" id="fecha" name="fecha" required value="<?php echo date('Y-m-d'); ?>">
            </div> 

            <div class="campo">
                <label for="imagen">Imagen (opcional):</label>               
                <input type="file" id="imagen" name="imagen" accept="image/*">
                <img id="preview-imagen" src="#" alt="Vista previa de la imagen">
            </div>

            <div class="campo">
                <label>
                    <input type="checkbox" name="bloquear_comentarios" id="bloquear_comentarios">
                    Bloquear comentarios
                </label>
            </div>
        
            <button class="boton-publicar" type="submit">Publicar Noticia</button>
        </form>
    </div>

        <script>
            document.getElementById('imagen').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const preview = document.getElementById('preview-imagen');
                        preview.src = event.target.result;
                        preview.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                }
            });
    </script>  
</body>
</html>