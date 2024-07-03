$(document).ready(function() {
    function fetchfactures(page, search) {
        $.ajax({
            url: 'fetch_liste_facture.php',
            method: 'POST',
            data: {
                page: page,
                search: search
            },
            dataType: 'json',
            success: function(response) {
                let tableBody = $('#factureTable');
                tableBody.empty();
                $.each(response.factures, function(index, facture) {
                    tableBody.append(`<tr>
                        <td>${facture.num_facture}</td>
                        <td>${facture.date_facture}</td>
                        <td>${facture.nom_transparence}</td>
                        <td>${facture.nom_societe_importateur}</td>
                        <td>${facture.nom_societe_expediteur}</td>
                        <td><a href="liste_contenu_facture.php?id=<?php echo $row['id_data_cc']; ?>" class="link-dark">détails</a></td>
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

    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        let page = $(this).data('page');
        let search = $('#search').val();
        fetchfactures(page, search);
    });

    $('#search').on('keyup', function() {
        let search = $(this).val();
        fetchfactures(1, search);
    });

    fetchfactures(1, '');
});
