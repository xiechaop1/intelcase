<?php

namespace frontend\controllers;

use common\definitions\Common;
use common\definitions\Member as DefMember;
use common\helpers\Client;
use common\models\Member;
use liyifei\base\controllers\ViewController;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\web\View;

/**
 * Site controller
 */
class SiteController extends ViewController
{
    public $layout = '@frontend/views/layouts/main_w.php';

    public $ref;

    private $_action;

    public function beforeAction($action)
    {

        $this->_action = $action;

        $this->ref = Yii::$app->request->referrer;

        $notLoginActions = [
            'index_not_login',
        ];
        if ( in_array($action->id, $notLoginActions ) === true ) {
            return true;
        }

//        如果未登录，则直接返回
        if(Yii::$app->user->isGuest){
//            var_dump(Yii::$app->request->referrer);exit;
            return $this->goHome();
        }

        $identity = Yii::$app->user->identity;
        if (!empty($identity->web_session_id) &&
            (
                !empty($identity->login_time) && time() - $identity->login_time <= DefMember::LOGIN_EXPIRE_AT
            )
            &&
            (
                (Client::isMobile() && $identity->wap_session_id != Yii::$app->session->id)
                || (!Client::isMobile() && $identity->web_session_id != Yii::$app->session->id)
            )
        ){
            Yii::$app->user->logout();
            return $this->goHome();
        }

        //获取路径
        $path = Yii::$app->request->pathInfo;

        //忽略列表
//        if (in_array($path, $this->ignoreList)) {
//            return true;
//        }

//        if (Yii::$app->user->can($path)) {
//            return true;
//        } else {
//            throw new ForbiddenHttpException(Yii::t('app', 'message 401'));
//        }
        if (!empty(Yii::$app->user->identity) && Yii::$app->user->identity->member_status != Member::MEMBER_STATUS_NORMAL) {
//            return $this->goRegister();
        }

        return true;
    }

    public function goHome() {
        if ($this->_action->id == 'index_api') {
            throw new ForbiddenHttpException();
        }
        $source = Yii::$app->request->get('source');
        header('location: /passport/web_login?ref=' . urlencode($this->ref) . '&source=' . $source);
    }

    public function goRegister() {

        $status = Yii::$app->user->identity->member_status;
        $role = Yii::$app->user->identity->type;


        $url = $urlBase = '/passport/web_register?';
        $url .= 'type=' . $role == 10 ? 2 : $role;
        $url .= '&member_status=' . $status;
        $url .= '&id=' . Yii::$app->user->id;
        header('location: ' . $url);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup', 'index_not_login'],
                'rules' => [
                    [
                        'actions' => ['signup', 'index_not_login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => 'frontend\actions\site\Index',
            ],
            'index_not_login' => [
                'class' => 'frontend\actions\site\IndexNotLogin',
            ],
            'index_api' => [
                'class' => 'frontend\actions\site\GetJobListApi',
            ],
            'get_latest_view_api' => [
                'class' => 'frontend\actions\site\GetLatestViewApi',
            ],
            'save_history_uri' => [
                'class'     => 'frontend\actions\site\HistoryUri',
                'action'    => 'save',
            ],
            'delete_history_uri' => [
                'class'     => 'frontend\actions\site\HistoryUri',
                'action'    => 'delete',
            ],
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'joinus' => [
                'class' => 'frontend\actions\site\Joinus'
            ],
            'fav'   => [
                'class' => 'frontend\actions\site\Favourite'
            ],
        ];
    }

//    /**
//     * Displays homepage.
//     *
//     * @return mixed
//     */
//    public function actionIndex()
//    {
////        $latest = Article::find()
////            ->orderBy(['created_at' => SORT_DESC])
////            ->limit(6)->all();
////
////        $recommend = Yii::$app->article->fetchInPosition(\common\definitions\Article::IN_INDEX, 3);
//
////        $qu = BoutiqueGroup::find()->joinWith([
////            'product' => function($productQuery) {
////                $productQuery->joinWith([
////                    'tags' => function ($query)
////                    {
////                        $query->where(['name' => '测试标签', 'special_type' => 2]);
////                    }
////                ]);
////            }
////
////        ])->all();
////        var_dump($qu);
//
//        return $this->render('index', [
////            'latest' => $latest,
////            'recommend' => $recommend
//        ]);
//    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

//    /**
//     * Displays contact page.
//     *
//     * @return mixed
//     */
//    public function actionContact()
//    {
//        $this->layout = '@frontend/views/layouts/main_r.php';
//
//        return $this->render('contact', [
//        ]);
//    }
//
//    public function actionQuestion($category_id = 0, $kw = '')
//    {
//        $this->layout = '@frontend/views/layouts/main_r.php';
//
//        $categories = Category::find()
//            ->where(['type' => \common\definitions\Category::TYPE_HELP_ISSUE])
//            ->all();
//
//        if (!$category_id) {
//            $category_id = $categories[0]->id;
//        }
//
//        $query = HelpIssue::find()
//            ->where(['category_id' => $category_id])
//            ->orderBy(['created_at' => SORT_DESC]);
//
//        if ($kw) {
//            $query->andWhere([
//                'or',
//                ['like', 'title', $kw],
//                ['like', 'answer', $kw]
//            ]);
//        }
//
//        $issues = $query->all();
//
//
//        return $this->render('question', [
//            'categories' => $categories,
//            'category_id' => $category_id,
//            'issues' => $issues
//        ]);
//    }
//
//
//    /**
//     * Displays about page.
//     *
//     * @return mixed
//     */
//    public function actionAbout()
//    {
//        $this->layout = '@frontend/views/layouts/main_r.php';
//
//        return $this->render('about', [
//        ]);
//    }

    public function actionPrivacy()
    {
        $this->layout = '@frontend/views/layouts/main_r.php';

        return $this->render('privacy', [
        ]);
    }

    public function actionCookie()
    {
        $this->layout = '@frontend/views/layouts/main_r.php';

        return $this->render('cookie', [
        ]);
    }

    public function actionServiceManual()
    {
        $this->layout = '@frontend/views/layouts/main_r.php';

        return $this->render('service-manual', [
        ]);
    }

    public function actionTest()
    {
        return $this->renderPartial('@frontend/views/invoice/content');
    }
}
