<?php
// Determina si el usuario ha iniciado sesión
$usuarioLogueado = isset($_SESSION['usuario_nombre']);
$currentPage = basename($_SERVER['PHP_SELF']); // Para resaltar el menú activo
?>

<?php if (!$usuarioLogueado): ?>
<!-- Menu para usuarios no logueados -->
<header class="encabezado1">
  <div class="logo1">
    <img src="imagenes/logo.png" alt="logo">
  </div>
  <div class="redes1">
    <a target="_blank" href="https://www.instagram.com/"><img src="imagenes/instagram.png" height="35"/></a>
    <a target="_blank" href="https://www.facebook.com/"><img src="imagenes/facebook.png" height="35" style="margin-left: 12px;"/></a>
    <a target="_blank" href="https://x.com/?lang=es"><img src="imagenes/X.png" height="35" style="margin-left: 10px;"/></a>
  </div> 
  <div class="informacion1">
    <a href="#" style="margin-left: 15px; color: #ffffff;">Contacto</a>
    <a href="sobrenosotros.php" style="margin-left: 15px; color: #ffffff;">Sobre Nosotros</a>
    <a id="linkSesion" href="login.php" style="margin-left: 15px; color: #ffffff;">Iniciar Sesión</a>
  </div>
</header>

<nav class="barra1">
  <a href="home.php" class="nav-link <?= ($currentPage == 'home.php') ? 'active' : '' ?>">Inicio</a>
  <a href="destacado1.php" class="nav-link <?= ($currentPage == 'destacado1.php') ? 'active' : '' ?>">Destacado</a>
  <a href="deporte1.php" class="nav-link <?= ($currentPage == 'deporte1.php') ? 'active' : '' ?>">Deportes</a>
  <a href="educacion1.php" class="nav-link <?= ($currentPage == 'educacion1.php') ? 'active' : '' ?>">Educación</a>
  <a href="turismo1.php" class="nav-link <?= ($currentPage == 'turismo1.php') ? 'active' : '' ?>">Turismo</a>
  <form action="/transporte/search" method="post">
    <div class="Buscador">
      <img src="imagenes/lupa.png" alt="Buscar">
      <input type="text" name="keyword" placeholder="Buscar" required>
    </div>
  </form>
</nav>

<?php else: ?>
<!-- Menu para usuarios logueados -->
<header class="encabezado">
  <div class="logo">
    <img src="imagenes/logo.png" alt="logo">
  </div>
  <div class="redes">
    <a target="_blank" href="https://www.instagram.com/"><img src="imagenes/instagram.png" height="35"/></a>
    <a target="_blank" href="https://www.facebook.com/"><img src="imagenes/facebook.png" height="35" style="margin-left: 12px;"/></a>
    <a target="_blank" href="https://x.com/?lang=es"><img src="imagenes/X.png" height="35" style="margin-left: 10px;"/></a>
  </div> 
  <div class="informacion">
    <span style="margin-left: 15px; color: #ffffff;">
      Bienvenido, <?= htmlspecialchars($_SESSION['usuario_nombre']) ?>
    </span>
    <a href="#" style="margin-left: 15px; color: #ffffff;">Contacto</a>
    <a href="sobrenosotros.php" style="margin-left: 15px; color: #ffffff;">Sobre Nosotros</a>
    <a id="linkSesion" href="login.php" style="margin-left: 15px; color: #ffffff;">Cerrar Sesion</a>
    <div class="menu-configuracion"> 
      <img src="imagenes/configurar.png" class="icono-configuracion" alt="Configuracion">
      <div class="menu-desplegable">        
          <a href="actualizar_perfil.php">Configurar Perfil</a>
          <a href="logout.php" id="btnSesion">Cerrar Sesión</a>
      </div>
    </div>
  </div>
</header>

<nav class="barra">
  <a href="inicio.php" class="nav-link <?= ($currentPage == 'inicio.php') ? 'active' : '' ?>">Inicio</a>
  <a href="destacado.php" class="nav-link <?= ($currentPage == 'destacado.php') ? 'active' : '' ?>">Destacado</a>
  <a href="noticias.php" class="nav-link <?= ($currentPage == 'noticias.php') ? 'active' : '' ?>">Deportes</a>
  <a href="educacion.php" class="nav-link <?= ($currentPage == 'educacion.php') ? 'active' : '' ?>">Educación</a>
  <a href="turismo.php" class="nav-link <?= ($currentPage == 'turismo.php') ? 'active' : '' ?>">Turismo</a>
  <a href="denuncia.php" class="nav-link <?= ($currentPage == 'denuncia.php') ? 'active' : '' ?>">Denuncias</a>
  <form action="/transporte/search" method="post">
    <div class="Buscador">
      <img src="imagenes/lupa.png" alt="Buscar">
      <input type="text" name="keyword" placeholder="Buscar" required>
    </div>
  </form>
</nav>
<?php endif; ?>
