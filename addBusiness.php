<!DOCTYPE html>
<html lang="fr" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, user-scalable=no">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
        <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/style.css">
        <title>CRM</title>
    </head>
    <body>
        <?php
        $user = "admin";
        $pass = "plop";
        $db = new PDO('mysql:host=localhost;dbname=crm_bdd', $user, $pass);
        if ($_POST) {
            $newName = $_POST["name"];
            $newAddress = $_POST["address"];
            $insertLine = $db->prepare("INSERT INTO Business VALUES (NULL, :name, :address)");
            $insertLine->execute([':name' => $newName, ":address" => $newAddress]);
            header("location:index.php");
        }?>
        <div class="container-fluid p-0">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <h1><a class="navbar-brand" href="index.php">My mini (putain de) CRM</a></h1>
                <button
                    class="navbar-toggler" type="button"
                    data-toggle="collapse"
                    data-target="#navbarNav"
                    aria-controls="navbarNav"
                    aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Listings</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="addCustomer.php">Ajouter Client</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="addBusiness.php">Ajouter Entreprise</a>
                        </li>
                    </ul>
                </div>
            </nav>
            <h2>Ajout d'une nouvelle entreprise</h2>
            <form action="addBusiness.php" method="post">
                <input class="form-control" type="text" name="name" placeholder="Dénomination" required>
                <input class="form-control" type="text" name="address" placeholder="Adresse Complète" required>
                <button class="btn btn-primary" type="submit" name="submit">Enregistrer</button>
            </form>
        </div>
        <script src="node_modules/jquery/dist/jquery.min.js"></script>
        <script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="js/script.js"></script>
    </body>
</html>
