<?php 
session_start();
    require_once 'config.php';
$filter_priority = $_GET['filter_priority'] ?? null;

if ($filter_priority && in_array($filter_priority, [1, 2, 3])) {
    // Filtra por prioridade
    $sql = "SELECT * FROM tasks WHERE priority = ? ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$filter_priority]);
} else {
    // Sem filtro: todas as tarefas
    $sql = "SELECT * FROM tasks ORDER BY created_at DESC";
    $stmt = $pdo->query($sql);
}
$tasks = $stmt->fetchAll();

 

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de tarefas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Gestor de Tarefas</h1>
        <?php if (isset($_SESSION['feedback_message'])): ?>
    <div class="feedback-message">
        <?= $_SESSION['feedback_message'] ?>
    </div>
    <?php unset($_SESSION['feedback_message']); ?>
<?php endif; ?>

        <form action="acoes.php"  class="task-form" method="POST">
            <input type="hidden" name="action" value="create">
            <input type="text" name="description" class="task-input" placeholder="Digite a nova tarefa..." required>
            <select name="priority" class="task-input">
    <option value="1">Baixa</option>
    <option value="2">Média</option>
    <option value="3">Alta</option>
</select>
            <button type="submit" class="btn">Adicionar</button>


        </form>

        <div class="filter-container" style="margin-bottom: 20px;"> 
    <strong>Filtrar:</strong> 
    <a href="index.php">Todas</a> | 
    <a href="index.php?filter_priority=3">Alta</a> | 
    <a href="index.php?filter_priority=2">Média</a> | 
    <a href="index.php?filter_priority=1">Baixa</a>
</div>

        <ul class="task-list">
            <?php if(count($tasks) > 0): ?>
                <?php foreach($tasks as $task): ?>
                    <?php
switch ($task['priority']) {
  case 3: $priorityClass = 'priority-alta'; $priorityLabel = 'Alta'; break;
  case 2: $priorityClass = 'priority-media'; $priorityLabel = 'Média'; break;
  default: $priorityClass = 'priority-baixa'; $priorityLabel = 'Baixa'; break;
}
?>
<li class="task-item <?= $task['is_completed'] ? 'completed' : '' ?> <?= $priorityClass ?>" data-task-id="<?= $task['id'] ?>">
    <span class="task-description"><?= htmlspecialchars($task['description']) ?></span>
    <span class="priority-label"><?= $priorityLabel ?></span>

                    <div class="task-actions">
                        <form action="acoes.php" method="POST">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="task_id" value="<?= $task['id']?>">
                            <button type="submit" class="btn-icon btn-delete"  title="Excluir Tarefa">×</button> 
                            <a href="editar.php?id=<?= $task['id'] ?>" class="btn-icon btn-edit" title="Editar tarefa">✏</a>

                        </form>
                    </div>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="no-tasks">Nenhuma tarefa foi encontrada. Adicione uma nova tarefa!</li>
            <?php endif; ?>
        </ul>
    </div>
</body>
</html>