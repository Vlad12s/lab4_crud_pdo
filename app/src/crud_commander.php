<?php
// crud_commander.php
require __DIR__ . '/db.php';

echo "<h2>Commander CRUD</h2>";
$action = $_GET['action'] ?? 'list';

if ($action === 'list') {
    $rows = $pdo->query("SELECT * FROM Commander ORDER BY commander_id")->fetchAll();
    echo "<a href='?entity=commander&action=create'>Create commander</a><br><br>";
    echo "<table border='1' cellpadding='6'><tr><th>ID</th><th>Name</th><th>Rank</th><th>Actions</th></tr>";
    foreach ($rows as $r) {
        $id = $r['commander_id'];
        echo "<tr>
            <td>{$id}</td>
            <td>".htmlspecialchars($r['name'])."</td>
            <td>".htmlspecialchars($r['commander_rank'])."</td>
            <td>
                <a href='?entity=commander&action=edit&id={$id}'>Edit</a> |
                <a href='?entity=commander&action=delete&id={$id}' onclick='return confirm(\"Delete?\")'>Delete</a>
            </td>
        </tr>";
    }
    echo "</table>";
    exit;
}

if ($action === 'create') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $stmt = $pdo->prepare("INSERT INTO Commander (name, commander_rank) VALUES (:name, :rank)");
        $stmt->execute(['name'=>$_POST['name'],'rank'=>$_POST['rank']]);
        header("Location: ?entity=commander");
        exit;
    }
    echo "<h3>Create commander</h3>";
    echo "<form method='post'>
        Name: <input name='name' required><br>
        Rank: <input name='rank' required><br>
        <button>Create</button>
    </form>";
    exit;
}

if ($action === 'edit') {
    $id = (int)($_GET['id'] ?? 0);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $stmt = $pdo->prepare("UPDATE Commander SET name = :name, commander_rank = :rank WHERE commander_id = :id");
        $stmt->execute(['name'=>$_POST['name'],'rank'=>$_POST['rank'],'id'=>$id]);
        header("Location: ?entity=commander");
        exit;
    }
    $row = $pdo->prepare("SELECT * FROM Commander WHERE commander_id = :id");
    $row->execute(['id'=>$id]);
    $r = $row->fetch();
    if (!$r) { echo "Not found"; exit; }
    echo "<h3>Edit commander</h3>";
    echo "<form method='post'>
        Name: <input name='name' value='".htmlspecialchars($r['name'])."' required><br>
        Rank: <input name='rank' value='".htmlspecialchars($r['commander_rank'])."' required><br>
        <button>Save</button>
    </form>";
    exit;
}

if ($action === 'delete') {
    $id = (int)($_GET['id'] ?? 0);
    $stmt = $pdo->prepare("DELETE FROM Commander WHERE commander_id = :id");
    $stmt->execute(['id'=>$id]);
    header("Location: ?entity=commander");
    exit;
}
