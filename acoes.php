<?php
session_start();
require_once 'config.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $action = $_POST['action'] ??'';

    if($action === 'create'){
        $description = $_POST['description'] ??'';
        if(!empty($description)){
            $stmt = $pdo->prepare('INSERT INTO tasks (description, priority) VALUES (?, ?)');
            $stmt->execute([$_POST['description'], $_POST['priority']]);

        }
    }

    elseif($action === 'delete'){
        $id = $_POST['task_id'] ?? 0;
        if($id > 0){
            $stmt = $pdo->prepare('DELETE FROM tasks WHERE id=?');
            $stmt->execute([$id]);
        }
    }

    elseif ($action === 'update' && !empty($_POST['task_id']) && !empty($_POST['description'])) {
    $stmt = $pdo->prepare("UPDATE tasks SET description = ? WHERE id = ?");
    $stmt->execute([$_POST['description'], $_POST['task_id']]);
    $_SESSION['feedback_message'] = "Tarefa atualizada com sucesso!";
}

}

header('Location: index.php');
exit();