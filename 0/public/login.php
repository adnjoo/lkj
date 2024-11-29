<?php
include_once '../config/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");
        } else {
            $error = "Invalid username or password.";
        }
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<?php
$title = 'Login'; // Dynamic title for the header
include_once '../src/views/header.php';
?>
<div class="flex justify-center items-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md bg-white rounded shadow-lg p-6">
        <h3 class="text-2xl font-bold text-center text-gray-700 mb-4">Login</h3>

        <!-- Display error message if login fails -->
        <?php if (!empty($error)): ?>
            <div class="mb-4 p-4 text-red-700 bg-red-100 rounded">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form method="POST" class="space-y-4">
            <div>
                <label for="username" class="block text-gray-700 font-medium">Username</label>
                <input
                    type="text"
                    name="username"
                    id="username"
                    class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
            </div>
            <div>
                <label for="password" class="block text-gray-700 font-medium">Password</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
            </div>
            <button
                type="submit"
                class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                Login
            </button>
        </form>

        <div class="text-center mt-4">
            <p class="text-gray-600">
                Don't have an account?
                <a href="register.php" class="text-blue-500 hover:underline">Register here</a>
            </p>
        </div>
    </div>
</div>

<?php include_once '../src/views/footer.php'; ?>