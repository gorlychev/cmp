<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Api;
use app\models\User;

class SiteController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions() {
        return [
            'error'   => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class'           => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex() {
        $model    = Api::findAll([
                    "status"   => "active",
                    "protocol" => "http"
                        ]
        );
        $num      = count($model);
        $modelSSL = Api::findAll([
                    "status"   => "active",
                    "protocol" => "https"
                        ]
        );
        $numSSL   = count($modelSSL);
        return $this->render('index', [
                    'num'    => $num,
                    'numSSL' => $numSSL
        ]);
    }

    public function actionRegister() {
        $model = new User();

        if (isset($_POST["User"]) && $_POST["User"]) {
            $model->login    = strtolower($_POST["User"]["login"]);
            $model->password = $_POST["User"]["password"];
            if (!(User::findAll([
                        "login" => strtolower($_POST["User"]["login"])]))) {
                $model->save(false);
            }
            else {

            }

            /*   var_dump($model);
              die(); */
            //    return $this->render('login');
            return $this->actionIndex(); //::actionIndex();
        }

        return $this->render('register', ['model' => $model]);
    }

    public function actionProfile($model) {
        return $this->render('profile', [
                    'model' => $model,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin() {

        if (isset($_POST["User"])) {
            $loginData = $_POST["User"];
            $model     = User::findAll($_POST["User"]);
            //  print_r($model[0]);
            if ($model) {
                $_SESSION["userId"] = $model[0]->id;
                //   \yii\debug\controllers\UserController::actionProfile();
                //   Yii::$app->user->id = $model[0]->id;
                //   var_dump(Yii::$app->user);
                //       var_dump($_SESSION);
                //    $this->redirect(array('user/profile', 'userId' => $_SESSION["userId"]));
                //  die();
                //    return $this->goHome();
                return $this->actionProfile($model);
                /*   return $this->render('profile', [
                  'model' => $model,
                  ]); */
            }
        }
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new User();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
                    'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact() {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
                    'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout() {
        $numEmails["leboncoin"] = 7; //User::numEmails();
        return $this->render('about', array('numEmails' => $numEmails));
    }

    public function actionAdd() {
        $message = null;
        if (isset($_POST["Api"])) {
            $model = Api::findOne([
                        "ip" => $_POST["Api"]["ip"],
                            ]
            );
            if (!$model) {
                $model           = new Api();
                $model->ip       = $_POST["Api"]["ip"];
                $model->port     = $_POST["Api"]["port"];
                $model->protocol = $_POST["Api"]["protocol"];
                if ($model->save(false)) {
                    //    echo "OK";
                    //    die();
                    //    return $this->render('add', ['model' => $model]);
                    $message = "Added";
                }
            }
            else {
                $message = "Repeated";
                //  echo "Repeated";
                //  die();
            }
            //    var_dump($_POST);
        }
        $model = new Api();
        return $this->render('add', ['model' => $model, 'message' => $message]);
    }

}
