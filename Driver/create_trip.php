<?php
require_once '../config/cnx.php';
$con = cnx_pdo();
session_start();

if(isset($_GET['id'])) {
    // Récupère l'ID de l'utilisateur depuis l'URL
    $user_id = $_GET['id'];
} else {
    // Utilisation de l'ID de session si pas d'ID dans l'URL
    $user_id = $_SESSION['id'];
}

$message = ""; // Variable pour stocker le message de succès ou d'erreur

if (isset($_POST['submit'])) {
    $departure_city = $_POST['departure_city'];
    $departure_location = $_POST['departure_location'];
    $arrival_city = $_POST['arrival_city'];
    $arrival_location = $_POST['arrival_location'];
    $departure_time = $_POST['departure_time'];
    $trip_start_date = $_POST['trip_start_date'];
    $available_seats = $_POST['available_seats'];
    $price_per_passenger = $_POST['price_per_passenger'];
    $description = $_POST['description'];

    // Validate input
    if (empty($departure_city) || empty($departure_location) || empty($arrival_city) || empty($arrival_location) || empty($departure_time) || empty($trip_start_date) || empty($available_seats) || empty($price_per_passenger) || empty($description)) {
        $message = "All fields are required.";
    } elseif (!is_numeric($available_seats) || !is_numeric($price_per_passenger)) {
        $message = "Available seats and price per passenger must be numeric.";
    } else {
        $sql = "INSERT INTO trip (USER_ID, DEPARTURE_CITY, DEPARTURE_LOCATION, ARRIVAL_, ARRIVAL_1, DEPARTURE_TIME, TRIP_START_DATE, AVAILABLE_SEATS, PRICE_PER_PASSENGER, DESCRIPTION) VALUES (:user_id, :departure_city, :departure_location, :arrival_city, :arrival_location, :departure_time, :trip_start_date, :available_seats, :price_per_passenger, :description)";

        $stmt = $con->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':departure_city', $departure_city);
        $stmt->bindParam(':departure_location', $departure_location);
        $stmt->bindParam(':arrival_city', $arrival_city);
        $stmt->bindParam(':arrival_location', $arrival_location);
        $stmt->bindParam(':departure_time', $departure_time);
        $stmt->bindParam(':trip_start_date', $trip_start_date);
        $stmt->bindParam(':available_seats', $available_seats);
        $stmt->bindParam(':price_per_passenger', $price_per_passenger);
        $stmt->bindParam(':description', $description);

        if ($stmt->execute()) {
            // Redirection vers dashboard.php après l'insertion réussie
            header("Location: dashboard.php?id=" . htmlentities($user_id));
            exit(); // Assurez-vous de terminer le script après la redirection
        } else {
            $message = "Error creating trip";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Trip</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto py-8">
        <form class="max-w-lg mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" method="post" action="<?= htmlentities($_SERVER['PHP_SELF']) . '?id=' . htmlentities($user_id) ?>">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="departure_city">Departure City:</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="departure_city" type="text" name="departure_city" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="departure_location">Departure Location:</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="departure_location" type="text" name="departure_location" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="arrival_city">Arrival City:</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="arrival_city" type="text" name="arrival_city" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="arrival_location">Arrival Location:</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="arrival_location" type="text" name="arrival_location" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="departure_time">Departure Time:</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="departure_time" type="time" name="departure_time" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="trip_start_date">Trip Start Date:</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="trip_start_date" type="date" name="trip_start_date" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="available_seats">Available Seats:</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="available_seats" type="number" name="available_seats" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="price_per_passenger">Price Per Passenger:</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="price_per_passenger" type="text" name="price_per_passenger" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="description">Description:</label><br>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="description" name="description" rows="4" cols="50" required></textarea>
            </div>
            <div class="flex items-center justify-between">
                <input class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit" name="submit" value="Submit">
                <a href="javascript:history.back()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Annuler</a>
            </div>
        </form>
        <!-- Afficher le message de succès ou d'erreur -->
        <p class="text-center text-red-500 font-bold"><?= htmlentities($message) ?></p>
    </div>
</body>

</html>
