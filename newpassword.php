<!DOCTYPE html>
<html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <!-- Responsive design -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <title> Login </title>

        <!-- Bootstrap CDN link -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    
         <!-- Fichier CSS -->
         <link rel="stylesheet" href="css/style.css">
    </head>



    <body>
        <div class="container p-4 mb-3 mt-3 col-6 bg-light text-dark">
            <form  action="#"  method="POST" autocomplete="off">
                <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                    <label for="email"> Veuillez saisir votre adresse e-mail pour recevoir votre mot de passe </label>
                    <input type="email" class="form-control" id="email" required>
                </div>

                <div style="text-align: center; margin-top:40px;">
                    <button type="submit" class="btn btn-success" id="bouton_valider"> Valider </button>
                    <a href="connexion.html"> <input type="button" class="btn btn-danger" value="Annuler"> </a>  
                </div>
            </form>
        </div>



        <!-- Bootstrap Jquery, Popper -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    
    
        <!-- fichier Javascript RegExp -->
        <script src="javascript/RegExp1.js"> </script>
    </body>
</html>