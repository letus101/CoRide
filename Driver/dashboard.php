<?php
require_once '../config/cnx.php';
$con = cnx_pdo();

// Vérifie si l'ID de l'utilisateur est présent dans l'URL
if(isset($_GET['id'])) {
    // Récupère l'ID de l'utilisateur depuis l'URL
    $user_id = $_GET['id'];
} 

$sql = "SELECT * FROM trip WHERE USER_ID = :user_id";
$stmt = $con->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$published_trips = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../Assets/css/tailwind.css" rel="stylesheet">
    <title>My Trips</title>
</head>
<body>
    <div class="flex h-screen bg-gray-100">
        <div class="hidden md:flex flex-col w-64 bg-gray-800">
            <div class="flex items-center justify-center h-16 bg-gray-900">
            <script src="https://cdn.lordicon.com/lordicon.js"></script>
           
            <a href="profile.php?id=<?= $user_id ?>">
    <lord-icon
        src="https://cdn.lordicon.com/kthelypq.json"
        trigger="hover"
        colors="primary:#ffffff"
        style="width:50px;height:50px">
    </lord-icon>
</a>

                <span class="text-white font-bold uppercase">Dashboard</span>
            </div>
            <div class="flex flex-col flex-1 overflow-y-auto">
                <nav class="flex-1 px-2 py-4 bg-gray-800">
                <a href="reserve.php?id=<?= $user_id ?>" class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700">                
                        Reservations
                    </a>
                    <a href="create_trip.php?id=<?= $user_id ?>" class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700">                
                        Ajoutet trip
                    </a>
                   
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
                <h1 class="text-2xl font-bold">My Trips</h1>
                <section class="my-8 sm:my-10 grid grid-cols-1 lg:grid-cols-2 gap-x-8 gap-y-4 p-6">
                    <?php if (empty($published_trips)): ?>
                        <p>No trips found.</p>
                    <?php else: ?>
                        <?php foreach ($published_trips as $trip): ?>
                            <div class="flex flex-col justify-center">
                                <div class="flex flex-col h-full shadow justify-between rounded-lg pb-8 p-6 xl:p-8 mt-3 bg-gray-50">
                                    <div>
                                        <h4 class="font-bold text-2xl leading-tight"><?= $trip['DEPARTURE_CITY'] ?> to <?= $trip['ARRIVAL_'] ?></h4>
                                        <div class="my-4">
                                            <p>Date: <?= $trip['TRIP_START_DATE'] ?></p>
                                            <p>Seats Available: <?= $trip['AVAILABLE_SEATS'] ?></p>
                                        </div>
                                    </div>
                                    <div>
                                        <!-- Bouton pour supprimer le voyage -->
                                        <form method="post" action="delete_trip.php?id=<?= $user_id ?>">
                                            <input type="hidden" name="trip_id" value="<?= $trip['TRIP_ID'] ?>">
                                            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md ml-2 hover:bg-red-600">Supprimer</button>
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
