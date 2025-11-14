<?php
// crud_product.php
require __DIR__ . '/db.php';

echo "<h2>Product CRUD</h2>";
$action = $_GET['action'] ?? 'list';

if ($action === 'list') {
    $rows = $pdo->query("SELECT * FROM Product ORDER BY product_id")->fetchAll();
    echo "<a href='?entity=product&action=create'>Create product</a><br><br>";
    echo "<table border='1' cellpadding='6'><tr><th>ID</th><th>Name</th><th>Price</th><th>Actions</th></tr>";
    foreach ($rows as $r) {
        $id = $r['product_id'];
        echo "<tr>
            <td>{$id}</td>
            <td>" . htmlspecialchars($r['name']) . "</td>
            <td>" . htmlspecialchars($r['price']) . "</td>
            <td>
                <a href='?entity=product&action=edit&id={$id}'>Edit</a> |
                <a href='?entity=product&action=delete&id={$id}' onclick='return confirm(\"Delete?\")'>Delete</a>
            </td>
        </tr>";
    }
    echo "</table>";
    exit;
}

if ($action === 'create') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $stmt = $pdo->prepare("INSERT INTO Product (name, price) VALUES (:name, :price)");
        $stmt->execute(['name'=>$_POST['name'],'price'=>$_POST['price']]);
        header("Location: ?entity=product");
        exit;
    }
    echo "<h3>Create product</h3>";
    echo "<form method='post'>
        Name: <input name='name' required><br>
        Price: <input name='price' type='number' step='0.01' required><br>
        <button>Create</button>
    </form>";
    exit;
}

if ($action === 'edit') {
    $id = (int)($_GET['id'] ?? 0);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $stmt = $pdo->prepare("UPDATE Product SET name = :name, price = :price WHERE product_id = :id");
        $stmt->execute(['name'=>$_POST['name'],'price'=>$_POST['price'],'id'=>$id]);
        header("Location: ?entity=product");
        exit;
    }
    $row = $pdo->prepare("SELECT * FROM Product WHERE product_id = :id");
    $row->execute(['id'=>$id]);
    $r = $row->fetch();
    if (!$r) { echo "Not found"; exit; }
    echo "<h3>Edit product</h3>";
    echo "<form method='post'>
        Name: <input name='name' value='".htmlspecialchars($r['name'])."' required><br>
        Price: <input name='price' type='number' step='0.01' value='".htmlspecialchars($r['price'])."' required><br>
        <button>Save</button>
    </form>";
    exit;
}

if ($action === 'delete') {
    $id = (int)($_GET['id'] ?? 0);
    $stmt = $pdo->prepare("DELETE FROM Product WHERE product_id = :id");
    $stmt->execute(['id'=>$id]);
    header("Location: ?entity=product");
    exit;
}
