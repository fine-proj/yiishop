<div class="table-responsive">
    <table style="width: 100%; border: 1px solid red;" class="table table-hover table-striped">
        <thead>
        <tr style="background: #f9f9f9;">
            <th>Наименование</th>
            <th>Кол-во</th>
            <th>Цена</th>
            <th>Сумма</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($session['cart'] as $id => $item): ?>
            <tr>
                <td><?= $item['name'] ?></td>
                <td><?= $item['qty'] ?></td>
                <td><?= $item['price'] ?></td>
                <td><?= $item['price'] * $item['qty'] ?></td>
            </tr>
        <?php endforeach; ?>

        <tr>
            <td colspan="3">Итого:</td>
            <td><?= $session['cart.qty'] ?></td>
        </tr>
        <tr>
            <td colspan="3">Сумма:</td>
            <td><?= $session['cart.sum'] ?></td>
        </tr
        </tbody>
    </table>
</div>
