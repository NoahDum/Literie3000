<?php
if (!empty($_POST)) {
    $name = trim(strip_tags($_POST["name"]));
    $MattressName = trim(strip_tags($_POST["MattressName"]));
    $MattressSize = trim(strip_tags($_POST["MattressSize"]));
    $price = trim(strip_tags($_POST["price"]));
    $promo = trim(strip_tags($_POST["promo"]));
    $errors = [];


    if (empty($name)) {
        $errors["name"] = "Le nom du produit est obligatoire";
    }

    if (empty($MattressName)) {
        $errors["MattressName"] = "Le nom du matelas est obligatoire";
    }


    if (isset($_FILES["inputPicture"]) && $_FILES["inputPicture"]["error"] === UPLOAD_ERR_OK) {

        $fileTmpPath = $_FILES["inputPicture"]["tmp_name"];
        $fileName = $_FILES["inputPicture"]["name"];
        $fileType = $_FILES["inputPicture"]["type"];


        $fileNameArray = explode(".", $fileName);

        $fileExtension = end($fileNameArray);

        $newFileName = md5($fileName . time()) . "." . $fileExtension;

        $fileDestPath = "./img/{$newFileName}";

        $allowedTypes = array("image/jpeg", "image/png", "image/webp");
        if (in_array($fileType, $allowedTypes)) {

            move_uploaded_file($fileTmpPath, $fileDestPath);
        } else {

            $errors["inputPicture"] = "Le type de fichier est incorrect (.jpg, .png ou .webp requis)";
        }
    }
    if ($price < 0) {
        $errors["price"] = "Le prix ne peut être inférieur à 0";
    }

    if ($promo < 0 ) {
        $errors["promo"] = "La promotion ne peut être inférieur à 0 ";
    }else if ($promo > $price){
        $errors["promo"] = "La promotion ne peut être suppérieur au prix ";
    }
    
    if (empty($errors)) {

        $dsn = "mysql:host=localhost;dbname=literie3000";
        $db = new PDO($dsn, "root", "");


        $query = $db->prepare("INSERT INTO catalogue (name, MattressName, picture, MattressSize, price, promo) VALUES (:name, :MattressName, :picture, :MattressSize, :price, :promo)");
        $query->bindParam(":name", $name);
        $query->bindParam(":MattressName", $MattressName);
        $query->bindParam(":MattressSize", $MattressSize);
        $query->bindParam(":picture", $newFileName);
        $query->bindParam(":price", $price, PDO::PARAM_INT);
        $query->bindParam(":promo", $promo, PDO::PARAM_INT);

        if ($query->execute()) {

            header("Location: index.php");
        }
    }
}

include("templates/header.php");
?>
<h1>Ajouter un produit</h1>
<div class="container">
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
        <?php
        if (isset($errors["MattressName"])) {
        ?>
            <span class="info-error"><?= $errors["name"] ?></span>
        <?php
        }
        ?>
    </div>

    <div class="form-group">
        <label for="inputMatSize">Taille du Matelas</label>
        <select name="MattressSize" id="inputMatSize">
            <option <?= isset($MattressSize) && $MattressSize === "90x190" ? "selected" : "" ?> value="90x190">90x190</option>
            <option <?= isset($MattressSize) && $MattressSize === "140x190" ? "selected" : "" ?> value="140x190">140x190</option>
            <option <?= isset($MattressSize) && $MattressSize === "160x200" ? "selected" : "" ?> value="160x200">160x200</option>
        </select>

    </div>

    <div class="form-group">
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

    <div class="form-group">
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

    <input type="submit" value="Ajouter le produit" class="submit">
</form>
</div>
</main>
</body>

</html>