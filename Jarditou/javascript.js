function controler()
{
    var filtreString = new RegExp(/^[A-Za-zàâæçéèêëîïôœùûüÿ ]+$/);   // à la fin de regexp on a ajouté un espace pour autoriser la saisie de l'espace blanc par l'utilisateur
    var filtreAlphanumeric = new RegExp(/^[A-Za-z0-9 àâæçéèêëîïôœùûüÿ_&!§$£@*',. -]+$/);   // aprés 0-9 on a ajouté un espace pour autoriser la saisie de l'espace blanc par l'utilisateur
	var filtreEmail = new RegExp(/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/);
    var filtreCodePostal = new RegExp(/^[0-9]{5}$/);
    var filtreDate = new RegExp("^[0-9][0-9]?\/[0-9][0-9]?\/([0-9][0-9]){2}$");
    var filtreAdresse = new RegExp("^[A-Za-z0-9]+$");


    var nom = document.getElementById("surname").value;
    var prenom = document.getElementById("name").value;
    var email = document.getElementById("email").value;
    // var féminin = document.getElementById("customRadioInline1").value;
    // var masculin = document.getElementById("customRadioInline2").value;
    // var date = document.getElementById("formGroupExampleInput3").value;
    // var codePostal = document.getElementById("formGroupExampleInput4").value;
    // var adresse = document.getElementById("formGroupExampleInput5").value;
    // var ville = document.getElementById("formGroupExampleInput6").value;
    // var question = document.getElementById("exampleFormControlTextarea1").value;
    // var accepter = document.getElementById("exampleCheck1").value;


    var controlNom = filtreString.test(nom);
    var controlPrénom = filtreString.test(prenom);
    var controlEmail = filtreEmail.test(email);
    // var controlFéminin = filtreString.test(féminin);
    // var controlMasculin = filtreString.test(masculin);
    // var controlDate = filtreDate.test(date);
    // var controlPostal = filtreCodePostal.test(codePostal);
    // var controlAdresse = filtreAdresse.test(adresse);
    // var controlVille = filtreString.test(ville);
    // var controlQuestion = filtreString.test(question);
    // var controlAccepter = filtreString.test(accepter);


    if (controlNom==false)
    {
        window.alert("Entrez le nom s.v.p. !");
    }

    if (controlPrénom==false)
    {
        window.alert("Entrez le prénom s.v.p. !");
    }

    if (controlEmail==false)
    {
        window.alert("Entrez email s.v.p. !");
    }

    // if (controlFéminin==false)
    // {
    //     window.alert("Entrez le sexe s.v.p. !");
    // }

    // if (controlMasculin==false)
    // {
    //     window.alert("Entrez le sexe s.v.p. !");
    // }

    // if (controlDate==false)
    // {
    //     window.alert("Entrez la date s.v.p. !");
    // }

    // if (controlPostal==false)
    // {
    //     window.alert("Entrez code postal s.v.p. !");
    // }

    // if (controlAdresse==false)
    // {
    //     window.alert("Entrez l'adresse s.v.p. !");
    // }

    // if (controlVille==false)
    // {
    //     window.alert("Entrez la ville s.v.p. !");
    // }

    // if (controlQuestion==false)
    // {
    //     window.alert("Entrez la quéstion s.v.p. !");
    // }

    // if (controlAccepter==false)
    // {
    //     window.alert("Cochez la case s.v.p. !");
    // }

}

controler();