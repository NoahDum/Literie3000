<?php
$db = new PDO('mysql:host=localhost;dbname=literie3000;charset=UTF8', 'root', '');

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $query = $db->prepare("DELETE FROM catalogue WHERE id = :id");
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
}

header("Location: index.php");
exit();
?>
