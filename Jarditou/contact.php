<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">

        <!-- Responsive web design -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Bootstrap CSS 4.5.3 import from CDN -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

        <title> Contact </title>
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
    <!-- Vos coordonnées -->
      <div class="container border border-dark px-4 pb-4"  style="width:62%"> 
        <div class="col p-0">
            <p class="pt-3"> <sup>*</sup> Ces zones sont obligatoires </p>
            <h1 class="pb-3"> Vos Coordonnées </h1>
            <form>
              <div class="form-group">
                <label for="formGroupExampleInput1"> Nom <sup>*</sup> </label>
                <input type="text" class="form-control" id="formGroupExampleInput1" placeholder="Veuillez saisir votre nom" required>
              </div>
              <div class="form-group">
                <label for="formGroupExampleInput2"> Prénom <sup>*</sup> </label>
                <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Veuillez saisir votre prénom" required>
              </div>
            </form>

            <p> Sexe <sup>*</sup> </p>
            <div class="custom-control custom-radio custom-control-inline">
              <input type="radio" id="customRadioInline1" name="customRadioInline1" class="custom-control-input" required>
              <label class="custom-control-label" for="customRadioInline1"> Féminin </label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
              <input type="radio" id="customRadioInline2" name="customRadioInline1" class="custom-control-input" required>
              <label class="custom-control-label" for="customRadioInline2"> Masculin </label>
            </div>
            
            <form class="mt-4">
              <div class="form-group">
                <label for="formGroupExampleInput3"> Date de naissance <sup>*</sup> </label>
                <input type="date" class="form-control" id="formGroupExampleInput3" required>
              </div>
              <div class="form-group">
                <label for="formGroupExampleInput4"> Code postal <sup>*</sup> </label>
                <input type="text" class="form-control" id="formGroupExampleInput4" required>
              </div>
              <div class="form-group">
                <label for="formGroupExampleInput5"> Adresse </label>
                <input type="text" class="form-control" id="formGroupExampleInput5">
              </div>
              <div class="form-group">
                <label for="formGroupExampleInput6"> Ville </label>
                <input type="text" class="form-control" id="formGroupExampleInput6">
              </div>
              <div class="form-group">
                <label for="exampleFormControlInput1"> Email <sup>*</sup> </label>
                <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="dave.loper@afpa.fr" required>
              </div>
            </form>
            <!-- Votre demande -->
            <h1> Votre demande </h1>
            <form>
              <div class="form-group">
                <label for="exampleFormControlSelect1">Sujet</label>
                <select class="form-control" id="exampleFormControlSelect1">
                      <option> Veuillez sélectionner un sujet </option>
                      <option> Mes commandes </option>
                      <option> Question sur un produit </option>
                      <option> Réclamation </option>
                      <option> Autres </option>
                </select>
              </div>
              <div class="form-group">
                <label for="exampleFormControlTextarea1"> Votre question <sup>*</sup> </label>
                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" required></textarea>
              </div>
              <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                <label class="form-check-label" for="exampleCheck1"> J'accepte le traitement de ce formullaire. </label>
              </div>
              <button type="submit" class="btn btn-dark" onclick=controler()> Envoyer </button>
              <button type="reset" class="btn btn-dark"> Annuler </button>
          </form>
        </div>
    </div>



    <!-- PAGE FOOT -->
    <?php
    include("footer.php")
    ?>

</html>