<?php
namespace app\modules\admin\controllers;
class DefaultController extends AppAdminController{

    public function actionIndex(){
        return $this->render('index');
    }
}
