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
        if ($_GET) {
            $businessID = $_GET['id'];
            $business= $db->query("SELECT * FROM Business WHERE id=".$businessID)->fetch();
        }
        if ($_POST) {
            $businessID = $_POST['id'];
            $newName = $_POST['name'];
            $newAddress = $_POST['address'];
            $modBusiness = $db->prepare("UPDATE Business SET name = :name, address = :address WHERE id=".$businessID);
            $modBusiness->execute([':name' => $newName, ':address' => $newAddress]);
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
                            <a class="nav-link" href="addBusiness.php">Ajouter Entreprise</a>
                        </li>
                    </ul>
                </div>
            </nav>
            <h2>Modifier données entreprise</h2>
            <form action="editBusiness.php" method="post">
                <input type="hidden" name="id" value="<?php echo $business['id'] ?>" placeholder="hiddenID">
                <input class="form-control" type="text" name="name" value="<?php echo $business['name'] ?>" placeholder="Dénomination" required>
                <input class="form-control" type="text" name="address" value="<?php echo $business['address'] ?>" placeholder="Adresse Complète" required>
                <button class="btn btn-primary" type="submit" name="submit">Modifier</button>
            </form>
        </div>
        <script src="node_modules/jquery/dist/jquery.min.js"></script>
        <script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="js/script.js"></script>
    </body>
</html>
