<?php
// crud_orders.php
require __DIR__ . '/db.php';

echo "<h2>Orders CRUD</h2>";
$action = $_GET['action'] ?? 'list';

if ($action === 'list') {
    $rows = $pdo->query("
        SELECT o.*, p.name AS product_name
        FROM Orders o
        LEFT JOIN Product p ON o.product_id = p.product_id
        ORDER BY o.order_id
    ")->fetchAll();
    echo "<a href='?entity=orders&action=create'>Create order</a><br><br>";
    echo "<table border='1' cellpadding='6'><tr>
        <th>ID</th><th>Customer</th><th>Product</th><th>Quantity</th><th>Contract date</th><th>Actions</th></tr>";
    foreach ($rows as $r) {
        $id = $r['order_id'];
        echo "<tr>
            <td>{$id}</td>
            <td>".htmlspecialchars($r['customer_name'])."</td>
            <td>".htmlspecialchars($r['product_name'] ?? '-')."</td>
            <td>".htmlspecialchars($r['planned_quantity'])."</td>
            <td>".htmlspecialchars($r['contract_date'])."</td>
            <td>
                <a href='?entity=orders&action=edit&id={$id}'>Edit</a> |
                <a href='?entity=orders&action=delete&id={$id}' onclick='return confirm(\"Delete?\")'>Delete</a>
            </td>
        </tr>";
    }
    echo "</table>";
    exit;
}

if ($action === 'create') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $stmt = $pdo->prepare("INSERT INTO Orders (customer_name, customer_address, contract_number, contract_date, product_id, planned_quantity) VALUES (:cn, :ca, :cnum, :cdate, :pid, :pq)");
        $stmt->execute([
            'cn'=>$_POST['customer_name'],
            'ca'=>$_POST['customer_address'],
            'cnum'=>$_POST['contract_number'],
            'cdate'=>$_POST['contract_date'],
            'pid'=>$_POST['product_id'] ?: null,
            'pq'=>$_POST['planned_quantity']
        ]);
        header("Location: ?entity=orders");
        exit;
    }
    $products = $pdo->query("SELECT product_id, name FROM Product ORDER BY name")->fetchAll();
    echo "<h3>Create order</h3>";
    echo "<form method='post'>
        Customer name: <input name='customer_name' required><br>
        Customer address: <input name='customer_address' required><br>
        Contract number: <input name='contract_number' required><br>
        Contract date: <input name='contract_date' type='date' required><br>
        Product: <select name='product_id'><option value=''>-- none --</option>";
    foreach ($products as $p) {
        echo "<option value='{$p['product_id']}'>".htmlspecialchars($p['name'])."</option>";
    }
    echo "</select><br>
        Planned quantity: <input name='planned_quantity' type='number' required><br>
        <button>Create</button>
    </form>";
    exit;
}

if ($action === 'edit') {
    $id = (int)($_GET['id'] ?? 0);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $stmt = $pdo->prepare("UPDATE Orders SET customer_name=:cn, customer_address=:ca, contract_number=:cnum, contract_date=:cdate, product_id=:pid, planned_quantity=:pq WHERE order_id=:id");
        $stmt->execute([
            'cn'=>$_POST['customer_name'],
            'ca'=>$_POST['customer_address'],
            'cnum'=>$_POST['contract_number'],
            'cdate'=>$_POST['contract_date'],
            'pid'=>$_POST['product_id'] ?: null,
            'pq'=>$_POST['planned_quantity'],
            'id'=>$id
        ]);
        header("Location: ?entity=orders");
        exit;
    }
    $row = $pdo->prepare("SELECT * FROM Orders WHERE order_id = :id");
    $row->execute(['id'=>$id]);
    $r = $row->fetch();
    if (!$r) { echo "Not found"; exit; }
    $products = $pdo->query("SELECT product_id, name FROM Product ORDER BY name")->fetchAll();
    echo "<h3>Edit order</h3>";
    echo "<form method='post'>
        Customer name: <input name='customer_name' value='".htmlspecialchars($r['customer_name'])."' required><br>
        Customer address: <input name='customer_address' value='".htmlspecialchars($r['customer_address'])."' required><br>
        Contract number: <input name='contract_number' value='".htmlspecialchars($r['contract_number'])."' required><br>
        Contract date: <input name='contract_date' type='date' value='".htmlspecialchars($r['contract_date'])."' required><br>
        Product: <select name='product_id'><option value=''>-- none --</option>";
    foreach ($products as $p) {
        $sel = ($p['product_id'] == $r['product_id']) ? "selected" : "";
        echo "<option value='{$p['product_id']}' $sel>".htmlspecialchars($p['name'])."</option>";
    }
    echo "</select><br>
        Planned quantity: <input name='planned_quantity' type='number' value='".htmlspecialchars($r['planned_quantity'])."' required><br>
        <button>Save</button>
    </form>";
    exit;
}

if ($action === 'delete') {
    $id = (int)($_GET['id'] ?? 0);
    $stmt = $pdo->prepare("DELETE FROM Orders WHERE order_id = :id");
    $stmt->execute(['id'=>$id]);
    header("Location: ?entity=orders");
    exit;
}
