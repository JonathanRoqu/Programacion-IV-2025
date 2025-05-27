<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'Administrador') {
    header("Location: login.php");
    exit();
}

$error = null;

if (!isset($_GET['id'])) {
    header("Location: noticias.php");
    exit();
}
$id_noticia = intval($_GET["id"]);

$stmt = $conexion->prepare("SELECT * FROM noticias WHERE id = ?");
$stmt->bind_param("i", $id_noticia);
$stmt->execute();
$noticia = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$noticia) {
    header("Location: noticia.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ??'';

    $stmt = $conexion->prepare("UPDATE noticias SET titulo = ?, descripcion = ? WHERE id = ?");
    $stmt->bind_param("ssi", $titulo, $descripcion, $id_noticia);
    if ($stmt->execute()) {
        header("Location: noticias.php?actualizada=1");
        exit();
    } else {
        $error = "Error al actualizar: " . $conexion->error;
    }
    $stmt->close();
    
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Noticia</title>
    <style>
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
            margin: 30px;
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
        .campo textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
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
        .error {
            color: red;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #ffeeee;
            border: 1px solid #ffeeee;
            border-radius: 4px;
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
    </nav>
</header>

<div class="contenedor-principal">
    <h1>Editar Noticia</h1>

    <?php if ($error): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="campo">
            <label for="titulo">Titulo:</label>
            <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($noticia['titulo']); ?>" required>
        </div>

        <div class="campo">
            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" required><?php echo htmlspecialchars($noticia['descripcion']); ?></textarea>
        </div>

        <button class="boton-publicar" type="submit">Guardar Cambios</button>
    </form>
</div>
</body>
</html>
