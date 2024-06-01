<?php
require_once '../config/cnx.php';
$con = cnx_pdo();

// Vérifie si l'ID du voyage à supprimer est présent dans la requête POST
if(isset($_POST['trip_id'])) {
    // Récupère l'ID du voyage à supprimer depuis la requête POST
    $trip_id = $_POST['trip_id'];

    // Requête pour supprimer le voyage de la base de données
    $sql = "DELETE FROM trip WHERE TRIP_ID = :trip_id";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':trip_id', $trip_id);
    $stmt->execute();
}

// Récupère l'ID de l'utilisateur actuel depuis l'URL
$user_id = isset($_GET['id']) ? $_GET['id'] : '';

// Redirige toujours vers dashboard.php avec l'ID de l'utilisateur actuel
header("Location: dashboard.php?id=" . $user_id);
exit; // Termine le script
?>
