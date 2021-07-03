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
      <div class="container-fluid mb-5">
        <div class="row justify-content-center no-gutters">
        <!-- LOGO -->
          <div class="col-12 col-sm-12 col-md-5 col-lg-5 col-xl-5">   
              <section class="maison">
                <img src="../Acoteq/image/logo.png" alt="logo" title="logo" class="col-12"> 
              </section>
          </div>

          <!-- Aside  -->
          <div class="col-12 col-sm-12 col-md-7 col-lg-7 col-xl-7"> 
            <aside class="aside">
              <div class="slogan_1 col-8">
                <h5> Trouvez la meilleur option d'isolation thermique </h5>   
              </div>
  
              <div id="carouselExampleInterval" class="carousel slide w-75" data-ride="carousel">
                <div class="carousel-inner w-100">
                  <div class="carousel-item active" data-interval="2000">
                    <img src="../Acoteq/image/slide1.png" class="col-12 d-block w-100" alt="...">
                  </div>
                  <div class="carousel-item" data-interval="2000">
                    <img src="../Acoteq/image/slide2.png" class="col-12 d-block w-100" alt="...">
                  </div>
                  <div class="carousel-item">
                    <img src="../Acoteq/image/slide3.png" class="col-12 d-block w-100" alt="...">
                  </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleInterval" role="button" data-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleInterval" role="button" data-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="sr-only">Next</span>
                </a>
              </div>
            </aside>
          </div>
          
          <div style="clear: left;"> </div>
            
        </div>
      </div>    
    </main>


  

    <!-- Bootstrap Jquery, Popper -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
  </body>
</html>