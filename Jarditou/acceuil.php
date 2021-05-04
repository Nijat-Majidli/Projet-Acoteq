<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">

        <!-- Responsive web design -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Bootstrap CSS 4.5.3 import from CDN -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

        <title> Acceuil </title>
    </head>


     <!-- PAGE HEAD -->
     <?php
        if (file_exists("header.php"))
        {
            include("header.php");
        }
        else
        {
            echo "file 'header.php' n'existe pas";
        }
    ?>


    <!-- PAGE MAIN CONTENT -->
    <div class="container"> 
        <div class="row mx-auto" style="font-family:Arial, Helvetica, sans-serif">
            <div class="col-12 col-lg-8 p-3">
                <article> 
                    <h2> L'entreprise </h2>
                    <p> Notre entreprise familiale met tout son savoir-faire à votre disposition dans le domaine du jardin et du paysagisme. </p>
                    <p> Créée il y a 70 ans, notre entreprise vend fleurs, arbustes, matériel à main et motorisés. </p>
                    <p> Implantés à Amiens, nous intervenons dans tout le département de la Somme : Albert, Doullens, Péronne, Abbeville, Corbie </p>
                </article>  
        
                <article> 
                    <h2> Qualité </h2>
                    <p> Nous mettons à votre disposition un service personnalisé, avec 1 seul interlocuteur durant tout votre projet. Vous serez séduit par notre expertise, nos compétences et notre sérieux. </p>
                </article> 

                <article> 
                    <h2> Devis gratuit  </h2>
                    <p> Vous pouvez bien sûr contacter pour de plus amples informations ou pour une demande d’intervention. Vous souhaitez un devis ? Nous vous le réalisons gratuitement. Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ipsum vitae dolore saepe est sequi, ipsam voluptatum accusantium officiis laboriosam praesentium vero, necessitatibus eos id commodi dolorem culpa facilis ab minus. Lorem ipsum dolor sit amet, consectetur adipisicing elit. </p>
                </article> 
            </div>
            <!-- COLONNE DROITE -->
            <div class="col-12 col-lg-4 bg-warning" style="margin-top: 20px;">
                <aside class="h2 p-4"> [Colonne de droite] </aside>
            </div>


            <!--  Les boutons  INSCRIPTION  et  LOGIN  -->
            <div style="margin: 20px 0 20px 200px">
                <a href="inscription.php"> 
                    <button style="margin-left:100px; padding:10px 15px; border-radius:10px; background-color:grey; color:white"> Inscription </button> 
                </a> 
                <a href="login.php"> 
                    <button style="margin-left:50px; padding:10px 32px; border-radius:10px; background-color:green; color:white"> Login </button> 
                </a> 
            </div>
        </div>
    </div>



    <!-- PAGE FOOT -->
    <?php
    include("footer.php")
    ?>

</html>