<?php
/**
 * Created by PhpStorm.
 * User: xiechao
 * Date: 2019/11/01
 * Time: 4:57 PM
 */

namespace frontend\actions\music;


use common\models\Category;
use common\models\Library;
use common\models\UserList;
use liyifei\base\actions\ApiAction;
use yii;

class GroupApi extends ApiAction
{
    public $action;

    private $_get;

    private $_userId;

    public function run()
    {

        $this->_get = Yii::$app->request->get();

        $this->_userId = !empty($this->_get['user_id']) ? (int)$this->_get['user_id'] : 0;

        switch ($this->action) {
            case 'get_category':
                $ret = $this->getCategory();
                break;
            case 'get_categories_list':
                $ret = $this->getCatoriesList();
                break;
            case 'get_library':
                $ret = $this->getLibrary();
                break;
            case 'get_libraries_list':
                $ret = $this->getLibrariesList();
                break;
            case 'get_user_lists_by_type':
                $ret = $this->getUserListsByType();
                break;
            case 'get_user_list_music':
                $ret = $this->getUserListMusic();
                break;
            default:
                $ret = [];
                break;

        }

        return $this->success($ret);
    }

    public function getLibrariesList() {

        $ret = Library::find()
            ->andFilterWhere(['is_delete' => \common\definitions\Common::STATUS_NORMAL])
            ->all();

        return $ret;
    }

    public function getCatoriesList() {

        $isTab = !empty($this->_get['is_tab']) ? $this->_get['is_tab'] : 0;

        $ret = Category::find()
            ->andFilterWhere(['is_delete' => \common\definitions\Common::STATUS_NORMAL]);

        if ($isTab > 0) {
            $ret = $ret->andFilterWhere(['>', 'tab_sort_by' => 0]);
            $ret->orderBy(['tab_sort_by' => SORT_ASC]);
        } else {
            $ret->orderBy(['sort_by' => SORT_ASC]);
        }
        $ret = $ret->all();

        return $ret;
    }

    public function getCategory() {

        $get = Yii::$app->request->get();

        $categoryId = !empty($get['category_id']) ? $get['category_id'] : 0;

        $ret = Category::findOne(['id' => $categoryId]);

        return $ret;
    }

    public function getLibrary() {

        $get = Yii::$app->request->get();

        $libraryId = !empty($get['library_id']) ? $get['library_id'] : 0;

        $ret = Library::findOne(['id' => $libraryId]);

        return $ret;
    }

    public function getUserListMusic() {

        $get = Yii::$app->request->get();

        $userId = $this->_userId;

        $listId = !empty($get['list_id']) ? $get['list_id'] : 0;

        $ret = UserList::findOne(['id' => $listId]);

        return $ret;
    }

    public function getUserListsByType() {

            $get = Yii::$app->request->get();

            $userId = $this->_userId;

            $type = !empty($get['list_type']) ? $get['list_type'] : 0;

            if (empty($type)) {
                $type = [
                    UserList::LIST_TYPE_FAV,
                    UserList::LIST_TYPE_VIEW
                ];
            }

            $model = UserList::find()
                ->where(['user_id' => $userId]);
            if (!empty($type)) {
                $model->andFilterWhere(['list_type' => $type]);
            }
            $ret = $model->all();

            return $ret;
    }

}