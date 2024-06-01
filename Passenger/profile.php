<?php
require_once '../config/cnx.php';
$con = cnx_pdo();

// Récupérer l'ID de l'utilisateur depuis l'URL
$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Récupérer les informations de l'utilisateur
$sql = "SELECT * FROM user WHERE USER_ID = :user_id";
$stmt = $con->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Chemin de l'avatar
$avatar_path = '../media/avatar/' . $user['AVATAR'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../Assets/css/tailwind.css" rel="stylesheet">
    <title>Profile</title>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto py-8">
        <div class="max-w-md mx-auto bg-white rounded-lg overflow-hidden shadow-lg">
            <div class="p-4">
                <!-- Profile content here -->
                <div class="flex justify-center">
                    <div class="flex-shrink-0">
                        <img class="h-24 w-24 rounded-full" src="<?= $avatar_path ?>" alt="User Avatar">
                    </div>
                </div>
                <p class="text-center text-3xl font-bold mt-4"><?= $user['FNAME'] ?> <?= $user['LNAME'] ?></p>
                <div class="border-b border-gray-300 mt-4"></div>
                <p class="text-lg font-semibold mt-4">User Information</p>
                <p class="mb-2"><span class="font-semibold">First Name:</span> <?= $user['FNAME'] ?></p>
                <p class="mb-2"><span class="font-semibold">Last Name:</span> <?= $user['LNAME'] ?></p>
                <p class="mb-2"><span class="font-semibold">Email:</span> <?= $user['EMAIL'] ?></p>
                <p class="mb-2"><span class="font-semibold">Phone:</span> <?= $user['PHONE'] ?></p>
                <!-- Add more information here -->
                <div class="flex justify-center mt-6">
                    <a href="dashboard.php?id=<?= $user_id ?>"class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mr-4">Annuler</a>
                    <a href="edit_profile.php?id=<?= $user_id ?>" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mr-4">Modifier Profile</a>
                    <a href="../logout.php" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">Déconnexion</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
