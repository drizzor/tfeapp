<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <title>TFEAPP / Bloqué</title>
</head>
<body>
<div class="bgimg">
  <div class="topleft">
    <p><?= '<img src="' . APPICON . '" alt="logo"> ' . SITENAME ?></p> 
  </div>
  <div class="middle">
    <h1>Bloqué !</h1>
    <hr>
    <p>Suite à un trop grand nombre de tentative de connexion vous avez été bloqué.<br><br>Si vous pensez qu'il s'agit d'une erreur, contactez le support technique.</p>
  </div>
  <div class="bottomright">
    <p><i class="far fa-sad-tear"></i></p>
  </div>
</div> 
</body>
</html>

<style>

     /* Set height to 100% for body and html to enable the background image to cover the whole page: */
body, html {
  height: 100%;
  margin: 0;
}

.bgimg {
  /* Background image */
  background: rgba(0, 0, 0, .5) url('../images/fail2.jpg');
  /* Full-screen */
  height: 100%;
  /* Center the background image */
  background-position: center;
  /* Scale and zoom in the image */
  background-size: cover;
  /* Add position: relative to enable absolutely positioned elements inside the image (place text) */
  position: relative;
  /* Add a white text color to all elements inside the .bgimg container */
  color: white;
  /* Add a font */
  font-family: "Courier New", Courier, monospace;
  /* Set the font-size to 25 pixels */
  font-size: 25px;
}

/* Position text in the top-left corner */
.topleft {
  position: absolute;
  top: 0;
  left: 16px;
}

/* Position text in the bottom-left corner */
.bottomright {
  position: absolute;
  bottom: 0;
  right: 16px;
}

/* Position text in the middle */
.middle {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  text-align: center;
}

/* Style the <hr> element */
hr {
  margin: auto;
  width: 40%;
} 

</style>