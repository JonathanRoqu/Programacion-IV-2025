<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'Administrador') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id_noticia = intval($_GET['id']);

    $stmt = $conexion->prepare("DELETE FROM comentarios WHERE noticia_id = ?");
    $stmt->bind_param("i", $id_noticia);
    $stmt->execute();
    $stmt->close();

    $stmt = $conexion->prepare("DELETE FROM noticias WHERE id = ?");
    $stmt->bind_param("i", $id_noticia);
    $stmt->execute();
    $stmt->close();
    
}

header("Location: noticias.php");
exit();
?>