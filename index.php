<?php
$db = new PDO('mysql:host=localhost;dbname=literie3000;charset=UTF8', 'root', '');


$query = $db->query("SELECT * FROM catalogue");
$beds = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Literie3000</title>
    <link rel="stylesheet" href="./Assets/css/main.css" />
</head>

<body>
    <header>
        <img src="./Assets/img/logo.png" alt="">
        <div>
            <a href="">Ajouter un produit</a>
        </div>
    </header>
    <main>
        <h1>Notre catalogue</h1>

        <ul>
            <?php
            foreach ($beds as $bed) {
            ?>
                <li class="movie">
                    <img src="./Assets/img/<?=$bed["image"] ?>" alt="">
                    <h3><?= $bed["name"] ?></h3>
                    <p><?= $bed["description"]?></p>
                    <p><?= $bed["price"]?></p>
                    <div>
                    <a href="">Modifier</a>
                    <a href="">Supprimer</a>                        
                    </div>
                </li>
            <?php
            }
            ?>
        </ul>

    </main>
</body>

</html>