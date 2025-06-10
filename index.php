<!doctype html>
<?php
if(file_exists('./bot/.maintenance.txt')){
    header('location: /maintenance');
    die;
}
session_start();
?>
<!doctype html>
<html lang="en">
  <head>
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="anonymous">
    <link rel="preload" as="style" onload="this.rel='stylesheet'" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap">

    <script src="https://www.googletagmanager.com/gtag/js?id=G-BCZKLGL3D0" async></script>
    <script>window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());
	gtag('config', 'G-BCZKLGL3D0');
	</script>

    <meta charset="UTF-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no"
    />
    <link rel="icon" type="image/png" href="/favicon.ico" />
    <title>Rocky Tapbot</title>
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
    <script type="module" crossorigin src="assets/index-BYqAG32B.js?v=0.0.18"></script>
    <link rel="stylesheet" crossorigin href="assets/index-Bx_Rp-Zd.css?v=0.0.18">
  </head>
  <style>
    html {
      height: 100%;
      overflow: hidden;
    }

    body {
      height: 100%;
      overflow: hidden;
      min-height: 100%;
      isolation: isolate;
    }
  </style>
  <body style="background-color: #202229">
    <script>
      Telegram.WebApp.expand();
      Telegram.WebApp.setHeaderColor('#2A2D36');
      Telegram.WebApp.setBackgroundColor('#202229');
    </script>
    <div
      id="loader"
      style="
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
      "
    >
      <svg width="100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
        <circle
          fill="#7ee7f7"
          stroke="#7ee7f7"
          stroke-width="20"
          r="15"
          cx="40"
          cy="65"
        >
          <animate
            attributeName="cy"
            calcMode="spline"
            dur="1.5"
            values="65;135;65;"
            keySplines=".5 0 .5 1;.5 0 .5 1"
            repeatCount="indefinite"
            begin="-.4"
          ></animate>
        </circle>
        <circle
          fill="#7ee7f7"
          stroke="#7ee7f7"
          stroke-width="20"
          r="15"
          cx="100"
          cy="65"
        >
          <animate
            attributeName="cy"
            calcMode="spline"
            dur="1.5"
            values="65;135;65;"
            keySplines=".5 0 .5 1;.5 0 .5 1"
            repeatCount="indefinite"
            begin="-.2"
          ></animate>
        </circle>
        <circle
          fill="#7ee7f7"
          stroke="#7ee7f7"
          stroke-width="20"
          r="15"
          cx="160"
          cy="65"
        >
          <animate
            attributeName="cy"
            calcMode="spline"
            dur="1.5"
            values="65;135;65;"
            keySplines=".5 0 .5 1;.5 0 .5 1"
            repeatCount="indefinite"
            begin="0"
          ></animate>
        </circle>
      </svg>
    </div>

    <div id="root"></div>

  </body>
</html>