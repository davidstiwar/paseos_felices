<?php
include 'conexion.php';
?>

<!doctype html>
<html lang="es">

<head>
  <title>Inicio</title>
  <!-- Required meta tags -->
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <link rel="stylesheet" href="./css/estilo.css">
  <link rel="icon" href="./img/favicon_io/android-chrome-192x192.png" type="image/x-icon">
  <link rel="icon" href="./img/favicon_io/android-chrome-512x512.png" type="image/x-icon">
  <link rel="icon" href="./img/favicon_io/apple-touch-icon.png" type="image/x-icon">
  <link rel="icon" href="./img/favicon_io/favicon-16x16.png" type="image/x-icon">
  <link rel="icon" href="./img/favicon_io/favicon-32x32.png" type="image/x-icon">
</head>

<body>
  <?php
  include './includes/header.php';
  ?>
  <main>
    <!-- Slider -->
    <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="./img/slider.jpeg" class="d-block w-100" alt="Slide 1">
        </div>
      </div>
    </div>
  </main>
  <br>
  <div class="mision">
    <h1>Misión</h1>
    <p>
      "En PASOS FELICES S.A.S, nos dedicamos a brindar un servicio integral y personalizado para el bienestar de
      tu mascota.
      Ofrecemos paseos revitalizantes, baños relajantes y alimentación balanceada, garantizando la mejor
      experiencia para tu compañero. Nuestro compromiso es asegurar que cada mascota reciba el cuidado, cariño y
      atención que merece, permitiendo a sus dueños disfrutar de la tranquilidad de saber que están en buenas
      manos."
    </p>
    <div class="img_mision"><img src="./img/img_mision.jpeg" alt="Imagen de misión"></div>
  </div>

  <br>

  <div class="vision">
    <h1>Visión</h1>
    <div class="img_vision"><img src="./img/img_vision.jpeg" alt="Imagen de visión"></div>
    <p>
      "En PASOS FELICES S.A.S, ofrecemos una experiencia integral y personalizada para el cuidado de las mascotas,
      brindando tranquilidad y comodidad a sus dueños. A través de servicios de paseo, baño y alimentación, nos
      comprometemos a cuidar a cada mascota como si fuera parte de nuestra familia, asegurando que cada necesidad
      sea
      atendida con cariño y profesionalismo."
    </p>
  </div>

  <br>

  <div class="porque_elegirnos">
    <h1>¿Por qué Elegirnos?</h1>
    <p>
      Servicios Personalizados
      Nos adaptamos a las necesidades específicas de cada mascota. Ya sea que necesite un paseo tranquilo, un baño relajante o atención especial en su alimentación, nuestro enfoque es siempre personalizado y amoroso.
    </p>
  </div>

  <?php
  include './includes/footer.php';
  ?>

  <script src="./js/menu.js"></script>
  <script src="./js/animacion.js"></script>
</body>

</html>