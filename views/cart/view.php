<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>
<div class="container">
    <?php if(Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success alert-dismissable" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <?php echo Yii::$app->session->getFlash('success'); ?>
        </div>
    <?php endif; ?>
    <?php if(Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger alert-dismissable" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <?php echo Yii::$app->session->getFlash('error'); ?>
        </div>
    <?php endif; ?>

    <?php if(!empty($session['cart'])):?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th>Фото</th>
                    <th>Наименование</th>
                    <th>Кол-во</th>
                    <th>Цена</th>
                    <th>Сумма по товару</th>
                    <th><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($session['cart'] as $id => $item): ?>
                    <tr>
                        <td><a href="<?= Url::to(['product/view', 'id' => $id]) ?>">
                            <?= \yii\helpers\Html::img("@web/images/products/{$item['img']}",
                                ['alt'=>$item['name'], 'height'=>50]) ?>
                            </a>
                        </td>
                        <td><a href="<?= Url::to(['product/view', 'id' => $id]) ?>">
                            <?= $item['name'] ?>
                            </a>
                        </td>
                        <td><?= $item['qty'] ?></td>
                        <td><?= $item['price'] ?></td>
                        <td><?= $item['price']*$item['qty'] ?></td>
                        <td><span data-id="<?= $id ?>" class="glyphicon glyphicon-remove text-danger del-item" aria-hidden="true"></span></td>
                    </tr>
                <?php endforeach; ?>

                <tr>
                    <td colspan="4">Итого:</td>
                    <td><?= $session['cart.qty']  ?></td>
                </tr>
                <tr>
                    <td colspan="4">Сумма:</td>
                    <td><?= $session['cart.sum']  ?></td>
                </tr
                </tbody>
            </table>
        </div>
        <div class="col-sm-6">
        <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($order, 'name'); ?>
            <?= $form->field($order, 'email'); ?>
            <?= $form->field($order, 'phone'); ?>
            <?= $form->field($order, 'address'); ?>
            <?= Html::submitButton('Заказать', ['class'=>'btn btn-success']); ?>
        <?php ActiveForm::end(); ?>
        </div>
    <?php else: ?>
        <h3>Корзина пуста</h3>
    <?php endif;?>
</div>