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

// Vérifier si l'utilisateur existe
if (!$user) {
    // Redirection ou message d'erreur si l'utilisateur n'existe pas
    header('Location: dashboard.php');
    exit;
}

// Traitement de la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    // Traitement de l'avatar
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $image_tmp = $_FILES['avatar']['tmp_name'];
        $image_name = $_FILES['avatar']['name'];
        $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);
        $image_new_name = "avatar_" . $user_id . "." . $image_extension;
        $image_new_path = '../media/avatar/' . $image_new_name;
        
        move_uploaded_file($image_tmp, $image_new_path);

        // Mettre à jour le chemin de l'avatar dans la base de données
        $avatar_sql = "UPDATE user SET AVATAR = :avatar WHERE USER_ID = :user_id";
        $avatar_stmt = $con->prepare($avatar_sql);
        $avatar_stmt->bindParam(':avatar', $image_new_name);
        $avatar_stmt->bindParam(':user_id', $user_id);
        $avatar_stmt->execute();
    }

    // Mettre à jour les autres informations de l'utilisateur
    $update_sql = "UPDATE user SET FNAME = :fname, LNAME = :lname, EMAIL = :email, PHONE = :phone WHERE USER_ID = :user_id";
    $update_stmt = $con->prepare($update_sql);
    $update_stmt->bindParam(':fname', $fname);
    $update_stmt->bindParam(':lname', $lname);
    $update_stmt->bindParam(':email', $email);
    $update_stmt->bindParam(':phone', $phone);
    $update_stmt->bindParam(':user_id', $user_id);
    $update_stmt->execute();

    // Mettre à jour le mot de passe si les champs de mot de passe sont remplis et correspondent
    if (!empty($password) && $password === $confirm_password) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $password_sql = "UPDATE user SET PASSWORD_HASH = :password WHERE USER_ID = :user_id";
        $password_stmt = $con->prepare($password_sql);
        $password_stmt->bindParam(':password', $hashed_password);
        $password_stmt->bindParam(':user_id', $user_id);
        $password_stmt->execute();
    }

    // Rediriger vers la page de profil après la modification
    header("Location: profile.php?id=$user_id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../Assets/css/tailwind.css" rel="stylesheet">
    <title>Edit Profile</title>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto py-8">
        <div class="max-w-md mx-auto bg-white rounded-lg overflow-hidden shadow-lg">
            <div class="p-4">
                <h1 class="text-3xl font-bold text-center mb-4">Edit Profile</h1>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="avatar" class="block text-sm font-semibold mb-2">Avatar:</label>
                        <input type="file" id="avatar" name="avatar" accept="image/*" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="fname" class="block text-sm font-semibold mb-2">First Name:</label>
                        <input type="text" id="fname" name="fname" value="<?= htmlspecialchars($user['FNAME']) ?>" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                    </div>
                    <div class="mb-4">
                        <label for="lname" class="block text-sm font-semibold mb-2">Last Name:</label>
                        <input type="text" id="lname" name="lname" value="<?= htmlspecialchars($user['LNAME']) ?>" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-semibold mb-2">Email:</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['EMAIL']) ?>" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                    </div>
                    <div class="mb-4">
                        <label for="phone" class="block text-sm font-semibold mb-2">Phone:</label>
                        <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($user['PHONE']) ?>" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-semibold mb-2">New Password:</label>
                        <input type="password" id="password" name="password" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="confirm_password" class="block text-sm font-semibold mb-2">Confirm New Password:</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                    <div class="flex justify-center">
                        <a href="javascript:history.back()" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Annuler</a>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 ml-2">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
