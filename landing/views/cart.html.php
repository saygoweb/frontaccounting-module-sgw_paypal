<?php
// use SGW_Landing\DebtorTransModel;
// $cart = new DebtorTransModel()
?>
Reference: <?= $invoice->reference ?>
<?= $invoice->dueDate ?>
<table>
<th>
<td></td>
<td></td>
<td></td>
</th>
<?php foreach($invoice->_items as $item): ?>
<tr>
<td><?= $item->description ?></td>
<td><?= $item->unitPrice ?></td>
<td><?= $item->quantity ?></td>
</tr>
<?php endforeach; ?>
</table>
<?= $paynow ?>