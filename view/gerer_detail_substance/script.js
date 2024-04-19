$(document).ready(function () {
    $("#granulo, #transparence, #degre, #forme, #durete, #categorie, #dimension").hide();
    // Gérer l'affichage du formulaire particulier lorsqu'on change l'option
    $('input[type="radio"]').change(function () {
        if ($(this).attr("id") == "cin") {
            $("#particulier").show();
            $("#check_duplicata").show();
            $("#societe").hide();
            $("#num_carte_sejour_resident").val("");

            // Rendre les champs du formulaire particulier requis
            $("#particulier input, #particulier select").prop("required", true);
            // Supprimer l'attribut required des champs du formulaire société
            $("#societe input, #societe select").prop("required", false);
            $("#duplicata input, #duplicata select").prop("required", false);
            $("#check_duplicata input, #check_duplicata select").prop("required", false);
        } else {
            $("#particulier").hide();
            $("#duplicata").hide();
            $("#check_duplicata").hide();
            $('#have_duplicata').prop('checked', false);
            $("#societe").show();
            $("#num_cin").val("");
            $("#date_cin").val("");
            $("#lieu_cin").val("");
            $("#region_cin").val("");
            $("#district_cin").val("");
            $("#commune_cin").val("");
            // Supprimer l'attribut required des champs du formulaire particulier
            $("#particulier input, #particulier select").prop("required", false);
            $("#duplicata input, #duplicata select").prop("required", false);
            // Rendre les champs du formulaire société requis
            $("#societe input, #societe select").prop("required", true);
            $("#check_duplicata input, #check_duplicata select").prop("required", false);
        }
    });
});