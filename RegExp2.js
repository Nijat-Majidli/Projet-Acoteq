/*  
A l'aide de la fonction checkForm() on va contrôler le format et le type des données saisi par l'utilisateur dans la page "inscription". 
Si le format et le type des données ne sont pas bons on va afficher un message d'erreur à l'utilisateur.
*/

document.getElementById("bouton_valider").addEventListener("click", checkForm);

function checkForm()
{
    var filtreString = new RegExp(/^[A-Za-z]+$/);
    var filtreAlphanumeric = new RegExp(/^[A-Za-z0-9_.,*&ç-]+$/);
    var filtreSiren = new RegExp(/^[0-9]{9}$/);
    var filtreZipCode = new RegExp(/^[0-9]{5}$/);
    var filtreEmail = new RegExp(/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/);
    

    var RaisonSociale = document.querySelector("#RaisonSociale").value;
    var Siren = document.querySelector("#siren").value;
    var ResponsableLegale = document.querySelector("#ResponsableLegale").value;
    var Address = document.querySelector("#address").value;
    var City = document.querySelector("#city").value;
    var State = document.querySelector("#state").value;
    var ZipCode = document.querySelector("#ZipCode").value;
    var Email = document.querySelector("#email").value;


    var controlRaisonSociale = filtreAlphanumeric.test(RaisonSociale);
    var controlSiren = filtreSiren.test(Siren);
    var controlResponsableLegale = filtreString.test(ResponsableLegale);
    var controlAddress = filtreAlphanumeric.test(Address);
    var controlCity = filtreAlphanumeric.test(City);
    var controlState = filtreAlphanumeric.test(State);
    var controlZipCode = filtreZipCode.test(ZipCode);
    var controlEmail = filtreEmail.test(Email);


    if (controlRaisonSociale===false)
    {
        window.alert("Entrez un nom correct de la Raison Sociale !");
    }

    if (controlSiren===false)
    {
        window.alert("Entrez un numéro Siren valide !");
    }

    if (controlResponsableLegale===false)
    {
        window.alert("Entrez un nom et prénom valide !");
    }

    if (controlAddress===false)
    {
        window.alert("Entrez une adresse valide !");
    }

    if (controlCity===false)
    {
        window.alert("Entrez une ville correcte !");
    }

    if (controlState===false)
    {
        window.alert("Entrez un pays correct !");
    }

    if (controlZipCode===false)
    {
        window.alert("Entrez un code postal correct !");
    }

    if (controlEmail===false)
    {
        window.alert("Entrez un email correct !");
    }
}


