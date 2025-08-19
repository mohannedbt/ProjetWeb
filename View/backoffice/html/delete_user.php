<?php
require_once "../../../config.php";
require_once "../../../Controller/UserController.php";

$config = new config();
$pdo = $config->getConnexion();
$userController = new UserController($pdo);

$id = $_GET['id'] ?? null;
if($id){
    $userController->delete($id);
}

header("Location: users.php");
exit;
?>
