<?php
include_once '../src/functions.php';

// Start session and check login
session_start();
$isLoggedIn = isset($_SESSION['user_id']);

// Handle form submissions
if ($isLoggedIn && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $task = isset($_POST['task']) ? trim(htmlspecialchars($_POST['task'])) : null;
    $taskId = isset($_POST['task_id']) ? (int)$_POST['task_id'] : null;

    if ($task && $taskId) {
        // Edit task
        updateTask($pdo, $taskId, $task);
    } elseif ($task) {
        // Add new task
        addTask($pdo, $task);
    } elseif (isset($_POST['complete'])) {
        completeTask($pdo, (int)$_POST['complete']);
    } elseif (isset($_POST['delete'])) {
        deleteTask($pdo, (int)$_POST['delete']);
    }
}

// Fetch tasks for logged-in users
$tasks = $isLoggedIn ? getTasks($pdo) : [];
?>

<?php
$title = 'To-Do List'; // Page title
include_once '../src/views/header.php';
?>

<div class="max-w-4xl mx-auto mt-10 p-6 bg-gray-100 rounded shadow-lg">
    <?php if ($isLoggedIn): ?>
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-700">Welcome, <?= htmlspecialchars($_SESSION['username']); ?>!</h1>
            <a href="logout.php" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Logout</a>
        </div>

        <h2 class="text-xl font-semibold text-gray-800 mb-4">To-Do List</h2>

        <!-- Add New Task Form -->
        <form method="POST" class="mb-6">
            <div class="flex items-center gap-4">
                <input
                    type="text"
                    name="task"
                    class="w-full p-3 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter a new task"
                    required>
                <button
                    type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Add Task
                </button>
            </div>
        </form>

        <!-- Task List -->
        <?php if (!empty($tasks)): ?>
            <ul class="space-y-4">
                <?php foreach ($tasks as $task): ?>
                    <li class="flex justify-between items-center p-4 bg-white rounded shadow-sm border">
                        <span class="<?= $task['status'] ? 'line-through text-gray-400' : 'text-gray-700' ?>">
                            <?= htmlspecialchars($task['task']); ?>
                        </span>
                        <div class="flex space-x-2">
                            <?php if (!$task['status']): ?>
                                <form method="POST">
                                    <button
                                        name="complete"
                                        value="<?= $task['id']; ?>"
                                        class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                        ✔️ Complete
                                    </button>
                                </form>
                                <!-- Edit Task Form -->
                                <form method="POST" class="flex gap-2">
                                    <input
                                        type="text"
                                        name="task"
                                        value="<?= htmlspecialchars($task['task']); ?>"
                                        class="p-2 border border-gray-300 rounded focus:ring-2 focus:ring-yellow-500"
                                        required>
                                    <button
                                        name="task_id"
                                        value="<?= $task['id']; ?>"
                                        class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                                        ✏️ Edit
                                    </button>
                                </form>
                            <?php endif; ?>
                            <form method="POST">
                                <button
                                    name="delete"
                                    value="<?= $task['id']; ?>"
                                    class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                    🗑️ Delete
                                </button>
                            </form>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="text-gray-500 text-center mt-4">No tasks found. Start by adding a new task above!</p>
        <?php endif; ?>

    <?php else: ?>
        <div class="text-center">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">Welcome to the To-Do List App</h1>
            <p class="text-lg text-gray-600 mb-6">Log in to manage your tasks efficiently.</p>
            <a href="login.php" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                Log In
            </a>
        </div>
    <?php endif; ?>
</div>

<?php include_once '../src/views/footer.php'; ?>