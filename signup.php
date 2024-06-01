<?php
require_once './config/cnx.php';
$con = cnx_pdo();
$req_roles = $con->prepare("SELECT * FROM role");
$req_roles->execute();
$roles = $req_roles->fetchAll();
if (isset($_POST['createaccount']) && !empty($_POST['firstName']) && !empty($_POST['lastName']) && !empty($_POST['email']) && !empty($_POST['phone']) && !empty($_POST['role']) && !empty($_POST['password']) && !empty($_POST['confirmPassword']) && !empty($_FILES['image'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $image = $_FILES['image'];
    $image_name = $image['name'];
    $image_tmp = $image['tmp_name'];
    $image_size = $image['size'];
    $image_error = $image['error'];
    $image_type = $image['type'];
    $image_ext = explode('.', $image_name);
    $image_actual_ext = strtolower(end($image_ext));
    $allowed = array('jpg', 'jpeg', 'png');
    if (in_array($image_actual_ext, $allowed)) {
        if ($image_error === 0) {
            if ($password === $confirmPassword) {
                $req = $con->prepare("SELECT * FROM user WHERE email = :email");
                $req->bindValue(':email', $email);
                $req->execute();
                $user = $req->fetch();
                if (!$user) {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $req = $con->prepare("INSERT INTO user (FNAME, LNAME, EMAIL, PHONE, ROLE_ID, PASSWORD_HASH, AVATAR) VALUES (:firstName, :lastName, :email, :phone, :role, :password, :image)");
                    $req->bindValue(':firstName', $firstName);
                    $req->bindValue(':lastName', $lastName);
                    $req->bindValue(':email', $email);
                    $req->bindValue(':phone', $phone);
                    $req->bindValue(':role', $role);
                    $req->bindValue(':password', $hashed_password);
                    $req->bindValue(':image', $image_name);
                    $req->execute();
                    move_uploaded_file($image_tmp, 'media/avatar/' . $image_name);
                    echo "<script>alert('Account created successfullyYou can now login')</script>";
                    header('Location: login.php');
                } else {
                    echo "<script>alert('Email already exists')</script>";
                }
            } else {
                echo "<script>alert('Passwords do not match')</script>";
            }
        } else {
            echo "<script>alert('There was an error uploading your image')</script>";
        }
    } else {
        echo "<script>alert('You cannot upload files of this type')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGNUP</title>
    <link href="Assets/css/tailwind.css" rel="stylesheet">
</head>
<body class="dark:bg-slate-900 bg-gray-100 flex h-full items-center py-16">
<form method="post" action="<?= htmlentities($_SERVER['PHP_SELF']) ?>" enctype="multipart/form-data">
    <div class="grid grid-cols-1 gap-6 p-4">
        <div class="grid grid-cols-1 gap-6">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label for="firstName" class="block text-sm mb-2 dark:text-white">First Name :</label>
                    <input type="text" name="firstName" id="firstName" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600" placeholder="First Name" required>
                </div>
                <div>
                    <label for="lastName" class="block text-sm mb-2 dark:text-white">Last Name :</label>
                    <input type="text" name="lastName" id="lastName" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600" placeholder="Last Name" required>
                </div>
            </div>
            <div>
                <label for="email" class="block text-sm mb-2 dark:text-white">Email</label>
                <input type="email" name="email" id="email" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600" placeholder="email" required>
            </div>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label for="phone" class="block text-sm mb-2 dark:text-white">Phone :</label>
                    <input type="tel" name="phone" id="phone" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600" placeholder="Phone" required>
                </div>
                <div>
                    <label for="role" class="block text-sm mb-2 dark:text-white">Role</label>
                    <select name="role" id="role" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600" required>
                        <option value="" disabled selected>Select role</option>
                        <?php foreach ($roles as $r): ?>
                            <option value="<?= $r['ROLE_ID'] ?>"><?= $r['ROLE_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div>
                <label for="password" class="block text-sm mb-2 dark:text-white">Password</label>
                <input type="password" name="password" id="password" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"  required>
            </div>
            <div>
                <label for="confirmPassword" class="block text-sm mb-2 dark:text-white">Confirm Password</label>
                <input type="password" name="confirmPassword" id="confirmPassword" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600" required>
            </div>
            <div>
                <label for="image" class="block text-sm mb-2 dark:text-white">Upload Image</label>
                <input type="file" name="image" id="image" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600" required>
            </div>
            <div class="flex justify-end space-x-4">
                    <p class="text-sm font-light text-gray-500 dark:text-gray-400">Already have an account? <a class="font-medium text-blue-600 hover:underline dark:text-blue-500" href="login.php">login here</a></p>
                    <button onclick="return(confirmAddUser())" type="submit" name="createaccount" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600 dark:focus:ring-1 dark:focus:ring-gray-600">
                        Add user
                    </button>
                </div>
        </div>
    </div>
</form>
<script src="./node_modules/preline/dist/preline.js"></script>
<script>
    function confirmAddUser() {
        return confirm('Are you sure you want to create this account');
    }
</script>
</body>
</html>