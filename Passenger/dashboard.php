<?php
require_once '../config/cnx.php';
$con = cnx_pdo();
if(isset($_GET['id'])) {
    // Récupère l'ID de l'utilisateur depuis l'URL
    $user_id = $_GET['id'];
} 

$sql = "SELECT * FROM trip WHERE USER_ID = :user_id";
$stmt = $con->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$published_trips = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Traitement du formulaire de filtrage
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $departure_city = $_POST['departure_city'] ?? '';
    $arrival_city = $_POST['arrival_city'] ?? '';
    $date = $_POST['date'] ?? '';

    $sql = "SELECT * FROM trip WHERE AVAILABLE_SEATS > 0";

    if (!empty($departure_city)) {
        $sql .= " AND DEPARTURE_CITY = :departure_city";
    }
    if (!empty($arrival_city)) {
        $sql .= " AND ARRIVAL_ = :arrival_city";
    }
    if (!empty($date)) {
        $sql .= " AND TRIP_START_DATE = :date";
    }

    $stmt = $con->prepare($sql);
    if (!empty($departure_city)) {
        $stmt->bindParam(':departure_city', $departure_city);
    }
    if (!empty($arrival_city)) {
        $stmt->bindParam(':arrival_city', $arrival_city);
    }
    if (!empty($date)) {
        $stmt->bindParam(':date', $date);
    }

    $stmt->execute();
    $trips = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Requête pour récupérer tous les voyages disponibles
    $sql = "SELECT * FROM trip WHERE AVAILABLE_SEATS > 0"; 
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $trips = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../Assets/css/tailwind.css" rel="stylesheet">
    <title>Dashboard</title>
</head>
<body>
    <div class="flex h-screen bg-gray-100">
        <!-- sidebar -->
        <div class="hidden md:flex flex-col w-64 bg-gray-800">
            
            <div class="flex items-center justify-center h-16 bg-gray-900">
            <a href="profile.php?id=<?= $user_id ?>" class="text-white font-bold uppercase">
                    <script src="https://cdn.lordicon.com/lordicon.js"></script>
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
                        
                        Trips reserved
                    </a>
                </nav>
            </div>
            
        </div>

        <div class="flex flex-col flex-1 overflow-y-auto">
            <div class="flex items-center justify-between h-16 bg-white border-b border-gray-200">
                <div class="flex items-center px-4">
                    <button class="text-gray-500 focus:outline-none focus:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <form method="post" class="mx-4">
                        <input class="w-28 border rounded-md px-4 py-2" type="text" name="departure_city" placeholder="Departure City">
                        <input class="w-28 border rounded-md px-4 py-2" type="text" name="arrival_city" placeholder="Arrival City">
                        <input class="w-28 border rounded-md px-4 py-2" type="date" name="date" placeholder="Date">
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-blue rounded-md ml-2">Filter</button>
                    </form>
                </div>
                <div class="flex items-center pr-4">
                    <button class="flex items-center text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l-7-7 7-7m5 14l7-7-7-7"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="p-4">
                <h1 class="text-2xl font-bold">List of Available Trips</h1>
                <section class="my-8 sm:my-10 grid grid-cols-1 lg:grid-cols-2 gap-x-8 gap-y-4 p-6">
                    <?php foreach ($trips as $trip): ?>
                        <div class="flex flex-col justify-center">
                            <div class="flex flex-col h-full shadow justify-between rounded-lg pb-8 p-6 xl:p-8 mt-3 bg-gray-50">
                                <div>
                                    <h4 class=" font-bold text-2xl leading-tight"><?= $trip['DEPARTURE_CITY'] ?> to <?= $trip['ARRIVAL_'] ?></h4>
                                    <div class="my-4">
                                        <p>Date: <?= $trip['TRIP_START_DATE'] ?></p>
                                        <p>Seats Available: <?= $trip['AVAILABLE_SEATS'] ?></p>
                                    </div>
                                </div>
                                <div>
                                    <a class="mt-1 inline-flex font-bold items-center border-2 border-transparent outline-none focus:ring-1 focus:ring-offset-2 focus:ring-link active:bg-link active:text-gray-700 active:ring-0 active:ring-offset-0 leading-normal bg-link text-gray-700 hover:bg-opacity-80 text-base rounded-lg py-1.5"aria-label="Book Now" target="_self" href="book_trip.php?trip_id=<?= $trip['TRIP_ID'] ?>">S'avoir plus<svg
                                            xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                            class="duration-100 ease-in transition -rotate-90 inline ml-1"
                                            style="min-width:20px;min-height:20px">
                                            <g fill="none" fill-rule="evenodd" transform="translate(-446 -398)">
                                                <path fill="currentColor" fill-rule="nonzero"
                                                    d="M95.8838835,240.366117 C95.3957281,239.877961 94.6042719,                                                    239.877961 94.1161165,240.366117 C93.6279612,240.854272 93.6279612,241.645728 94.1161165,242.133883 L98.6161165,246.633883 C99.1042719,247.122039 99.8957281,247.122039 100.383883,246.633883 L104.883883,242.133883 C105.372039,241.645728 105.372039,240.854272 104.883883,240.366117 C104.395728,239.877961 103.604272,239.877961 103.116117,240.366117 L99.5,243.982233 L95.8838835,240.366117 Z"
                                                    transform="translate(356.5 164.5)"></path>
                                                <polygon points="446 418 466 418 466 398 446 398"></polygon>
                                            </g>
                                        </svg>
                                    </a>
                                    
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </section>
            </div>
        </div>
    </div>
</body>

<script src="./node_modules/preline/dist/preline.js"></script>
</html>

