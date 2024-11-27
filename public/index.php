<?php
include_once '../src/functions.php';

// check if the user is logged in
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['task'])) {
        addTask($pdo, $_POST['task']);
    } elseif (isset($_POST['complete'])) {
        completeTask($pdo, $_POST['complete']);
    } elseif (isset($_POST['delete'])) {
        deleteTask($pdo, $_POST['delete']);
    }
}

$tasks = getTasks($pdo);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <!-- Display the logged-in user's name -->
        <p>Welcome, <?= htmlspecialchars($_SESSION['username']); ?>!</p>
        <a href="logout.php">Logout</a>

        <h1>To-Do List</h1>
        <form method="POST">
            <input type="text" name="task" placeholder="Enter a new task" required>
            <button type="submit">Add Task</button>
        </form>

        <ul>
            <?php foreach ($tasks as $task): ?>
                <li>
                    <span class="<?= $task['status'] ? 'completed' : '' ?>">
                        <?= htmlspecialchars($task['task']) ?>
                    </span>
                    <?php if (!$task['status']): ?>
                        <form method="POST" style="display:inline;">
                            <button name="complete" value="<?= $task['id'] ?>">✔️</button>
                        </form>
                    <?php endif; ?>
                    <form method="POST" style="display:inline;">
                        <button name="delete" value="<?= $task['id'] ?>">🗑️</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>

</html>