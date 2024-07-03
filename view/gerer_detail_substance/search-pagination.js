$(document).ready(function () {
    function fetchSubstances(page, search) {
        $.ajax({
            url: 'fetch_substances.php',
            method: 'POST',
            data: {
                page: page,
                search: search
            },
            dataType: 'json',
            success: function (response) {
                let tableBody = $('#substanceTable');
                tableBody.empty();
                $.each(response.substances, function (index, substance) {
                    tableBody.append(`<tr>
                        <td>${substance.nom_substance}</td>
                        <td>${substance.nom_granulo}</td>
                        <td>${substance.nom_transparence}</td>
                        <td>${substance.nom_degre_couleur}</td>
                        <td>${substance.nom_forme_substance}</td>
                        <td>${substance.nom_durete}</td>
                        <td>${substance.nom_categorie}</td>
                        <td>${substance.nom_dimension_diametre}</td>
                        <td>${substance.prix_substance}</td>
                        <td>${substance.unite_prix_substance}</td>
                        <td><a href="#" class="link-dark edit-substance" data-id="${substance.id_detaille_substance}"><i class="fa-solid fa-pen-to-square me-3"></i></a>
                        <a href="#" class="link-dark" onclick="confirmerSuppression(${substance.id_detaille_substance})"><i class="fa-solid fa-trash"></i></a></td>
                    </tr>`);
                });

                let pagination = $('#pagination');
                pagination.empty();
                let totalPages = Math.ceil(response.total / response.limit);
                let currentPage = response.page;

                if (currentPage > 1) {
                    pagination.append(`<li class="page-item">
                        <a class="page-link" href="#" data-page="${currentPage - 1}">Précédent</a>
                    </li>`);
                }

                pagination.append(`<li class="page-item disabled">
                    <span class="page-link">${currentPage}/${totalPages}</span>
                </li>`);

                if (currentPage < totalPages) {
                    pagination.append(`<li class="page-item">
                        <a class="page-link" href="#" data-page="${parseInt(currentPage) + 1}">Suivant</a>
                    </li>`);
                }

                let pagination2 = $('#pagination2');
                pagination2.empty();
                let startPage = Math.max(1, Math.ceil(currentPage / 10) * 10 - 9);
                let endPage = Math.min(startPage + 9, totalPages);

                for (let i = startPage; i <= endPage; i++) {
                    pagination2.append(`<li class="page-item ${i == currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>`);
                }
            }
        });
    }

    $(document).on('click', '.page-link', function (e) {
        e.preventDefault();
        let page = $(this).data('page');
        let search = $('#search').val();
        fetchSubstances(page, search);
    });

    $('#search').on('keyup', function () {
        let search = $(this).val();
        fetchSubstances(1, search);
    });

    //modification
    $(document).on('click', '.edit-substance', function (e) {
        e.preventDefault();
        let substanceId = $(this).data('id');
        // Fetch the substance details
        $.ajax({
            url: 'get_substance.php',
            method: 'POST',
            data: { id: substanceId },
            dataType: 'json',
            success: function (response) {
                $('#editSubstanceId').val(response.id_detaille_substance);
                $('#id_substance').val(response.nom_substance !== null ? response.nom_substance : 'Aucune');
                $('#id_granulo').val(response.nom_granulo !== null ? response.nom_granulo : 'Aucune');
                $('#id_couleur_substance').val(response.nom_couleur_substance !== null ? response.nom_couleur_substance : 'Aucune');
                $('#id_transparence').val(response.nom_transparence !== null ? response.nom_transparence : 'Aucune');
                $('#id_degre_couleur').val(response.nom_degre_couleur !== null ? response.nom_degre_couleur : 'Aucune');
                $('#id_forme_substance').val(response.nom_forme_substance !== null ? response.nom_forme_substance : 'Aucune');
                $('#id_categorie').val(response.nom_categorie !== null ? response.nom_categorie : 'Aucune');
                $('#id_durete').val(response.nom_durete !== null ? response.nom_durete : 'Aucune');
                $('#id_dimension_diametre').val(response.nom_dimension_diametre !== null ? response.nom_dimension_diametre : 'Aucune');
                $('#prix_substance').val(response.prix_substance !== null ? response.prix_substance : 'Aucune');
                // Populate other fields as necessary

                $('#editSubstanceModal').modal('show');
            }
        });

    });
    //update
    $('#editSubstanceForm').on('submit', function (e) {
        e.preventDefault();

        let substanceData = {
            id: $('#editSubstanceId').val(),
            prix_substance: $('#prix_substance').val(),
            // Collect other fields as necessary
        };

        // Mettre à jour les détails de la substance
        $.ajax({
            url: 'update_substance.php',
            method: 'POST',
            data: substanceData,
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    alert('Modification réussie !');
                } else {
                    alert('Échec de la modification : ' + (response.error || 'Erreur inconnue'));
                }
                $('#editSubstanceModal').modal('hide');
                fetchSubstances(1, '');
            }
        });
    });


    fetchSubstances(1, '');
});
