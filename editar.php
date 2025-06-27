<?php
require_once 'config.php';
$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
$stmt->execute([$id]);
$task = $stmt->fetch();

if (!$task) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Tarefa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Editar Tarefa</h1>

    <form action="acoes.php" method="POST">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
        <input type="text" name="description" class="task-input" value="<?= htmlspecialchars($task['description']) ?>" required>
        <button type="submit" class="btn">Salvar</button>
    </form>
</div>
</body>
</html>
