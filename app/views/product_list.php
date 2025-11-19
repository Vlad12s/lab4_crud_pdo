<?php
// expects $products array
?>
<h1>Products</h1>
<a href='/?entity=product&create=1'>Create product</a>
<table border='1' cellpadding='6'>
<tr><th>ID</th><th>Name</th><th>Price</th><th>Actions</th></tr>
<?php foreach ($products as $p): ?>
<tr>
  <td><?= $p['product_id'] ?></td>
  <td><?= htmlspecialchars($p['name']) ?></td>
  <td><?= htmlspecialchars($p['price']) ?></td>
  <td>
    <a href='/?entity=product&edit=<?= $p['product_id'] ?>'>Edit</a> |
    <a href='/?entity=product&delete=<?= $p['product_id'] ?>' onclick='return confirm("Delete?")'>Delete</a>
  </td>
</tr>
<?php endforeach; ?>
</table>