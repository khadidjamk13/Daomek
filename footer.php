<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <style>
        /*footer footer footer footer footer footer footer*/

.copy {
  position: static;
  display: contents;
}
.col {
  width: 100%; /* Default to full width for smaller screens */
  max-width: 500px; /* Optional: Set a maximum width for tablets */
  text-align: center;
  padding: 20px;
}

footer h3 {
  font-size: 18px;
  position: relative;
}
.footer p {
  color: white;
  margin: 15px 0 10px 0;
  font-size: 1rem;
  font-weight: 300;
}

.wave#wave1 {
  z-index: 1000;
  opacity: 1;
  bottom: 0;
  animation: animateWaves 3s linear infinite;
}

.wave#wave2 {
  z-index: 999;
  opacity: 0.7;
  bottom: 10px;
  animation: animate 3s linear infinite !important;
}

.wave#wave3 {
  z-index: 1000;
  opacity: 0.5;
  bottom: 15px;
  animation: animateWaves 3s linear infinite;
}

.wave#wave4 {
  z-index: 999;
  opacity: 0.7;
  bottom: 10px;
  animation: animate 3s linear infinite;
}

@keyframes animateWaves {
  0% {
    background-position-x: 1000px;
  }
  100% {
    background-positon-x: 0px;
  }
}

@keyframes animate {
  0% {
    background-position-x: -1000px;
  }
  100% {
    background-positon-x: 0px;
  }
}

footer h3 {
  font-size: 18px;
  position: relative;
  color: white;
}
.footer p {
  color: white;
  margin: 15px 0 10px 0;
  font-size: 1rem;
  font-weight: 300;
}
.wave {
  position: absolute;
  top: -100px;
  left: 0;
  width: 100%;
  height: 120px;
  background: url("wave-6.svg");
  background-size: 1000px 125px;
}
body {
  background-color: #eeeee7;
}
.footer {
  position: relative;
  width: 100%;
  background-color: rgb(31, 30, 30);
  display: flex;
  height: 400px;
}

    </style>
</head>
<body>
<div id="marg"></div>

<footer class="footer">
  <div class="waves">
    <div class="wave" id="wave1"></div>
    <div class="wave" id="wave2"></div>
    <div class="wave" id="wave3"></div>
    <div class="wave" id="wave4"></div>
  </div>
  <div class="col">
    <h3>À propos du site</h3>
    <P>L'hebdo immobilier est un site spécialisé dans la publication de petites annonces immobilières en Algérie entre particuliers et professionnels</P>
  </div>
  <div class="col">
    <h3>Contact info</h3>
    <a href="mailto:propriété.par@gmail.com">propriété.par@gmail.com</a><br />
    <p> 00213-66-66-66</p>
  </div>
  <div class="col">
    <h3>Information</h3>
    <p>Ajouter une annonce
      Qui sommes-nous
      Contactez-nous
      Terms et conditions
      Annuaire Agences Mon Compte
    </p>
  </div>
  <p>&copy;2024 | All Rights Reserved</p>
</footer>
</body>
</html>