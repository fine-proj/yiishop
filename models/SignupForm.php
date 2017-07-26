<?php
namespace app\models;
use Yii;
use yii\base\Model;

class SignupForm extends Model
{

    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            ['username', 'trim'],
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Пользователь с таким именем уже существует'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['password', 'string', 'min' => 1],
            ['rememberMe', 'boolean'],
        ];
    }

    public function attributeLabels(){
        return[
            'username' => 'Логин',
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить',
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {

        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->save();
        return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }

}