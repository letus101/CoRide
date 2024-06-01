<?php
require_once '../config/cnx.php';
$con = cnx_pdo();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération de l'identifiant de la réservation à annuler depuis le formulaire
    $reservation_id = $_POST['reservation_id'];

    // Suppression de la réservation de la base de données
    $sql = "DELETE FROM reservation WHERE RESERVATION_ID = :reservation_id";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':reservation_id', $reservation_id);
    $stmt->execute();

    // Redirection vers la page des réservations après l'annulation
    header("Location: reserve.php");
    exit;
} else {
    // Redirection si la méthode de requête n'est pas POST
    header("Location: reserve.php");
    exit;
}
?>
