<?php
$db = new PDO('mysql:host=localhost;dbname=literie3000;charset=UTF8', 'root', '');

$find = false;
$data = array("name" => "Produit introuvable");
if (isset($_GET["id"])) {
    $id = $_GET['id'];
    $query = $db->prepare('SELECT * from catalogue where id = :id');
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $bed = $query->fetch(); // Retourne un tableau ou false

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

            <form action="">
                <label for="inputImage"> Image (sous format image.type)</label>
                <input type="text" name="image" id="inputImage" value="<?= $data["image"] ?>">

                <label for="inputName">Nom du lit</label>
                <input type="text" name="name" id="inputName" value="<?= $data["name"] ?>">

                <label for="inputMatName">Nom du Matelas</label>
                <input type="text" name="MattressName" id="inputMatName" value="<?= $data["MattressName"] ?>">

                <label for="inputMatSize">Taille du Matelas</label>
                <select name="MattressSize" id="inputMatSize">
                    <option value="option1">90x190 cm</option>
                    <option value="option2">140x190 cm</option>
                    <option value="option3">160x200 cm</option>
                </select>

                <label for="inputPrice">Prix (en euro)</label>
                <input type="number" name="price" id="inputPrice" value="<?= $data["price"] ?>">
                <label for="inputPromo">Prix apres promotion(en euro)</label>
                <input type="number" name="promo" id="inputPromo" value="<?= $data["promo"] ?>">
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