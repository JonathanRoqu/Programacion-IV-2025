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

  </style>
</head>
<body>
  <?php include 'menu.php'; ?>
</body>
</html>
