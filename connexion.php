<?php

  // La superglobale $_COOKIE représente le contenu de tous les cookies stockés (sous forme d'array) par votre site sur l'ordinateur du visiteur. 
  if(isset($_COOKIE['login']) && isset($_COOKIE['password']))
  {
    $login = $_COOKIE['login'];
    $password = $_COOKIE['password'];
  }

?>


<!DOCTYPE html>
<html lang="fr">
  <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">

      <!-- Responsive design -->
      <meta name="viewport" content="width=device-width, initial-scale=1.0">

      <title> Connexion </title>

      <!-- Bootstrap CDN link -->
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
  
      <!-- Fichier CSS -->
      <link rel="stylesheet" href="css/style.css">

      <!-- JQuery Google CDN: -->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  </head>
 
  <body>
    <main>
      <!-- PAGE HEAD -->
      <?php
        if (file_exists("header_accueil.php"))
        {
          include("header_accueil.php");
        }
        else
        {
          echo "file 'header_accueil.php' n'existe pas";
        }
      ?>

      <!-- PAGE CONTENT -->
      <div class="container-fluid">
        <div class="row justify-content-center no-gutters">
          <!-- LOGO -->
          <div class="col-12 col-sm-12 col-md-7 col-lg-6 col-xl-5">   
            <center>
              <section class="maison">
                <img src="../Acoteq/image/logo.png" alt="logo" title="logo" class="col-12">
              </section>
            </center>  
          </div>

          <!-- Aside  -->
          <div class="col-12 col-sm-12 col-md-5 col-lg-6 col-xl-7"> 
            <aside>
              <div class="slogan">
                <h5> Connecter vous pour accéder à tous nos services </h5> 
              </div>
              
              <form action="script_connexion.php" method="POST" autocomplete="off" class="form_connect">
                  <div class="form-group">
                    <label for="mail"> E-mail <sup>*</sup> </label>
                    <input id="mail" type="text" class="form-control col-11 col-xl-9" name="email" value='<?php echo $login;?>' required>
                  </div>
                  
                  <div class="form-group clearfix">
                    <label for="code" style="display: block;"> Mot de passe <sup>*</sup> </label> 
                    <input id="code" type="password" class="form-control col-11 col-xl-9 float-left" name="mdp" value='<?php echo $password;?>' required> 
                    <img src="image/eye_closed.png" alt="eyePicture" id="eyeIcon">  
                  </div>         

                  <div class="form-group form-check">
                    <input class="form-check-input" id="exampleCheck1" type="checkbox" name="cookie" value="rememberMe">
                    <label class="form-check-label" for="exampleCheck1"> Se souvenir de moi </label>
                  </div>

                  <a href="mdpLost.php"> Mot de passe oublié ? </a>
                  <br><br>

                  <div style="text-align: center;">
                    <button type="submit" class="btn btn-primary"> Connexion </button>
                  </div>
              </form>
            </aside>
          </div>
        </div>
      </div>
    </main>
      
  

    <!-- Bootstrap Jquery, Popper -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    

    <!-- fichier Javascript RegExp -->
    <script src="javascript/RegExp1.js"> </script>
    
    <!-- fichier Javascript pour icon Eye (à côté du champ mot de passe) -->
    <script src="javascript/ShowPassword.js"> </script>
  </body>
</html>