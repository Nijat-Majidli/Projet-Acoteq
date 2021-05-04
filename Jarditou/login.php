<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">

        <!-- Responsive web design -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Bootstrap CSS 4.5.3 import from CDN -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

        <title> Login </title>
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
    <div class="container" style="margin: 100px 0"> 
            <form  method="post"  action="script_login.php"  style="margin-left:600px">
                <div class="form-group">
                    <label for="username"> Login <sup>*</sup> </label>
                    <input type="text"  id="username"  name="login"  style="margin-left:75px">
                </div>
                <div class="form-group">
                    <label for="code"> Mot de passe <sup>*</sup> </label>
                    <input type="password"  id="code"  name="mdp"  style="margin-left:20px">
                </div>
                <br>

                 <!--  Le bouton ENTRER -->
                <div>
                    <input type="submit" value="Entrer" style="float:left; padding:10px 40px; border-radius:10px; background-color:green; color:white"> 
                </div>
            </form>


             <!--  Le bouton ANNULER  -->
            <a href="acceuil.php"> 
                <button style="margin-left:50px; padding:10px 33px; border-radius:10px; background-color:red; color:white"> Annuler </button> 
            </a>
    </div>



    <!-- PAGE FOOT -->
    <?php
    include("footer.php")
    ?>

</html>