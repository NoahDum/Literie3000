<?php
if (!empty($_POST)) {
    $name = trim(strip_tags($_POST["name"]));
    $MattressName = trim(strip_tags($_POST["MattressName"]));
    $MattressSize = trim(strip_tags($_POST["MattressSize"]));
    $price = trim(strip_tags($_POST["price"]));
    $promo = trim(strip_tags($_POST["promo"]));
    $errors = [];

    // Valider que le champ name est bien renseigné
    if (empty($name)) {
        $errors["name"] = "Le nom du produit est obligatoire";
    }

    // Gestion de l'upload de la photo de notre recette
    var_dump($_FILES);
    if (isset($_FILES["inputPicture"]) && $_FILES["inputPicture"]["error"] === UPLOAD_ERR_OK) {
        // Le fichier avec l'attribut name qui vaut picture existe et qu'il n'y a pas eu d'erreur d'upload
        $fileTmpPath = $_FILES["inputPicture"]["tmp_name"];
        $fileName = $_FILES["inputPicture"]["name"];
        $fileType = $_FILES["inputPicture"]["type"];

        // Récupération de l'extension du fichier uploadé
        $fileNameArray = explode(".", $fileName);
        // La fonction end() est très pratique pour récupérer le dernier élément d'un tableau
        $fileExtension = end($fileNameArray);
        // On génère un nouveau nom de fichier pour ne pas se préoccuper des espaces, caractères accentués mais aussi si des personnes upload plusieurs images ayant le même nom
        // L'ajout de time() permet d'être sur d'avoir un hash unique
        $newFileName = md5($fileName . time()) . "." . $fileExtension;
        // Attention à vérifier que le dossier de destination est bien créé au préalable
        $fileDestPath = "./img/{$newFileName}";

        $allowedTypes = array("image/jpeg", "image/png", "image/webp");
        if (in_array($fileType, $allowedTypes)) {
            // Le type de fichier est bien valide on peut donc ajouter le fichier à notre serveur
            move_uploaded_file($fileTmpPath, $fileDestPath);
        } else {
            // Le type de fichier est incorrect
            $errors["inputPicture"] = "Le type de fichier est incorrect (.jpg, .png ou .webp requis)";
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
        // Connexion à la base marmiton
        $dsn = "mysql:host=localhost;dbname=literie3000";
        $db = new PDO($dsn, "root", "");

        // La valeur attendue, pour les durées, est en seconde et non en minute


        $query = $db->prepare("INSERT INTO catalogue (name, MattressName, picture, MattressSize, price, promo) VALUES (:name, :MattressName, :picture, :MattressSize, :price, :promo)");
        $query->bindParam(":name", $name);
        $query->bindParam(":MattressName", $MattressName);
        $query->bindParam(":MattressSize", $MattressSize);
        $query->bindParam(":picture", $newFileName);
        $query->bindParam(":price", $price, PDO::PARAM_INT);
        $query->bindParam(":promo", $promo, PDO::PARAM_INT);

        if ($query->execute()) {
            // La requête s'est bien déroulée donc on redirige l'utilisateur vers la page d'accueil
            header("Location: index.php");
        }
    }
}

include("templates/header.php");
?>
<h1>Ajouter une recette</h1>
<!-- Lorsque l'attribut action est vide les données du formulaire sont envoyées à la même page -->
<!-- Un formulaire utilisant un champ de type file doit forcément avoir un attribut enctype avec la valeur multipart/form-data -->
<form action="" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="inputName">Nom du produit:</label>
        <input type="text" id="inputName" name="name" value="<?= isset($name) ? $name : "" ?>">
        <?php
        if (isset($errors["name"])) {
        ?>
            <span class="info-error"><?= $errors["name"] ?></span>
        <?php
        }
        ?>
    </div>

    <div class="form-group">
        <label for="inputPicture">Photo du produit :</label>
        <input type="file" id="inputPicture" name="inputPicture">
        <?php
        if (isset($errors["inputPicture"])) {
        ?>
            <span class="info-error"><?= $errors["inputPicture"] ?></span>
        <?php
        }
        ?>
    </div>

    <div class="form-group">
        <label for="inputMatName">Nom du matelas :</label>
        <input type="text" name="MattressName" id="inputMatName">
    </div>

    <div class="form-group">
        <label for="inputMatSize">Taille du Matelas</label>
        <select name="MattressSize" id="inputMatSize">
            <option <?= isset($MattressSize) && $MattressSize === "90x190" ? "selected" : "" ?> value="90x190">90x190</option>
            <option <?= isset($MattressSize) && $MattressSize === "140x190" ? "selected" : "" ?> value="140x190">140x190</option>
            <option <?= isset($MattressSize) && $MattressSize === "160x200" ? "selected" : "" ?> value="160x200">160x200</option>
        </select>

    </div>

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

    <input type="submit" value="Ajouter la recette" class="btn-marmiton">
</form>