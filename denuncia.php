<?php
session_start();
include 'menu.php';
include 'conexion.php';
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Denuncias Ciudadanas</title>
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
    .contenedor {
      display: flex;
      padding: 150px;
      gap: 40px;
    }
    .denuncia {
      width: 45%;
    }
    .denuncia img {
      width: 100%;
      background-color: #a0bfff;
      height: 200px;
      display: block;
      margin-bottom: 30px;
    }
    .denuncia-noticias {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 30px;
      border-top: 2px solid black;
      padding-top: 10px;
    }
    .iconos-noticias {
      display: flex;
      gap: 15px;
    }
    .iconos-noticias img {
      width: 24px;
      height: 24px;
      cursor: pointer;
    }
    .comentario {
      display: flex;
      align-items: flex-start;
      margin-bottom: 20px;
    }
    .comentarios h3 {
      margin-top: 0;
    }
    .comentarios {
      width: 35%;
      border: 2px solid #000;
      border-radius: 12px;
      padding: 20px;
      margin-left: 70px;
    }
    .comentario .avatar {
      width: 40px;
      height: 40px;
      background-color: #a0bfff;
      border-radius: 60%;
      margin-right: 10px;
    }
    .comentario .contenido {
      background-color: #ddd;
      padding: 10px;
      border-radius: 8px;
      width: 100%;
    }
    .input-comentario {
      display: flex;
      gap: 10px;
      margin-top: 20px;
    }
    .input-comentario button {
      background-color: #0d5c9b;
      color: white;
      border: none;
      border-radius: 8px;
      padding: 10px 15px;
      cursor: pointer;
    }
    a {
      text-decoration: none;
    }
  </style>
</head>
<body>
  <div class="contenedor">
    <div class="denuncia">
      <h2>Denuncias Ciudadanas</h2>
      <hr />
      <img src="imagenes/google.png" alt="Imagen de denuncia" />
      <p>Descripción de la denuncia</p>
      <div class="denuncia-noticias">
        <div></div>
        <div class="iconos-noticias">
          <img src="imagenes/facebook.png" alt="comentarios">
          <img src="imagenes/lupa.png" alt="justicia">
        </div>
      </div>
    </div>
    <div class="comentarios">
      <h3>Comentarios</h3>
      <div class="comentario">
        <div class="avatar"></div>
        <div class="contenido">
          <strong>Usuario x</strong><br />
          Texto del comentario.
        </div>
      </div>
      <div class="comentario">
        <div class="avatar"></div>
        <div class="contenido">
          <strong>Usuario</strong><br />
          Comentario de ejemplo.
        </div>
      </div>
      <div class="input-comentario">
        <input type="text" placeholder="Escribe un comentario..." />
        <button>Enviar</button>
      </div>
    </div>
  </div>

</body>
</html>
