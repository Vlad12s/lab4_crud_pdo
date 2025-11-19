<?php
// expects $edit, $order, $products
?>
<h1><?= $edit ? 'Edit order' : 'Create order' ?></h1>
<form method='post'>
  Customer name: 
  <input name='customer_name' value='<?= htmlspecialchars($order['customer_name'] ?? '') ?>' required><br><br>

  Customer address: 
  <input name='customer_address' value='<?= htmlspecialchars($order['customer_address'] ?? '') ?>' required><br><br>

  Contract number: 
  <input name='contract_number' value='<?= htmlspecialchars($order['contract_number'] ?? '') ?>' required><br><br>

  Contract date: 
  <input name='contract_date' type='date' value='<?= htmlspecialchars($order['contract_date'] ?? '') ?>' required><br><br>

  Product:
  <select name='product_id'>
    <option value=''>-- none --</option>
    <?php foreach ($products as $p): ?>
      <option value='<?= $p['product_id'] ?>'
        <?= (isset($order['product_id']) && $order['product_id']==$p['product_id']) ? 'selected' : '' ?>>
        <?= htmlspecialchars($p['name']) ?>
      </option>
    <?php endforeach; ?>
  </select><br><br>

  Planned quantity:
  <input name='planned_quantity' type='number'
         value='<?= htmlspecialchars($order['planned_quantity'] ?? '') ?>' required><br><br>

  <button>Save</button>
</form>
