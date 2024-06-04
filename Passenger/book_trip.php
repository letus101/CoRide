<?php
require_once '../config/cnx.php';
$con = cnx_pdo();

// Récupération de l'identifiant du voyage à réserver depuis l'URL
if (isset($_GET['trip_id'])) {
    $trip_id = $_GET['trip_id'];

    // Récupération des informations sur le voyage et le conducteur
    $sql = "SELECT trip.*, user.FNAME AS DRIVER_NAME, user.PHONE AS DRIVER_PHONE_NUMBER, user.USER_ID AS DRIVER_ID
            FROM trip 
            INNER JOIN user ON trip.USER_ID = user.USER_ID 
            WHERE trip.TRIP_ID = :trip_id";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':trip_id', $trip_id);
    $stmt->execute();
    $trip_info = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$trip_info) {
        // Redirection si l'identifiant du voyage est invalide
        header("Location: dashboard.php");
        exit;
    }
} else {
    // Redirection si l'identifiant du voyage n'est pas fourni dans l'URL
    header("Location: dashboard.php");
    exit;
}

// Vérification si l'utilisateur a déjà réservé ce voyage
$user_id = 1; // Exemple: ID de l'utilisateur (Remplacez par la méthode réelle pour obtenir l'ID de l'utilisateur)
$sql_check_reservation = "SELECT * FROM reservation WHERE TRIP_ID = :trip_id AND USER_ID = :user_id";
$stmt_check_reservation = $con->prepare($sql_check_reservation);
$stmt_check_reservation->bindParam(':trip_id', $trip_id);
$stmt_check_reservation->bindParam(':user_id', $user_id);
$stmt_check_reservation->execute();
$reservation_exists = $stmt_check_reservation->fetch(PDO::FETCH_ASSOC);

// Traitement du formulaire de réservation uniquement si l'utilisateur n'a pas déjà réservé ce voyage
if ($_SERVER["REQUEST_METHOD"] == "POST" && !$reservation_exists) {
    // Récupération de l'identifiant du voyage à réserver
    $trip_id = $_POST['trip_id'];

    // Insérer une nouvelle réservation dans la table de réservation
    $sql = "INSERT INTO reservation (TRIP_ID, DRIVER_ID, STATE_ID, USER_ID) VALUES (:trip_id, :driver_id, 1, :user_id)";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':trip_id', $trip_id);
    $stmt->bindParam(':driver_id', $trip_info['DRIVER_ID']);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    // Redirection vers la page de réservation une fois la réservation effectuée
    header("Location: reserve.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Trip</title>
    <link href="../Assets/css/tailwind.css" rel="stylesheet">
</head>
<body>
    <div class="flex h-screen bg-gray-100">
        <div class="hidden md:flex flex-col w-64 bg-gray-800">
            <div class="flex items-center justify-center h-16 bg-gray-900">
                <span class="text-white font-bold uppercase">Book Trip</span>
            </div>
          
            <div class="flex flex-col flex-1 overflow-y-auto">
                <nav class="flex-1 px-2 py-4 bg-gray-800">
                    <!-- Sidebar navigation -->
                </nav>
            </div>
        </div>

        <div class="flex flex-col flex-1 overflow-y-auto">
            <div class="flex items-center justify-between h-16 bg-white border-b border-gray-200">
                <div class="flex items-center px-4">
                    <a href="javascript:history.back()" class="text-gray-500 focus:outline-none focus:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        <span class="ml-2">Retour</span>
                    </a>
                </div>
                <div class="flex items-center pr-4">
                    <!-- Right side navigation -->
                </div>
            </div>
            <div class="p-4">
    <h1 class="text-2xl font-bold">Trip Details</h1>
    <div class="mt-8 bg-white rounded-lg shadow-md p-4 max-w-md mx-auto">
        <p><strong>From:</strong> <?= $trip_info['DEPARTURE_CITY'] ?>(<?= $trip_info['DEPARTURE_LOCATION'] ?>)</p>
        <p><strong>To:</strong> <?= $trip_info['ARRIVAL_'] ?>(<?= $trip_info['ARRIVAL_1'] ?>)</p>
        <p><strong>Date:</strong> <?= $trip_info['TRIP_START_DATE'] ?></p>
        <p><strong>Seats Available:</strong> <?= $trip_info['AVAILABLE_SEATS'] ?></p>
        <p><strong>Driver Name:</strong> <?= $trip_info['DRIVER_NAME'] ?></p>
        <p><strong>Driver Phone Number:</strong> <?= $trip_info['DRIVER_PHONE_NUMBER'] ?></p>
        <?php if (!$reservation_exists): ?>
        <form method="post" action="">
            <input type="hidden" name="trip_id" value="<?= $trip_id ?>">
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md mt-4 hover:bg-blue-600">Reserve</button>
        </form>
        <?php endif; ?>
    </div>
</div>

        </div>
    </div>
</body>
<script src="./node_modules/preline/dist/preline.js"></script>
</html>
