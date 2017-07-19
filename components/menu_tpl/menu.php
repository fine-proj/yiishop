<li>
    <a href="<?= \yii\helpers\Url::to( ['category/view', 'id' => $category['id']] ); ?>">
        <?= $category['name']; ?>
        <?php if(  isset ($category['childs'])  ):?>
            <!-- ставим знак плюсик возле родительской категории -->
            <span class="badge pull-right"><i class="fa fa-plus"></i></span>
        <?php endif; ?>
    </a>
    <!-- проверяем наличие потомков в этом узле, и если они есть,
    то строим для них подсписок с помощью рекурсии -->
    <?php if(  isset ($category['childs'])  ):?>
        <ul>
            <?= $this->getMenuHtml($category['childs']); ?>
        </ul>
    <?php endif; ?>
</li>