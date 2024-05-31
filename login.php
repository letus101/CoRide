<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN</title>
    <link href="Assets/css/tailwind.css" rel="stylesheet">
</head>
<body class="dark:bg-slate-900 bg-gray-100 flex h-full items-center py-16">
<?php
require_once './config/cnx.php';
$con = cnx_pdo();
if (isset($_POST['signin']) && !empty($_POST['email']) && !empty($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $req = $con->prepare("SELECT * FROM user WHERE email = :email");
    $req->bindValue(':email', $email);
    $req->execute();
    $user = $req->fetch();
    if ($user) {
        if (password_verify($password, $user['PASSWORD_HASH'])) {
            $req_role = $con->prepare("SELECT * FROM role");
            $req_role->execute();
            $role = $req_role->fetchAll();
            session_start();
            foreach ($role as $r) {
                if ($r['ROLE_ID'] == $user['ROLE_ID']) {
                    $_SESSION['email'] = $user['EMAIL'];
                    $_SESSION['id']= $user['USER_ID'];
                    $_SESSION['role'] = $r['ROLE_NAME'];
                    $_SESSION['role_id'] = $r['ROLE_ID'];
                    if ($r['ROLE_NAME'] == 'Driver'){
                        $req_trip = $con->prepare("SELECT * FROM trip WHERE USER_ID = :id");
                        $req_trip->bindValue(':id', $user['USER_ID']);
                        $req_trip->execute();
                        $trip = $req_trip->fetch();
                        if ($trip){
                            header('Location: ./Driver/dashboard.php');
                        }else{
                            header('Location: ./Driver/create_trip.php');
                        }
                    }
                    if ($r['ROLE_NAME'] == 'Passenger'){
                        header('Location: ./Passenger/dashboard.php');
                    }
                }
            }
        } else {
            echo "<script>alert('Wrong password')</script>";
        }
    } else {
        echo "<script>alert('Wrong Email ')</script>";
    }
}
?>
<main class="w-full max-w-md mx-auto p-6">
    <div class="mt-7 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <div class="p-4 sm:p-7">
            <div class="text-center">
                <h1 class="block text-2xl font-bold text-gray-800 dark:text-white">Sign in</h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Don't have an account yet?
                    <a class="text-blue-600 decoration-2 hover:underline font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="signup.php">
                        Create an account
                    </a>
                </p>
            </div>
            <div class="mt-5">
                <form method="post" action="<?= htmlentities($_SERVER['PHP_SELF']) ?>">
                    <div class="grid gap-y-4">
                        <div>
                            <label for="email" class="block text-sm mb-2 dark:text-white">Email</label>
                            <div class="relative">
                                <input type="text" id="email" name="email" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" required aria-describedby="username-error">
                                <div class="hidden absolute inset-y-0 end-0 flex items-center pointer-events-none pe-3">
                                    <svg class="h-5 w-5 text-red-500" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                                    </svg>
                                </div>
                            </div>
                            <p class="hidden text-xs text-red-600 mt-2" id="username-error">Please include a valid username</p>
                        </div>
                        <div>
                            <div class="flex justify-between items-center">
                                <label for="password" class="block text-sm mb-2 dark:text-white">Password</label>
                            </div>
                            <div class="relative">
                                <input type="password" id="password" name="password" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" required aria-describedby="password-error">
                                <div class="hidden absolute inset-y-0 end-0 flex items-center pointer-events-none pe-3">
                                    <svg class="h-5 w-5 text-red-500" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                                    </svg>
                                </div>
                            </div>
                            <p class="hidden text-xs text-red-600 mt-2" id="password-error">8+ characters required</p>
                        </div>
                        <button type="submit" class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" name="signin">Sign in</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<script src="./node_modules/preline/dist/preline.js"></script>
</body>
</html>