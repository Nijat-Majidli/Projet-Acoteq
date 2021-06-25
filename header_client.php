<!-- ATTENTION : 
Les pages comme "header.php" ou "footer.php" doivent contenir que les codes html et php nécessaire. 
Dans la page "header.php" ou "footer.php" il ne faut pas mettre ni balise <doctype>, ni balise <html>, ni de balise <head>, 
on doit écrire juste les codes nécessaire.  -->

<div class="container">
    <header>
        <!-- Navigation Bar -->
        <nav class="navbar navbar-expand-sm navbar-light bg-light">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active mr-2">
                        <a class="nav-link" href="client.php"> Accueil <span class="sr-only">(current)</span> </a>
                    </li>

                    <li class="nav-item dropdown mr-2">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Équipes
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="equipeNew.php"> Nouvelle équipe </a>
                            <a class="dropdown-item" href="equipeCreated.php"> Mes équipes crées </a>
                            <a class="dropdown-item" href="equipeMember.php"> Les équipes dont je fais partie </a>
                        </div>
                    </li>
                    
                    <li class="nav-item dropdown mr-2">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Demandes
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="demandeNew.php"> Nouvelle demande </a>
                            <a class="dropdown-item" href="demandeSaved.php"> Demandes sauvegardées </a>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Profil
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="infoPerso.php"> Infos personelles </a>
                            <a class="dropdown-item" href="script_deconnexion.php"> Déconnexion </a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <br><br>
</div>
    
   