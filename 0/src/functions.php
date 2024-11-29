<?php
include_once '../config/config.php';

// Fetch tasks
function getTasks($pdo)
{
    $stmt = $pdo->prepare("SELECT * FROM tasks ORDER BY created_at DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Add a task
function addTask($pdo, $task)
{
    $stmt = $pdo->prepare("INSERT INTO tasks (task) VALUES (:task)");
    $stmt->execute(['task' => $task]);
}

// Mark task as complete
function completeTask($pdo, $taskId)
{
    $stmt = $pdo->prepare("UPDATE tasks SET status = 1 WHERE id = :id");
    $stmt->execute(['id' => $taskId]);
}

// Delete a task
function deleteTask($pdo, $taskId)
{
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = :id");
    $stmt->execute(['id' => $taskId]);
}

function updateTask($pdo, $taskId, $newTask)
{
    try {
        $stmt = $pdo->prepare('UPDATE tasks SET task = :task WHERE id = :id');
        $stmt->execute([':task' => $newTask, ':id' => $taskId]);
    } catch (PDOException $e) {
        die('Error updating task: ' . $e->getMessage());
    }
}
