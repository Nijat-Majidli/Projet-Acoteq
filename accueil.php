<!DOCTYPE html>
<html lang="fr">
  <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">

      <!-- Responsive design -->
      <meta name="viewport" content="width=device-width, initial-scale=1.0">

      <title> Accueil </title>

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
        <section class="maison">
          <img src="../Acoteq/image/logo.png" alt="logo" title="logo">
        </section>

        <aside>
          <div id="slogan_1">
            <center> <h2> Trouvez la meilleur option d'isolation thermique </h2>  </center> 
          </div>
        </aside>

      </div>
    
    </main>
  </body>
</html>