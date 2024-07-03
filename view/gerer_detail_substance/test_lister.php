<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau des Substances</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h2>Tableau des Substances</h2>
    <input type="text" id="search" class="form-control mb-3" placeholder="Recherche...">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Substance</th>
                <th>Granulo</th>
                <th>Transparence</th>
                <th>Degré Couleur</th>
                <th>Forme</th>
                <th>Dureté</th>
                <th>Catégorie</th>
                <th>Dimension Diamètre</th>
                <th>Prix</th>
                <th>Unité</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="substanceTable">
            <!-- Contenu généré par PHP -->
        </tbody>
    </table>
    <nav>
        <ul class="pagination" id="pagination">
            <!-- Pagination générée par PHP -->
        </ul>
    </nav>
</div>
<script src="search-pagination.js"></script>
</body>
</html>
