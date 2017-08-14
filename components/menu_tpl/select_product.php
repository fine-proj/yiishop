<option value = "<?= $category['id'] ?>"
<?php if($category['id'] == $this->model->category_id) echo ' selected' ?>
>
<?= $tab . $category['name'] ?>
</option>
<!-- проверяем наличие потомков в этом узле, и если они есть,
   то строим для них подсписок с помощью рекурсии -->
<?php if(  isset ($category['childs'])  ):?>
    <ul>
        <?= $this->getMenuHtml($category['childs'], $tab.'-'); ?>
    </ul>
<?php endif; ?>