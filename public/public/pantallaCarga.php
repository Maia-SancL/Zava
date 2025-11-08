<!--NO SE. LO HIZO CHATGPT DESPUES DE DISCUTIR 1 HORA. PERO QUEDA LINDO :D-->
<?php
$mensaje = ($_POST['mensaje']) ?? "Cargando...";
$destino = $_POST['destino'] ?? '/Zava/index.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zava - Cargando...</title>
    <link rel="stylesheet" href="/Zava/css/public/general.css">
<style>
  body, html {
    margin: 0; height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    background: var(--secundairo-50);
    flex-direction:column;
    gap:1rem;
  }

    .mensaje {
    font-family: 'Nunito';
    font-size: 1.2rem;
    font-weight: 800;
    color: var(--primario-500);
    }

  #icon-container {
    width: 120px;
    height: 120px;
    perspective: 600px;
    cursor: default;
    color: var(--primario-500);
  }
  svg {
    width: 100px;
    height: 100px;
    transform-style: preserve-3d;
  }

  /* Pequeño salto */
  .jump {
    animation: jumpAnim 0.3s forwards;
  }
  @keyframes jumpAnim {
    0% { transform: translateY(0) scale(1) rotateZ(0deg); }
    50% { transform: translateY(-15px) scale(1.1) rotateZ(0deg); }
    100% { transform: translateY(0) scale(1) rotateZ(0deg); }
  }

  /* Giro 2 vueltas sentido horario (0° a 720°), escala 1 a 0 */
  .rotate-shrink {
    animation: rotateShrink 1100ms forwards;
  }
  @keyframes rotateShrink {
    0% { transform: rotateZ(0deg) scale(1); }
    100% { transform: rotateZ(720deg) scale(0);}
  }

  /* Giro 2 vueltas sentido horario (720° a 1440°), escala 0 a 1 */
  .rotate-grow {
    animation: rotateGrow 1100ms forwards;
  }
  @keyframes rotateGrow {
    0% { transform: rotateZ(720deg) scale(0);  }
    100% { transform: rotateZ(1440deg) scale(1); }
  }
</style>
</head>
<body>
  <div id="icon-container"></div>
  <p class="mensaje"><?php echo $mensaje;?></p>

