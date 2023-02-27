<?php

if (!empty($_POST)) {
    $name = trim(strip_tags($_POST["name"]));
    $MattressName = trim(strip_tags($_POST["MattressName"]));
    $MattressSize = trim(strip_tags($_POST["MattressSize"]));
    $price = trim(strip_tags($_POST["price"]));
    $promo = trim(strip_tags($_POST["promo"]));

    $errors = [];


    if (empty($name)) {
        $errors["name"] = "Le nom du lit est obligatoire";
    }


    var_dump($_FILES);
    if (isset($_FILES["picture"]) && $_FILES["picture"]["error"] === UPLOAD_ERR_OK) {

        $fileTmpPath = $_FILES["picture"]["tmp_name"];
        $fileName = $_FILES["picture"]["name"];
        $fileType = $_FILES["picture"]["type"];

        $fileNameArray = explode(".", $fileName);

        $fileExtension = end($fileNameArray);

        $newFileName = md5($fileName . time()) . "." . $fileExtension;

        $fileDestPath = "./img/{$newFileName}";

        $allowedTypes = array("image/jpeg", "image/png", "image/webp");
        if (in_array($fileType, $allowedTypes)) {

            move_uploaded_file($fileTmpPath, $fileDestPath);
        } else {
            $errors["picture"] = "Le type de fichier est incorrect (.jpg, .png ou .webp requis)";
        }
    }

    if ($price < 0) {
        $errors["price"] = "Le prix ne peut être inférieur à 0";
    }

    if ($promo < 0 && $promo > $price) {
        $errors["promo"] = "La promotion ne peut être inférieur à 0 ou suppérieur au prix ";
    }


    // Requête d'insertion en BDD de la recette s'il n'y a aucune erreur
    if (empty($errors)) {
        $id = $_GET['id'];
        // Connexion à la base marmiton
        $dsn = "mysql:host=localhost;dbname=literie3000";
        $db = new PDO($dsn, "root", "");


        $query = $db->prepare("UPDATE catalogue where id = :id (name, MattressName, picture, MattressSize, price, promo) VALUES (:name, :MattressName, :picture, :MattressSize, :price, :promo)");
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->bindParam(":name", $name);
        $query->bindParam(":MattressName", $MattressName);
        $query->bindParam(":MattressSize", $MattressSize);
        $query->bindParam(":picture", $newFileName);
        $query->bindParam(":price", $price, PDO::PARAM_INT);
        $query->bindParam(":promo", $promo, PDO::PARAM_INT);

        if ($query->execute()) {
            // La requête s'est bien déroulée donc on redirige l'utilisateur vers la page d'accueil
            header("Location: index.php");
        } else {
            echo "pb en BDD";
        }
    } else {
        echo "il y a une erreur";
    }
}
$db = new PDO('mysql:host=localhost;dbname=literie3000;charset=UTF8', 'root', '');

$find = false;
$data = array("name" => "Produit introuvable");
if (isset($_GET["id"])) {
    $id = $_GET['id'];
    $query = $db->prepare('SELECT * from catalogue where id = :id');
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $bed = $query->fetch(); 

    if ($bed) {
        $find = true;
        $data = $bed;
    }
}
include("templates/header.php");
?>
<main>
    <div class="container">
        <?php
        if ($find) {
        ?>
            <h1>Modification du lit <?= $data["name"] ?> </h1>

            <form action="" method="post" enctype="multipart/form-data">
                <div>
                    <label for="inputPicture"> Image</label>
                    <input type="file" name="picture" id="inputPicture" value="<?= $data["picture"] ?>">
                    <?php
                    if (isset($errors["picture"])) {
                    ?>
                        <span class="info-error"><?= $errors["picture"] ?></span>
                    <?php
                    }
                    ?>
                </div>

                <label for="inputName">Nom du lit</label>
                <input type="text" name="name" id="inputName" value="<?= $data["name"] ?>">

                <label for="inputMatName">Nom du Matelas</label>
                <input type="text" name="MattressName" id="inputMatName" value="<?= $data["MattressName"] ?>">

                <label for="inputMatSize">Taille du Matelas</label>
                <select name="MattressSize" id="inputMatSize">
                    <option <?= isset($MattressSize) && $MattressSize === "90x190" ? "selected" : "" ?> value="taille1">90x190</option>
                    <option <?= isset($MattressSize) && $MattressSize === "140x190" ? "selected" : "" ?> value="taille2">140x190</option>
                    <option <?= isset($MattressSize) && $MattressSize === "160x200" ? "selected" : "" ?> value="taille3">160x200</option>
                </select>

                <div>
                    <label for="inputPrice">Prix (en euro)</label>
                    <input type="number" name="price" id="inputPrice" value="<?= $data["price"] ?>">
                    <?php
                    if (isset($errors["price"])) {
                    ?>
                        <span class="info-error"><?= $errors["price"] ?></span>
                    <?php
                    }
                    ?>
                </div>

                <div>
                    <label for="inputPromo">Prix apres promotion(en euro)</label>
                    <input type="number" name="promo" id="inputPromo" value="<?= $data["promo"] ?>">
                    <?php
                    if (isset($errors["promo"])) {
                    ?>
                        <span class="info-error"><?= $errors["promo"] ?></span>
                    <?php
                    }
                    ?>
                </div>

                <input type="submit" value="Valider la modification">
            </form>
    </div>
<?php
        } else {
?>
    <h1><?= $data["name"] ?></h1>
<?php
        }
?>
</main>
</body>

</html>