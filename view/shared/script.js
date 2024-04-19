
function openModal() {
    var myModal = new bootstrap.Modal(document.getElementById('staticBackdrop'), {
        backdrop: 'static',
        keyboard: false
    });
    myModal.show();
}
// Fonction pour fermer la modal
function closeModal() {
    console.log("Fermeture de la modal"); // Ajout de cette ligne
    var myModal = new bootstrap.Modal(document.getElementById('staticBackdrop'));
    myModal.hide();
}
// Fonction pour confirmer la suppression
function confirmDeletion() {
    // Ici, vous pouvez ajouter le code PHP pour effectuer la suppression
    // Par exemple, vous pouvez utiliser une requête AJAX pour appeler un script PHP de suppression
    console.log("Suppression confirmée");
    closeModal(); // Fermer la modale après la confirmation
}