<script>
  const iconos = [
    ' <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 512 512"><path  class="icon" fill="currentColor" fill-rule="evenodd" d="M200.736 482.822c2.368 4.849 6.381 10.175 10.645 13.842c5.211 4.481 11.704 7.65 18.576 10.194c4.798 1.776 11.137 3.324 16.264 4.245c5.313.954 11.837.937 17.986.87c10.926-.12 19.55-1.137 29.283-3.508c6.132-1.494 11.957-3.153 16.685-5.013c8.927-3.513 14.619-7.413 15.806-9.943c-.891-4.183-2.293-10.77-3.337-16.69a272 272 0 0 1-2.318-16.001c-1.337-11.6-.649-19.762.833-33.255c2.183-19.873 6.427-28.926 12.624-40.735c5.918-11.28 15.687-27.045 23.216-37.2c7.849-10.588 27.5-31.28 34.668-38.969c2.5.74 7.328 2.35 14.795 3.824c5.69 1.123 12.316 2.462 20.654 2.181c19.852-.668 27.079-4.464 38.627-11.993c10.478-6.83 20.503-20.498 25.752-33.596c4.036-10.07 5.278-20.324 3.522-32.357c-1.182-8.094-3.797-17.679-5.887-24.017c-2.274-6.894-5.843-14.3-8.84-19.903c0 0 12.051-14.4 16.874-26.412c4.152-10.344 6.088-23.654 5.257-31.973c-.862-8.634-8.191-24.474-12.798-30.127c-6.069-7.448-12.002-15.17-19.499-21.224c-7.348-5.936-15.67-10.794-24.385-14.435c-9.031-3.772-19.835-5.956-28.398-7.46c-1.286-6.86-3.058-11.643-7.778-19.897c-5.894-10.6-12.368-15.388-22.543-22.838c-12.625-9.243-30.221-18.778-47.3-20.084c-7.358-.562-18.144-.794-31.708 2.123c-11.794 2.536-23.42 7.328-32.26 11.845c-17.75-6.798-34.333-5.794-47.415-2.241c-11.033 2.995-13.387 4.793-23.481 10.733c-9.91 4.281-20.676 14.236-29.012 25.762c-31.051.881-56.898 11.803-75.3 22.246c-20.93 11.878-39.713 34.582-47.067 49.024c-8.208 16.118-16.406 38.917-9.48 74.176c-45.742 28.123-41.038 73.108-15.323 98.226c22.552 22.03 59.913 28.893 95.3 25.324c10.572 10.717 50.566 59.17 50.566 59.17s40.285 45.399 26.196 106.086M157.337 294.89c5.474-5.473 10.166-13.293 13.815-19.288c4.141.23 12.645.788 21.007.404c8.384-.386 17.269-1.624 20.821-3.7c.997 2.99 7.935 27.758 8.375 42.978c.578 19.954.022 23.491-3.203 30.495c-1.756.83-3.464 1.192-5.619.418c-7.677-2.756-20.47-12.343-29.642-20.9c-14.205-13.25-25.554-30.407-25.554-30.407m158.127-4.215c-.275 2.424-.451 11.125.72 18.725c1.191 7.719 2.359 14.891 5.27 17.677c10.23-1.843 17.374-8.406 23.549-13.014c0 0 20.04-15.647 23.911-19.24c-2.949-2.673-7.096-8.94-8.57-12.718c0 0-14.103 4.131-21.2 5.606c-8.938 1.935-19.441 2.78-23.68 2.964" clip-rule="evenodd"/></svg>',
   '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 20 20"><path class="icon" fill="currentColor" d="M2 18.43c1.51 1.36 11.64-4.67 13.14-7.21c.72-1.22-.13-3.01-1.52-4.44C15.2 5.73 16.59 9 17.91 8.31c.6-.32.99-1.31.7-1.92c-.52-1.08-2.25-1.08-3.42-1.21c.83-.2 2.82-1.05 2.86-2.25c.04-.92-1.13-1.97-2.05-1.86c-1.21.14-1.65 1.88-2.06 3c-.05-.71-.2-2.27-.98-2.95c-1.04-.91-2.29-.05-2.32 1.05c-.04 1.33 2.82 2.07 1.92 3.67C11.04 4.67 9.25 4.03 8.1 4.7c-.49.31-1.05.91-1.63 1.69c.89.94 2.12 2.07 3.09 2.72c.2.14.26.42.11.62c-.14.21-.42.26-.62.12c-.99-.67-2.2-1.78-3.1-2.71c-.45.67-.91 1.43-1.34 2.23c.85.86 1.93 1.83 2.79 2.41c.2.14.25.42.11.62c-.14.21-.42.26-.63.12c-.85-.58-1.86-1.48-2.71-2.32C2.4 13.69 1.1 17.63 2 18.43"/></svg>',
   '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 512 512"><path class="icon" fill="currentColor" d="M256 99.633c-37.26 0-74.595 11.18-119.844 33.328c1.565 57.322 29.767 114.884 57.942 145.362C211.383 271.182 233.625 268 256 268s44.617 3.18 61.902 10.322c28.175-30.478 56.377-88.04 57.942-145.36C330.594 110.813 293.26 99.63 256 99.63zm-134.422 54.135c-25.185 6.602-40.16 20.58-49.844 40.697c-5.602 34.042-.223 57 10.98 74.916c10.892 17.424 27.93 30.347 47.21 42.335c10.962-1.166 21.316-2.24 30.152-5.563c8.206-3.086 15.32-8.034 21.715-17.27c-27.815-30.238-53.7-80.825-60.212-135.114zm268.844 0c-6.513 54.29-32.397 104.876-60.213 135.115c6.393 9.235 13.508 14.183 21.714 17.27c8.836 3.322 19.19 4.396 30.152 5.562c19.28-11.988 36.318-24.91 47.21-42.334c11.203-17.915 16.582-40.873 10.98-74.915c-9.684-20.118-24.66-34.095-49.844-40.697zM54.33 234.014C36.35 260.292 24 289.6 24 320c0 21.623 1.848 42.626 6.418 58.707c4.57 16.08 11.55 26.322 20.512 29.85c13.776 4.573 33.902-22.085 45.773-41.323c3.23-6.184 6.993-13.896 10.555-21.39a1143 1143 0 0 0 10.545-22.787c-18.77-11.952-36.496-25.745-48.657-45.194c-7.68-12.283-12.92-26.732-14.816-43.85zm403.34 0c-1.897 17.117-7.137 31.566-14.816 43.85c-12.16 19.448-29.886 33.24-48.657 45.193c1.837 4.084 5.25 11.642 10.545 22.787c3.562 7.494 7.326 15.206 10.555 21.39c7.403 14.066 26.39 45.016 45.773 41.323c8.96-3.528 15.942-13.77 20.512-29.85S488 341.623 488 320c0-30.4-12.35-59.708-30.33-85.986"/></svg>',
   '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path class="icon" fill="currentColor" d="M12 22q-2.075 0-3.9-.788t-3.175-2.137T2.788 15.9T2 12q0-2.025.838-3.937T5.163 4.7T8.7 2.5t4.5-.45q.375.05.575.313t.225.712q.05 1.6 1.188 2.738T17.9 7q.525.025.8.3t.3.85q.05 1.05.638 1.725t1.637 1.025q.35.125.538.363t.187.587q.05 2.075-.725 3.925t-2.125 3.238t-3.2 2.187T12 22m-1.5-12q.625 0 1.063-.437T12 8.5t-.437-1.062T10.5 7t-1.062.438T9 8.5t.438 1.063T10.5 10m-2 5q.625 0 1.063-.437T10 13.5t-.437-1.062T8.5 12t-1.062.438T7 13.5t.438 1.063T8.5 15m6.5 1q.425 0 .713-.288T16 15t-.288-.712T15 14t-.712.288T14 15t.288.713T15 16"/></svg>',
   

  ];

  const container = document.getElementById('icon-container');
  let currentIndex = 0;

  function setIcon(index) {
    container.innerHTML = iconos[index];
  }

  function jump() {
    const svg = container.querySelector('svg');
    return new Promise(resolve => {
      svg.classList.add('jump');
      svg.addEventListener('animationend', () => {
        svg.classList.remove('jump');
        resolve();
      }, {once: true});
    });
  }

  function rotateShrink() {
    const svg = container.querySelector('svg');
    return new Promise(resolve => {
      svg.classList.add('rotate-shrink');
      svg.addEventListener('animationend', () => {
        svg.classList.remove('rotate-shrink');
        resolve();
      }, {once: true});
    });
  }

  function rotateGrow() {
    const svg = container.querySelector('svg');
    return new Promise(resolve => {
      svg.classList.add('rotate-grow');
      svg.addEventListener('animationend', () => {
        svg.classList.remove('rotate-grow');
        resolve();
      }, {once: true});
    });
  }

  async function animateLoop() {
    setIcon(currentIndex);
    await jump();

    while(true) {
      await rotateShrink();

      currentIndex = (currentIndex + 1) % iconos.length;
      setIcon(currentIndex);

      await rotateGrow();

      await new Promise(r => setTimeout(r, 500));

      await jump();
    }
  }

  animateLoop();

  //Redirige a iniciarSesion.php después de 3 segundos :)
  setTimeout(() => {
    window.location.href = <?= json_encode($destino) ?>;
  }, 3000);
</script>

</body>
</html>