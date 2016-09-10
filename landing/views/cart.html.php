<?php
// use SGW_Landing\DebtorTransModel;
// $cart = new DebtorTransModel()
?>
<div class="page-header">
	<h1>Invoice <span class="text-muted small"><?= $invoice->reference ?></span></h1>
</div>

<form class="form">
<form-group>
</form-group>
</form>
Reference: <?= $invoice->reference ?>
<?= $invoice->dueDate ?>
<table class="table">
<tr>
<th>Description</th>
<th>Price</th>
<th>Quantity</th>
</tr>
<?php foreach($invoice->_items as $item): ?>
<tr>
<td><?= $item->description ?></td>
<td><?= $item->unitPrice ?></td>
<td><?= $item->quantity ?></td>
</tr>
<?php endforeach; ?>
</table>
<div class="pull-right">
<?= $paynow ?>
</div>
