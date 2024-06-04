<?php
require_once '../config/cnx.php';
$con = cnx_pdo();

// Vérifie si l'ID de l'utilisateur est présent dans l'URL
if (isset($_GET['id'])) {
    // Récupère l'ID de l'utilisateur depuis l'URL
    $user_id = $_GET['id'];
}
$sql = "SELECT * FROM trip WHERE USER_ID = :user_id";
$stmt = $con->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$published_trips = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Pour cet exemple, nous supposons que l'ID de l'utilisateur actuel est stocké dans une variable fictive $user_id.
// Remplacez cela par la méthode réelle que vous utilisez pour récupérer l'ID de l'utilisateur.
$user_id = 1; // Exemple: ID de l'utilisateur

// Récupérer les voyages réservés par l'utilisateur actuel
$sql = "SELECT trip.*, user.FNAME AS DRIVER_NAME, user.PHONE AS DRIVER_PHONE_NUMBER, reservation.RESERVATION_ID, state.STATE_NAME
        FROM reservation 
        INNER JOIN trip ON reservation.TRIP_ID = trip.TRIP_ID 
        INNER JOIN user ON trip.USER_ID = user.USER_ID
        INNER JOIN state ON reservation.STATE_ID = state.STATE_ID
        WHERE reservation.USER_ID = :user_id";
$stmt = $con->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$reserved_trips = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Si le formulaire d'annulation de réservation est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cancel_reservation'])) {
    $reservation_id = $_POST['reservation_id'];

    // Récupérer les informations sur la réservation
    $sql_reservation = "SELECT * FROM reservation WHERE RESERVATION_ID = :reservation_id";
    $stmt_reservation = $con->prepare($sql_reservation);
    $stmt_reservation->bindParam(':reservation_id', $reservation_id);
    $stmt_reservation->execute();
    $reservation_info = $stmt_reservation->fetch(PDO::FETCH_ASSOC);

    // Vérifier si le nombre de sièges disponibles est égal à zéro
    if ($reservation_info['AVAILABLE_SEATS'] == 0) {
        // Mettre à jour le nombre de sièges disponibles dans la table trip
        $sql_update_seats = "UPDATE trip SET AVAILABLE_SEATS = AVAILABLE_SEATS + 1 WHERE TRIP_ID = :trip_id";
        $stmt_update_seats = $con->prepare($sql_update_seats);
        $stmt_update_seats->bindParam(':trip_id', $reservation_info['TRIP_ID']);
        $stmt_update_seats->execute();
    }

    // Supprimer la réservation de la table reservation
    $sql_cancel_reservation = "DELETE FROM reservation WHERE RESERVATION_ID = :reservation_id";
    $stmt_cancel_reservation = $con->prepare($sql_cancel_reservation);
    $stmt_cancel_reservation->bindParam(':reservation_id', $reservation_id);
    $stmt_cancel_reservation->execute();

    // Rediriger vers la page actuelle pour actualiser les données
    header("Location: {$_SERVER['PHP_SELF']}?id=$user_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../Assets/css/tailwind.css" rel="stylesheet">
    <title>My Reservations</title>
</head>
<body>
<div class="flex h-screen bg-gray-100">
    <div class="hidden md:flex flex-col w-64 bg-gray-800">
        <div class="flex items-center justify-center h-16 bg-gray-900">
            <span class="text-white font-bold uppercase">My Reservations</span>
        </div>
        <a href="dashboard.php?id=<?= $user_id ?>" class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700">
            Dashboard
        </a>
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
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 19l-7-7 7-7"/>
                    </svg>
                    <span class="ml-2">Retour</span>
                </a>
            </div>
            <div class="flex items-center pr-4">
                <!-- Right side navigation -->
            </div>
        </div>
        <div class="p-4">
            <h1 class="text-2xl font-bold">My Reservations</h1>
            <section class="my-8 sm:my-10 grid grid-cols-1 lg:grid-cols-2 gap-x-8 gap-y-4 p-6">
                <?php if (empty($reserved_trips)): ?>
                    <p>No reservations found.</p>
                <?php else: ?>
                    <?php foreach ($reserved_trips as $trip): ?>
                        <div class="flex flex-col justify-center">
                            <div class="flex flex-col h-full shadow justify-between rounded-lg pb-8 p-6 xl:p-8 mt-3 bg-gray-50">
                                <div>
                                    <h4 class="font-bold text-2xl leading-tight"><?= htmlspecialchars($trip['DEPARTURE_CITY']) ?> (<?= htmlspecialchars($trip['DEPARTURE_LOCATION']) ?>) to <?= htmlspecialchars($trip['ARRIVAL_']) ?> (<?= htmlspecialchars($trip['ARRIVAL_1']) ?>)</h4>
                                    <div class="my-4">
                                        <p>Date: <?= $trip['TRIP_START_DATE'] ?></p>
                                        <p>Seats Available: <?= $trip['AVAILABLE_SEATS'] ?></p>
                                        <p>Status: <?= $trip['STATE_NAME'] ?></p>
                                    </div>
                                </div>
                                <div class="flex justify-between">
                                    <a class="mt-1 inline-flex font-bold items-center border-2 border-transparent outline-none focus:ring-1 focus:ring-offset-2 focus:ring-link active:bg-link active:text-gray-700 active:ring-0 active:ring-offset-0 leading-normal bg-link text-gray-700 hover:bg-opacity-80 text-base rounded-lg py-1.5" aria-label="Book Now                                    target="_self" href="book_trip.php?trip_id=<?= $trip['TRIP_ID'] ?>">Learn More<svg
                                            xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                            class="duration-100 ease-in transition -rotate-90 inline ml-1"
                                            style="min-width:20px;min-height:20px">
                                            <g fill="none" fill-rule="evenodd" transform="translate(-446 -398)">
                                                <path fill="currentColor" fill-rule="nonzero"
                                                    d="M95.8838835,240.366117 C95.3957281,239.877961 94.6042719,239.877961 94.1161165,240.366117 C93.6279612,240.854272 93.6279612,241.645728 94.1161165,242.133883 L98.6161165,246.633883 C99.1042719,247.122039 99.8957281,247.122039 100.383883,246.633883 L104.883883,242.133883 C105.372039,241.645728 105.372039,240.854272 104.883883,240.366117 C104.395728,239.877961 103.604272,239.877961 103.116117,240.366117 L99.5,243.982233 L95.8838835,240.366117 Z"
                                                    transform="translate(356.5 164.5)"></path>
                                                <polygon points="446 418 466 418 466 398 446 398"></polygon>
                                            </g>
                                        </svg>
                                    </a>
                                    <div class="ml-4">
                                        <form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
                                            <input type="hidden" name="reservation_id" value="<?= $trip['RESERVATION_ID'] ?>">
                                            <button type="submit" name="cancel_reservation" class="mt-1 inline-flex font-bold items-center border-2 border-transparent outline-none focus:ring-1 focus:ring-offset-2 focus:ring-link active:bg-link active:text-gray-700 active:ring-0 active:ring-offset-0 leading-normal bg-link text-red-700 hover:bg-opacity-80 text-base rounded-lg py-1.5" aria-label="Cancel Reservation">Cancel reservation</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </section>
        </div>
    </div>
</div>
</body>
<script src="./node_modules/preline/dist/preline.js"></script>
</html>

