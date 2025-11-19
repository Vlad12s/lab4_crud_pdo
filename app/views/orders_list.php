<?php
// expects $orders
?>
<h1>Orders</h1>
<a href='/?entity=orders&create=1'>Create order</a>
<table border='1' cellpadding='6'>
<tr><th>ID</th><th>Customer</th><th>Product</th><th>Quantity</th><th>Contract date</th><th>Actions</th></tr>
<?php foreach ($orders as $o): ?>
<tr>
  <td><?= $o['order_id'] ?></td>
  <td><?= htmlspecialchars($o['customer_name']) ?></td>
  <td><?= htmlspecialchars($o['product_name'] ?? '-') ?></td>
  <td><?= htmlspecialchars($o['planned_quantity']) ?></td>
  <td><?= htmlspecialchars($o['contract_date']) ?></td>
  <td>
    <a href='/?entity=orders&edit=<?= $o['order_id'] ?>'>Edit</a> |
    <a href='/?entity=orders&delete=<?= $o['order_id'] ?>' onclick='return confirm("Delete?")'>Delete</a>
  </td>
</tr>
<?php endforeach; ?>
</table>
