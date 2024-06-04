<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGNUP</title>
    <link href="Assets/css/tailwind.css" rel="stylesheet">
</head>
<body class="dark:bg-slate-900 bg-gray-100 flex justify-center items-center h-full">
<form method="post" action="<?= htmlentities($_SERVER['PHP_SELF']) ?>" enctype="multipart/form-data" class="p-6 bg-white shadow-lg rounded-lg">
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
</form>
<script src="./node_modules/preline/dist/preline.js"></script>
<script>
    function confirmAddUser() {
        return confirm('Are you sure you want to create this account');
    }
</script>
</body>
</html>
