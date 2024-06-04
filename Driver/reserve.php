<?php
require_once '../config/cnx.php';
$con = cnx_pdo();

// Récupérer l'ID de l'utilisateur depuis l'URL
$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$driver_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Récupérer les voyages réservés par l'utilisateur actuel
$sql = "SELECT trip.*, 
               driver.FNAME AS DRIVER_NAME, 
               driver.PHONE AS DRIVER_PHONE_NUMBER, 
               reservation.RESERVATION_ID,
               client.FNAME AS CLIENT_NAME,
               client.PHONE AS CLIENT_PHONE_NUMBER,
               state.STATE_NAME
        FROM reservation 
        INNER JOIN trip ON reservation.TRIP_ID = trip.TRIP_ID 
        INNER JOIN user AS driver ON trip.USER_ID = driver.USER_ID
        INNER JOIN user AS client ON reservation.USER_ID = client.USER_ID
        INNER JOIN state ON reservation.STATE_ID = state.STATE_ID
        WHERE reservation.DRIVER_ID = :driver_id";
$stmt = $con->prepare($sql);
$stmt->bindParam(':driver_id', $driver_id);
$stmt->execute();
$reserved_trips = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Traiter les actions d'accepter, refuser et supprimer
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reservation_id'])) {
    $reservation_id = (int)$_POST['reservation_id'];
    
    // Vérifier l'état actuel de la réservation
    $current_state_sql = "SELECT STATE_ID FROM reservation WHERE RESERVATION_ID = :reservation_id";
    $current_state_stmt = $con->prepare($current_state_sql);
    $current_state_stmt->bindParam(':reservation_id', $reservation_id);
    $current_state_stmt->execute();
    $current_state = $current_state_stmt->fetch(PDO::FETCH_ASSOC);

    if ($current_state) {
        $state_id = (int)$current_state['STATE_ID'];
        // Vérifier si l'état actuel est différent de celui que l'utilisateur souhaite appliquer
        if ($state_id != $_POST['state_id']) {
            // Si différent, procéder à la mise à jour de l'état et à l'incrémentation/décrémentation
            if (isset($_POST['state_id'])) {
                $new_state_id = (int)$_POST['state_id'];
                $update_sql = "UPDATE reservation SET STATE_ID = :state_id WHERE RESERVATION_ID = :reservation_id";
                $update_stmt = $con->prepare($update_sql);
                $update_stmt->bindParam(':state_id', $new_state_id);
                $update_stmt->bindParam(':reservation_id', $reservation_id);
                $update_stmt->execute();
                
                // Mettre à jour le nombre de sièges disponibles seulement si l'état a changé
                if ($new_state_id == 2 && $state_id != 2) {
                    // Décrémenter seulement si l'état précédent n'était pas déjà accepté
                    $update_seat_sql = "UPDATE trip SET AVAILABLE_SEATS = AVAILABLE_SEATS - 1 WHERE TRIP_ID IN (SELECT TRIP_ID FROM reservation WHERE RESERVATION_ID = :reservation_id)";
                    $update_seat_stmt = $con->prepare($update_seat_sql);
                    $update_seat_stmt->bindParam(':reservation_id', $reservation_id);
                    $update_seat_stmt->execute();
                } elseif ($new_state_id == 3 && $state_id != 3) {
                    // Incrémenter seulement si l'état précédent n'était pas déjà refusé
                    $update_seat_sql = "UPDATE trip SET AVAILABLE_SEATS = AVAILABLE_SEATS + 1 WHERE TRIP_ID IN (SELECT TRIP_ID FROM reservation WHERE RESERVATION_ID = :reservation_id)";
                    $update_seat_stmt = $con->prepare($update_seat_sql);
                    $update_seat_stmt->bindParam(':reservation_id', $reservation_id);
                    $update_seat_stmt->execute();
                }
            } elseif (isset($_POST['delete'])) {
                $delete_sql = "DELETE FROM reservation WHERE RESERVATION_ID = :reservation_id";
                $delete_stmt = $con->prepare($delete_sql);
                $delete_stmt->bindParam(':reservation_id', $reservation_id);
                $delete_stmt->execute();
            }
        }
    }

    // Rafraîchir la page pour refléter les modifications
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
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
                
                <span class="text-white font-bold uppercase">Reservations</span>
            </div>
            
            <div class="flex flex-col flex-1 overflow-y-auto">
                <nav class="flex-1 px-2 py-4 bg-gray-800">
                    <a href="dashboard.php?id=<?= $user_id ?>" class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700">Dashboard</a>
                    <a href="create_trip.php?id=<?= $user_id ?>" class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700">Ajouter un trip</a>
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
                <h1 class="text-2xl font-bold">Reservations</h1>
                <section class="my-8 sm:my-10 grid grid-cols-1 lg:grid-cols-2 gap-x-8 gap-y-4 p-6">
                    <?php if (empty($reserved_trips)): ?>
                        <p>No reservations found.</p>
                    <?php else: ?>
                        <?php foreach ($reserved_trips as $trip): ?>
                            <div class="flex flex-col justify-center">
                                <div class="flex flex-col h-full shadow justify-between rounded-lg pb-8 p-6 xl:p-8 mt-3 bg-gray-50">
                                    <div>
                                        <h4 class="font-bold text-2xl leading-tight"><?= $trip['DEPARTURE_CITY'] ?> to <?= $trip['ARRIVAL_'] ?></h4>
                                        <div class="my-4">
                                            <p>Date: <?= $trip['TRIP_START_DATE'] ?></p>
                                            <p>Seats Available: <?= $trip['AVAILABLE_SEATS'] ?></p>
                                            <p>Client Name: <?= $trip['CLIENT_NAME'] ?></p>
                                            <p>Client Phone: <?= $trip['CLIENT_PHONE_NUMBER'] ?></p>
                                            <p>State: <?= $trip['STATE_NAME'] ?></p>
                                        </div>
                                    </div>
                                    <div>
                                        <form method="post" action="">
                                            <input type="hidden" name="reservation_id" value="<?= $trip['RESERVATION_ID'] ?>">
                                            <button type="submit" name="state_id" value="3" class="mt-1 inline-flex font-bold items-center border-2 border-transparent outline-none focus:ring-1 focus:ring-offset-2 focus:ring-red-500 active:bg-red-500 active:text-white active:ring-0 active:ring-offset-0 leading-normal bg-red-500 text-white hover:bg-opacity-80 text-base rounded-lg py-1.5"  aria-label="Refuse">Refuser</button>
                                            <button type="submit" name="state_id" value="2" class="mt-1 inline-flex font-bold items-center border-2 border-transparent outline-none focus:ring-1 focus:ring-offset-2 focus:ring-red-500 active:bg-red-500 active:text-white active:ring-0 active:ring-offset-0 leading-normal bg-blue-500 text-white hover:bg-opacity-80 text-base rounded-lg py-1.5" aria-label="Accept">Accepter</button>
                    
                                            <button type="submit" name="delete" class="mt-1 inline-flex font-bold items-center border-2 border-transparent outline-none focus:ring-1 focus:ring-offset-2 focus:ring-red-500 active:bg-red-500 active:text-white active:ring-0 active:ring-offset-0 leading-normal bg-red-500 text-white hover:bg-opacity-80 text-base rounded-lg py-1.5" aria-label="Delete">Supprimer</button>
                                        </form>
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

