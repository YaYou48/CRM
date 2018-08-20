<!-- DEBUT DU HEADER.PHP
tu peux factoriser tout le début de tes fichiers php dans un fichier séparé et l'include dans tous les autres php
 -->
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
        <?php $user = "admin";
        $pass = "plop";
        $db = new PDO('mysql:host=localhost;dbname=crm_bdd', $user, $pass);
// FIN DU HEADER.PHP
        // le nom de la variable doit être au pluriel
        // espace avant et après le =
        $customer=$db->query("SELECT * FROM Customers")->fetchAll();
        $business=$db->query("SELECT * FROM Business")->fetchAll();
        if ($_GET) {
            // c'est pas parce que tu es en GET que t'es assuré d'avoir les valeurs 'category' et 'id'
            // ta condition doit plutot ressemblé à isset($_GET['category']) && !empty($_GET['category']) && isset($_GET['id']) && !empty($_GET['id'])
            $selectedCategory = $_GET['category'];
            $deletedID = $_GET['id'];
            $db->query("SET foreign_key_checks = 0"); //pourquoi tu veux ca ?
            //bien vu pour le passage en paramètre du nom de la table dans laquelle deleter ! ;)
            $delete = $db->prepare("DELETE FROM $selectedCategory WHERE id=$deletedID");
            $delete->execute();
            $db->query("SET foreign_key_checks = 1");
            header("location:index.php");
        }?>
        <div class="container-fluid p-0">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <h1><a class="navbar-brand" href="index.php">My mini (putain de) CRM</a></h1>  <!-- grossier personnage -->
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
                        <li class="nav-item active">
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
            <h2>Listing clients & entreprises</h2>
            <div class="list-group" id="list-tab" role="tablist">
                <a class="col-6 list-group-item list-group-item-action active" id="list-customer-list"
                data-toggle="list" href="#list-customer" role="tab" aria-controls="customer">Client (<?php echo count($customer)?>)</a>
                <a class="col-6 list-group-item list-group-item-action" id="list-business-list"
                data-toggle="list" href="#list-business" role="tab" aria-controls="business">Entreprise (<?php echo count($business)?>)</a>
            </div>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="list-customer" role="tabpanel" aria-labelledby="list-customer-list">
                    <div id="accordionCustomer">
                        <div class="input-group">
                            <input class="form-control" type="search" placeholder="Rechercher client" id="myCustomerInput">
                            <div class="input-group-append">
                                <button class="btn btn-outline-primary" type="button" id="customerInputCancel"><i class="fas fa-ban"></i></button>
                            </div>
                        </div>
                    <?php 
                        //si tu avais appelé ta variable $customers tu aurais pu écrire foreach ($customers as $customer)
                        //sémantiquement plus efficient
                        foreach ($customer as $value): 
                    ?>
                    <?php 
                       //un coup du fait du query, un coup du faire du prepare/execute
                       // 1) faut choisir son camps pour un question de cohérence de code
                       // 2) quand y'a pas de paramètre à concater, tu peux faire du query, 
                       // sinon prepare/execuse pour éviter les injections SQL https://goo.gl/dg1uvQ
                       $customerBusiness=$db->query("SELECT * FROM Business WHERE id=".$value['business_id'])->fetch();
                    ?>
                        <div class="card customerCard">
                            <div class="card-header"
                            id="headingC<?php echo $value['id'] ?>">
                            <h5 class="mb-0 customerName">
                                <button
                                    class="btn btn-link" data-toggle="collapse" data-target="#collapseC<?php echo $value['id'] ?>" aria-expanded="false"><?php echo $value['name'] ?>
                                </button>
                            </h5>
                            </div>
                            <div id="collapseC<?php echo $value['id'] ?>" class="collapse" aria-labelledby="headingC<?php
                            echo $value['id'] ?>" data-parent="#accordionCustomer">
                                <div class="card-body">
                                    <div class="media">
                                        <img class="mr-3" src="https://picsum.photos/150/200/?random" alt="Generic placeholder image">
                                        <div class="media-body">
                                            <h5 class="mt-0"><?php echo $value['name']?>
                                                <span>
                                                    <a href="editCustomers.php?id=<?php echo $value['id'] ?>">
                                                    <i class="fas fa-edit"></i></a>
                                                    <a data-toggle="modal" href="#deleteCustomer<?php echo $value['id'] ?>">
                                                    <i class="fas fa-trash-alt"></i></a>
                                                </span>
                                            </h5>
                                            <p><?php echo $value['address'] ?></p>
                                            <a href="#collapseE<?php echo $customerBusiness['id']?>" class="toBusinessCard"><?php echo $customerBusiness['name']?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- tu génère une modal par lien de suppression ... trop trop trop lourd en terme de code généré
                        ta page HTML va devenir énorme est donc lourde à charger
                        Il faut mieux faire plus de JS et moins d'HTML
                        Il faut que tu fasses une et une seule modal « à trou » que tu complétement en JS en fonction du lien cliquer
                         -->
                        <div class="modal fade" id="deleteCustomer<?php echo $value['id'] ?>"tabindex="-1" role="dialog" aria-labelledby="delete-Customer" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteCustomer<?php echo $value['id'] ?>">
                                            <?php echo $value['name'] ?>
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="mb-0">Voulez-vous vraiment effacer cette entrée ?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                        <a href="index.php?category=Customers&id=<?php echo $value['id']?>" class="btn btn-primary">Confirmer</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach;?>
                    </div>
                </div>
                <!-- les remarques pour la partie client s'applique aussi à la partie Entreprise -->
                <div class="tab-pane fade" id="list-business"
                role="tabpanel" aria-labelledby="list-business-list">
                    <div id="accordionBusiness">
                        <div class="input-group">
                            <input class="form-control" type="search" placeholder="Rechercher entreprise" id="myBusinessInput">
                            <div class="input-group-append">
                                <button class="btn btn-outline-primary" type="button" id="businessInputCancel"><i class="fas fa-ban"></i></button>
                            </div>
                        </div>
                        <?php foreach ($business as $value):?>
                        <div class="card businessCard">
                            <div class="card-header"
                            id="headingE<?php echo $value['id'] ?>">
                                <h5 class="mb-0 businessName" id="businessID<?php echo $value['id'] ?>">
                                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseE<?php echo $value['id'] ?>" aria-expanded="true"><?php echo $value['name'] ?>
                                    </button>
                                </h5>
                            </div>
                            <div id="collapseE<?php echo $value['id'] ?>" class="collapse"
                                aria-labelledby="headingE<?php echo $value['id'] ?>"
                                data-parent="#accordionBusiness">
                            <div class="card-body">
                                    <div class="media">
                                        <img class="mr-3" src="https://picsum.photos/150/200/?random" alt="Generic placeholder image">
                                        <div class="media-body">
                                            <h5 class="mt-0">
                                            <?php echo $value['name'] ?>
                                                <span>
                                                    <a href="editBusiness.php?id=<?php  echo $value['id'] ?>">
                                                    <i class="fas fa-edit"></i></a>
                                                    <a data-toggle="modal" href="#deleteBusiness<?php echo $value['id'] ?>">
                                                    <i class="fas fa-trash-alt"></i></a>
                                                </span>
                                            </h5>
                                            <p><?php echo $value['address'] ?></p>
                                            <?php $worker = $db->query("SELECT * FROM Customers WHERE business_id=".$value['id'])->fetchAll();?>
                                            <ul>
                                            <?php foreach ($worker as $actualWorker):?>
                                                <li>
                                                    <a href="#collapseC<?php echo $actualWorker['id']?>" class="toCustomerCard">
                                                        <?php echo $actualWorker['name'];?>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="deleteBusiness<?php echo $value['id'] ?>"tabindex="-1" role="dialog" aria-labelledby="delete-Business" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered"
                            role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteBusiness<?php echo $value['id'] ?>">
                                            <?php echo $value['name'] ?>
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="mb-0">Voulez-vous vraiment effacer cette entrée ?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                        <a href="index.php?category=Business&id=<?php echo $value['id']?>" class="btn btn-primary">Confirmer</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach;?>
                    </div>
                </div>
            </div>
        </div>
<!-- meme si c'est moi important vu le nombre de ligne que ca represente, tu peux aussi faire un FOOTER.PHP et l'include -->
        <script src="node_modules/jquery/dist/jquery.min.js"></script>
        <script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="js/script.js"></script>
    </body>
</html>
<!-- FIN DU FOOTER.PHP -->
