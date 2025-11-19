<?php
// expects $commanders
?>
<h1>Commanders</h1>
<a href='/?entity=commander&create=1'>Create commander</a>
<table border='1' cellpadding='6'>
<tr><th>ID</th><th>Name</th><th>Rank</th><th>Actions</th></tr>
<?php foreach ($commanders as $c): ?>
<tr>
  <td><?= $c['commander_id'] ?></td>
  <td><?= htmlspecialchars($c['name']) ?></td>
  <td><?= htmlspecialchars($c['commander_rank']) ?></td>
  <td>
    <a href='/?entity=commander&edit=<?= $c['commander_id'] ?>'>Edit</a> |
    <a href='/?entity=commander&delete=<?= $c['commander_id'] ?>' onclick='return confirm("Delete?")'>Delete</a>
  </td>
</tr>
<?php endforeach; ?>
</table>
