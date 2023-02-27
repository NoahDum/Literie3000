<?php
$db = new PDO('mysql:host=localhost;dbname=literie3000;charset=UTF8', 'root', '');


$query = $db->query("SELECT * FROM catalogue");
$beds = $query->fetchAll(PDO::FETCH_ASSOC);
include("templates/header.php");
?>
    <main>
        <h1>Notre catalogue</h1>

        <div class="table">
            <ul>
                <?php
                foreach ($beds as $bed) {
                ?>
                    <li>
                        <img src="./Assets/img/<?= $bed["image"] ?>" alt="">
                        <h3><?= $bed["name"] ?></h3>
                        <div>
                        <p><?= $bed["MattressName"] ?></p> 
                        <p><?= $bed["MattressSize"] ?></p>                         
                        </div>
                        <div class="price">
                            <p class="prix"><?= $bed["price"] ?> €</p>
                            <p class="promo"><?= $bed["promo"] ?> €</p>
                        </div>
                        <div>
                            <a href="edit.php?id=<?= $bed["id"] ?>" class="edit">Modifier</a>
                            <a href="" class="delete">Supprimer</a>
                        </div>
                    </li>
                <?php
                }
                ?>
            </ul>
        </div>

    </main>
</body>

</html>