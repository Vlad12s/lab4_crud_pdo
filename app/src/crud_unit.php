<?php
// crud_unit.php
require __DIR__ . '/db.php';

echo "<h2>Unit CRUD</h2>";
$action = $_GET['action'] ?? 'list';

if ($action === 'list') {
    // join to show commander name
    $rows = $pdo->query("
        SELECT u.unit_id, u.unit_name, u.type, u.commander_id, c.name AS commander_name
        FROM Unit u
        LEFT JOIN Commander c ON u.commander_id = c.commander_id
        ORDER BY u.unit_id
    ")->fetchAll();

    echo "<a href='?entity=unit&action=create'>Create unit</a><br><br>";
    echo "<table border='1' cellpadding='6'><tr><th>ID</th><th>Name</th><th>Type</th><th>Commander</th><th>Actions</th></tr>";
    foreach ($rows as $r) {
        $id = $r['unit_id'];
        echo "<tr>
            <td>{$id}</td>
            <td>".htmlspecialchars($r['unit_name'])."</td>
            <td>".htmlspecialchars($r['type'])."</td>
            <td>".htmlspecialchars($r['commander_name'] ?? '-')."</td>
            <td>
                <a href='?entity=unit&action=edit&id={$id}'>Edit</a> |
                <a href='?entity=unit&action=delete&id={$id}' onclick='return confirm(\"Delete?\")'>Delete</a>
            </td>
        </tr>";
    }
    echo "</table>";
    exit;
}

if ($action === 'create') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $stmt = $pdo->prepare("INSERT INTO Unit (unit_name, type, commander_id) VALUES (:name, :type, :cmd)");
        $stmt->execute(['name'=>$_POST['name'],'type'=>$_POST['type'],'cmd'=>$_POST['commander_id'] ?: null]);
        header("Location: ?entity=unit");
        exit;
    }
    // commander select
    $cmds = $pdo->query("SELECT commander_id, name FROM Commander ORDER BY name")->fetchAll();
    echo "<h3>Create unit</h3>";
    echo "<form method='post'>
        Name: <input name='name' required><br>
        Type: <input name='type' required><br>
        Commander: <select name='commander_id'><option value=''>-- none --</option>";
    foreach ($cmds as $c) {
        echo "<option value='{$c['commander_id']}'>".htmlspecialchars($c['name'])."</option>";
    }
    echo "</select><br>
        <button>Create</button>
    </form>";
    exit;
}

if ($action === 'edit') {
    $id = (int)($_GET['id'] ?? 0);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $stmt = $pdo->prepare("UPDATE Unit SET unit_name=:name, type=:type, commander_id=:cmd WHERE unit_id=:id");
        $stmt->execute(['name'=>$_POST['name'],'type'=>$_POST['type'],'cmd'=>$_POST['commander_id'] ?: null,'id'=>$id]);
        header("Location: ?entity=unit");
        exit;
    }
    $row = $pdo->prepare("SELECT * FROM Unit WHERE unit_id = :id");
    $row->execute(['id'=>$id]);
    $r = $row->fetch();
    if (!$r) { echo "Not found"; exit; }

    $cmds = $pdo->query("SELECT commander_id, name FROM Commander ORDER BY name")->fetchAll();

    echo "<h3>Edit unit</h3>";
    echo "<form method='post'>
        Name: <input name='name' value='".htmlspecialchars($r['unit_name'])."' required><br>
        Type: <input name='type' value='".htmlspecialchars($r['type'])."' required><br>
        Commander: <select name='commander_id'><option value=''>-- none --</option>";
    foreach ($cmds as $c) {
        $sel = ($c['commander_id'] == $r['commander_id']) ? "selected" : "";
        echo "<option value='{$c['commander_id']}' $sel>".htmlspecialchars($c['name'])."</option>";
    }
    echo "</select><br>
        <button>Save</button>
    </form>";
    exit;
}

if ($action === 'delete') {
    $id = (int)($_GET['id'] ?? 0);
    $stmt = $pdo->prepare("DELETE FROM Unit WHERE unit_id = :id");
    $stmt->execute(['id'=>$id]);
    header("Location: ?entity=unit");
    exit;
}
