<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/4/17
 * Time: 下午11:41
 */

namespace console\controllers;

use common\models\UserCompany;
use liyifei\chinese2pinyin\Chinese2pinyin;
use common\models\City;
use common\models\MemberSpecial;
use common\models\Orders;
use common\models\ConsultantCompany;
use common\models\Job;
use common\models\Documents;
use common\models\Company;
use common\models\Industry;
use common\models\IndustryLink;
use common\models\Post;
use common\models\Recommend;
use common\models\RecommendHistory;
use common\models\Tag;
use common\models\PostBasic;
use common\models\Member;
use common\helpers\No;
use yii\db\Query;

use yii\console\Controller;
use Yii;

class ImportController extends Controller
{

    public static $postMap = [
        'Finance' => '财务',
        'Legal' => '法务',
        '成本合约' => '',
        'Senior Management' => '高级管理',
        'Project Management' => '项目管理/项目协调',
        'Marketing & PR' => '公关/媒介',
        'Admin & EA PA' => '行政/后勤/文秘',
        'HR' => '人力资源',
        'Marketing & PR' => '市场',
        'CSR / Technical Support' => '技术支持',
        'Sales' => '销售',
        'Operation' => '机械设计/制造/维修',
        'Engineering' => '',
        'R&D' => '汽车研发制造',
        'Operation' => '生产/营运',
        'Quality' => '质量管理/安全防护',
        'Sourcing' => '采购/贸易',
        'Supplier Quality' => '',
        'Supply Chain' => '',
        'IT' => 'IT运维/技术支持',
        'Product Management' => '产品',
        '投资' => '房地产规划/开发',
        '招商' => '',
        'Consulting' => '咨询/顾问/调研',
        'Integration 模块集成协调' => '项目管理/项目协调',
        'Launch 量产工厂项目' => '项目管理/项目协调',
        'RFQ - SOP 完整流程(Auto)' => '项目管理/项目协调',
        'Technical Project 工程技术类项目' => '项目管理/项目协调',
        'Equipment, Indirect, CAPEX 设备维修' => '机械设计/制造/维修',
        'Advance Engineering Innovation 先期工程设计' => '',
        'Application Engineering 应用工程' => '汽车研发制造',
        'Calibration & Testing 测试标定' => '汽车研发制造',
        'Electronics Electrical EE 电子电器设计' => '汽车研发制造',
        'Hardware 硬件' => '汽车研发制造',
        'Mechanical 机械结构' => '汽车研发制造',
        'Product Engineering 产品工程开发' => '汽车研发制造',
        'Software 软件' => '汽车研发制造',
        'Styling Industrial Design 造型工业设计' => '汽车研发制造',
        'System 系统' => '汽车研发制造',
        'Facility 厂务 土建' => '生产/营运',
        'Production 生产' => '生产/营运',
        'Process Engineering 工艺工程' => '生产/营运',
        'Manufacturing Engineering 制造工程' => '生产/营运',
        'EHS' => '质量管理/安全防护',
    ];

    public static $industryMap = [
        '物流地产' => '物业',
        '共创空间' => '商业地产',
        '市政建设' => '产业地产',
        '教育地产' => '产业地产',
        '4A Agency' => '广告|传媒|教育|文化',
        'Consultanting Agency' => '广告|传媒|教育|文化',
        'Marketing Research Agency' => '广告|传媒|教育|文化',
        'PR Agency' => '广告|传媒|教育|文化',
        'Culture 文化' => '广告|传媒|教育|文化',
        'Education 教育' => '广告|传媒|教育|文化',
        'Mining 矿产冶金' => '能源|化工|环保',
        '天然气 LNG' => '能源|化工|环保',
        '太阳能 Solar' => '能源|化工|环保',
        '水处理 Water Treatment' => '能源|化工|环保',
        '环保 Environmental' => '能源|化工|环保',
        '风电 Wind Power' => '能源|化工|环保',
        'F&B' => '旅游/酒店/餐饮服务/生活服务',
        'Hospitality' => '旅游/酒店/餐饮服务/生活服务',
        'Industrial Software Provider 工业软件提供商' => '旅游/酒店/餐饮服务/生活服务',
        'Agrochemical  农化' => '能源|化工|环保',
        'Chemical 化工' => '能源|化工|环保',
        'Fine Chemical 精细化学品' => '能源|化工|环保',
        '炼油 refinery' => '能源|化工|环保',
        '石油 Petroleum' => '能源|化工|环保',
        '石油化工 Petrochemical' => '能源|化工|环保',
        'Hopsital 医院' => '医院/医疗/护理',
        'Medical Device / Equipment' => '医疗设备/器械',
        'Pharmaceutical / Biogical' => '制药/生物工程',
        'Testing and Certification Organization 检测认证机构' => '检测/认证',
        'Entertainment' => '旅游/酒店/餐饮服务/生活服务',
        'OTA' => '旅游/酒店/餐饮服务/生活服务',
        'Tourism' => '旅游/酒店/餐饮服务/生活服务',
        '供应链咨询 Supply Chain Consulting' => '专业服务(咨询/财会/法律/翻译等)',
        '审计事务所 Audit Firm' => '专业服务(咨询/财会/法律/翻译等)',
        '律所 Legal Firm' => '法律服务机构',
        '快递 Express ' => '旅游/酒店/餐饮服务/生活服务',
        '电商物流 E Logistic ' => '旅游/酒店/餐饮服务/生活服务',
        '租赁汽车服务 Car Rental Service' => '租赁服务',
        '第三方物流公司 3PL ' => '外包服务',
        '货代 Forwarding ' => '外包服务',
        'Ecommerce 电商' => '互联网/移动互联网/电子商务',
        'Gaming 游戏' => '网络游戏',
        'IT Service Integration IT服务/系统集成' => 'IT服务/系统集成',
        'IT Software 计算机软件' => '计算机软件',
        'Life Service' => 'IT服务/系统集成',
        'TP Agency' => 'IT服务/系统集成',
        '共享经济 Sharing Economy' => 'IT服务/系统集成',
        '航空航天 Aerospace ' => '军工设备/航空/航天制造',
        '船舶 Ship ' => '军工设备/航空/航天制造',
        '轨道交通 Transportation Railway ' => '机械制造/机电/重工',
        '仪器仪表 Instrument ' => '仪器/仪表/工业自动化/电气',
        '光学 Optical ' => '仪器/仪表/工业自动化/电气',
        '工业工具 Machine Tool' => '仪器/仪表/工业自动化/电气',
        '工业照明 Lighting ' => '仪器/仪表/工业自动化/电气',
        '工业第三方工程咨询公司 Eng Consulting Firm ' => '仪器/仪表/工业自动化/电气',
        '工业自动化 Automation ' => '仪器/仪表/工业自动化/电气',
        '无人机 Drone ' => '军工设备/航空/航天制造',
        '机器人 Robitics ' => '工业机器人',
        '机械/设备制造 Machinery, Equipment' => '机械制造/机电/重工',
        '激光 Laser ' => '仪器/仪表/工业自动化/电气',
        '商业运营公司' => '商业地产',
        '地产中介/咨询公司' => '房地产交易\分销',
        '地产基金' => '商业地产',
        '总包/施工单位' => '建筑设计\工程',
        '设计院/设计公司' => '建筑设计\工程',
        '住宅地产' => '住宅地产',
        '养老地产' => '泛地产(酒店、养老、长租公寓)',
        '开发商-写字楼' => '商业地产',
        '商业地产' => '商业地产',
        '工业地产' => '产业地产',
        '文旅地产' => '产业地产',
        '开发商-综合' => '建筑设计\工程',
        '物业管理公司' => '物业',
        '农机工程机械 Off - Highway ' => '机械制造/机电/重工',
        '市场调研 Market Reaserch ' => '汽车/摩托车',
        '摩托车 Motorcycle' => '汽车/摩托车',
        '整车厂 OEM ' => '汽车/摩托车',
        '汽车售后 Automotive Aftermarket ' => '汽车/摩托车',
        '汽车广告 Auto Advtisement' => '汽车/摩托车',
        '汽车第三方咨询公司 Auto Consulting Firm' => '汽车/摩托车',
        '汽车设备提供商 Auto Equipment' => '汽车/摩托车',
        '汽车零配件 Auto Components' => '汽车/摩托车',
        '个人护理 Personal Care' => '食品/饮料/烟酒/日化',
        '化妆品 Cosmetics' => '食品/饮料/烟酒/日化',
        '奢侈品 Luxury' => '奢侈品/收藏品',
        '婴儿用品 Baby Care' => '百货/批发/零售',
        '家具家居 Furniture / Homeware ' => '家具/家电',
        '时装 Fashion& Apparel' => '服装服饰/纺织/皮革',
        '烟酒 Wine & Spiritis' => '食品/饮料/烟酒/日化',
        '耐消品 Consumer Durable' => '消费电子',
        '营养品 Nutrition' => '食品/饮料/烟酒/日化',
        '购物中心 Shopping Center ' => '百货/批发/零售',
        '超市 Super Market' => '百货/批发/零售',
        '运动 Sports' => '百货/批发/零售',
        '采购办 Buying Office ' => '百货/批发/零售',
        '食品 Food' => '食品/饮料/烟酒/日化',
        '消费电子 Consumer Electronics ' => '消费电子',
        '半导体 Semiconductor' => '电子技术/半导体/集成电路',
        '网络设备 Network Equipment' => '电子技术/半导体/集成电路',
        '计算机硬件 IT Hardware' => '计算机硬件/网络设备',
        '通信电信 Telecom' => '通信(设备/运营/增值)',
        '互联网金融' => '互联网金融',
        '会计/审计' => '财务/审计服务',
        '保险 Insurance' => '保险',
        '信托' => '信托/担保/拍卖/典当',
        '公募基金' => '基金/证券/期货/投资',
        '典当' => '信托/担保/拍卖/典当',
        '投资' => '基金/证券/期货/投资',
        '担保' => '信托/担保/拍卖/典当',
        '拍卖' => '信托/担保/拍卖/典当',
        '期货' => '基金/证券/期货/投资',
        '私募基金' => '基金/证券/期货/投资',
        '证券' => '基金/证券/期货/投资',
        '资产管理 Asset management' => '资产管理',
        '银行 Bank' => '银行',

        '房地产 Real Estate & Construction' => '房地产|建筑|物业',
        'Agency 广告咨询' => '其他',
        'Education & Culture 文化教育' => '其他',
        'Energy Environmental 能源 环保' => '其他',
        'Hospitality & F&B 酒店餐饮' => '服务|外包|中介',
        'Industrial Software 工业软件' => '服务|外包|中介',
        'Oil Chemical 石油石化化工 ' => '其他',
        'Pharmaceutical / Healthcare 制药医疗' => '生物|制药|医疗|护理',
        'Testing and Certification 检测认证' => '服务|外包|中介',
        'Tourism& Entertainment 旅游娱乐' => '服务|外包|中介',
        '专业服务类 Professional Service' => '服务|外包|中介',
        '互联网电商 IT / Internet / E-commerce ' => '互联网|游戏|软件',
        '其他交通 Transportation' => '汽车|机械|制造',
        '工业制造 Industrial Manufacturing' => '汽车|机械|制造',
        '汽车行业 Automotive ' => '汽车|机械|制造',
        '消费品 Consumer Goods' => '消费品',
        '电子/通信/硬件 Electronics / Telecom / Hardware' => '电子|通讯|硬件',
        '金融 Banking Business' => '金融',

    ];

    public function actionResetpassword()
    {
        $uids = [];

        $mobiles = [
            '18516071662'
        ];

        $initPassword = '123456';
        foreach ($mobiles as $mobile) {
            $model = Member::findOne([
                'mobile' => $mobile,
            ]);

            $model->password = Yii::$app->security->generatePasswordHash($initPassword);

            $r = $model->save();

            if ($r) {
                echo $mobile . ' reset successful!' . "\n";
            } else {
                echo $mobile . ' reset failed!err: ' . $model->getFirstErrors() .  "\n";
            }
        }

    }

    public function actionMember()
    {
        $account = (new Query())
            ->from('account')
            ->where(['status' => 1])
            ->limit(10000)
            ->all();

        $map = [
            'USER_NAME'			=> 'user_name',
            'NAME'				=> 'true_name',
            'ENGLISH_NAME'		=> 'english_name',
            'MOBILE'			=> 'mobile',
            'MAIL'				=> 'email',
            'AVATAR'			=> 'avatar',
            'UID'               => 'old_user_no',
            'REGISTER_TIME'		=> 'created_at',
            'COMPANY_UID'       => 'company_id',

        ];

        $initPassword = '123456';

        foreach ($account as $line) {
            echo $line['UID'] . ' ' . $line['ACC_NUM'] . "\n";
            $model = \common\models\Member::findOne([
                'user_name' => $line['USER_NAME'],
                'true_name' => $line['NAME'],
                'english_name'  => $line['ENGLISH_NAME'],
                'mobile'    => $line['MOBILE'],
            ]);
            if (empty($model)) {
                $model = new \common\models\Member();
                $model->user_no = No::create('Users', 'U');
            } else {
                $model->user_no = 'U20191225' . (100000 + $model->id);
            }

            foreach ($map as $oldCol => $col) {
                if (!($oldCol == 'REGISTER_TIME'
                    && strlen($line[$oldCol]) > 11)
                ) {
                    if ($oldCol == 'COMPANY_UID') {
                        $consultantMap = [
                            '2c8755443af94d99b06c0dd41b5a141a'  => '3fa03c16177048878f7fde19d3d2da18',
                            '6ebf81c7df6e4b38a3f7f9507788f604'  => '3fa03c16177048878f7fde19d3d2da18',
                        ];
                        $val = $line[$oldCol];

                        if (isset($consultantMap[$val])) {
                            $val = $consultantMap[$val];
                        }

                        $tmp = ConsultantCompany::findOne([
                            'import_company_id' => $val
                        ]);
                        if (!empty($tmp)) {
                            $model->$col = $tmp->id;
                        }
                    } else {
                        $model->$col = $line[$oldCol];
                    }
                }
            }
            $model->password = Yii::$app->security->generatePasswordHash($initPassword);
            $model->mobile_section = '+86';
            $model->type = \common\models\Member::MEMBER_TYPE_CONSULTANT;
            $model->member_status = Member::MEMBER_STATUS_NORMAL;

            $r = $model->save();
            if (!$r) {
                var_dump($model->getFirstErrors());
                exit;
            } else {
                $userId = $model->id;
            }

            $funcRet = (new Query())
                ->from('account_fun_tag_ref')
                ->where(['ACCOUNT_UID' => $line['UID']])
                ->all();

            $tagIds = [];
            foreach ($funcRet as $funcRow) {
                $tagIds[] = $funcRow['FUN_TAG_UID'];
            }

            $industryRet = (new Query())
                ->from('account_indust_tag_ref')
                ->where(['ACCOUNT_UID' => $line['UID']])
                ->all();

            foreach ($industryRet as $funcRow) {
                $tagIds[] = $funcRow['INDUST_TAG_UID'];
            }

            foreach ($tagIds as $tagId) {
//                $funcId = $funcRow['FUN_TAG_UID'];
                $tagRet = (new Query())
                    ->from('tag_config')
                    ->where([
                        'UID'   => $tagId
                    ])
                    ->one();

                $tag = Tag::findOne([
                    'tag_name'  => $tagRet['TAG_NAME']
                ]);

                if (!empty($tag)) {
                    $spId = $tag->id;
                } else {
                    $tagModel = new Tag();
                    $tagModel->tag_name = $tagRet['TAG_NAME'];
                    $tagModel->tag_type = Tag::TAG_TYPE_MEMBER_SPECIAL;
                    $r = $tagModel->save();
                    if (!$r) {
                        var_dump($tagModel->getFirstErrors());
                    } else {
                        $spId = $tagModel->id;
                    }
                }

                $msModel = MemberSpecial::findOne([
                    'user_id'   => $userId,
                    'tag_id'    => $spId,
                ]);

                if (empty($msModel)) {
                    $msModel = new MemberSpecial();
                    $msModel->user_id   = $userId;
                    $msModel->tag_id    = $spId;
                    $r = $msModel->save();
                    if (!$r) {
                        var_dump($msModel->getFirstErrors());
                    }
                }
            }




        }
    }

    public function actionJob()
    {
        $fp = @fopen('output_mPosition.json', 'r');

        $map = [
            'positionName' => 'job_name',
//            'recruitsNum' => 'head_count',
            'customerUid' => 'customer_company_id',
            'estimateSalary' => 'salary_max',
            'chargeType' => 'customer_fee_type',
            'estimatedProportion' => 'customer_fee',
            'shareProportion' => 'share_rate',
//            'estimateIncome' => 'estimated_share',
            'remark' => 'remarks',
            'positionFile' => 'upload_file',
            'accountUid'    => 'user_id',
            '_id'   => 'import_job_no',
            'createTime'    => 'old_created_at',
            'modifyTime'    => 'old_updated_at',
            'status'        => 'job_open_status',
        ];

//        $r = rand(0,1000);
        $r = 0;
        $ct = 0;

        while (($line = fgets($fp)) !== false) {
            $ct++;
            if ($ct < $r) {
                continue;
            }

            $arr = json_decode($line, true);

//            var_dump($arr['positionTags']);
//            exit;

            if (in_array($ct, [2266, 2267])) {
                continue;
            }

            foreach ($map as $importCol => $col) {
                if (isset($arr[$importCol])) {
                    if ($importCol == 'customerUid') {
                        if (!empty($arr[$importCol])) {
                            $c = Company::findOne(['import_company_id' => $arr[$importCol]]);
                            if (!empty($c)) {
                                $insert[$col] = $c->id;
                            }
                        }
                    } else if ( in_array($importCol, ['estimateIncome', 'estimateSalary', 'estimatedProportion', 'shareProportion', 'estimateIncome']) ) {
                        $val = current($arr[$importCol]);
                        if ($importCol == 'shareProportion'
                            || $importCol == 'estimatedProportion'
                        ) {
                            $val *= 100;
                            $val = (string)$val;
                        }
                        $insert[$col] = $val;

                    } else if ($importCol == 'accountUid') {
                        $member = Member::findOne(['old_user_no' => $arr[$importCol]]);
                        if (!empty($member)) {
                            $insert[$col] = $member->id;
                        }
                    } else if ($importCol == 'positionFile') {
                        if (!empty($arr[$importCol]['url'])) {
                            $insert[$col] = $arr[$importCol]['url'];
                            if (strpos($insert[$col], 'http') === false) {
                                $insert[$col] = 'http://www.vhewa.com' . $insert[$col];
                            }
                        }
                    } else if ($importCol == 'chargeType') {
//                        var_dump($arr[$importCol]);
                        if ($arr[$importCol] == 0) {
                            $insert[$col] = Job::POST_FEE_TYPE_RATE;
                        } elseif ($arr[$importCol] == 1) {
                            $insert[$col] = Job::POST_FEE_TYPE_FIX;
                        }
                    } else if ($importCol == 'createTime'
                        || $importCol == 'modifyTime'
                    ) {
                        if (strlen(intval($arr[$importCol])) > 11) {
                            $insert[$col] = intval($arr[$importCol] / 1000);
                        }   else {
                            $insert[$col] = intval($arr[$importCol]);
                        }
                    } else if ($importCol == 'status') {
                        if ($arr[$importCol] == 1) {
                            $insert[$col] = Job::POST_OPEN_STATUS_OPEN;
                        } else {
                            $insert[$col] = Job::POST_OPEN_STATUS_CLOSE;
                        }
                    } else {
                        if (!empty($arr[$importCol])) {
                            $insert[$col] = (string)$arr[$importCol];
                        }
                    }
                }
            }
            if (!empty($arr['positionTags'])) {
                foreach ($arr['positionTags'] as $tag) {
//                    var_dump($tag['type']);
                    switch ($tag['type']) {
                        case 'city':
//                            $insert['excpet_city_str'] = $tag['tagName'];
                            $cityStr = $tag['tagName'];
//var_dump($cityStr);
                            if (strpos($cityStr, ' ')=== false)
                                break;
                            list($chn, $eng) = explode(' ', $cityStr);
                            $ret = City::find()
                                ->where(['like', 'city_name', $chn])
                                ->andWhere([
                                    'deleted_at' => null,
                                ])
                                ->all();
//                            var_dump($ret);
                            if (!empty($ret)) {
                                foreach ($ret as $one) {
                                    $insert['work_city'] = $one->id;
                                    break;
                                }
                            }
                            break;
                        case 'industry':
                            $tmpIndustry = $tag['tagName'];
                            if (isset(self::$industryMap[$tmpIndustry])) {
                                $newIndustry = self::$industryMap[$tmpIndustry];
                            } else {
                                break;
                            }
                            $ret = Industry::findOne(['industry' => $newIndustry]);
////                            $insert['excpet_industry'] = $tmpIndustry;
                            if (!empty($ret)) {
                                $insert['industry'] = $ret->id;
                                $insert['industry_str'] = $newIndustry;
                            }
                            if (!empty($tag['companyUid'])) {
                                $insert['import_company_id'] = $tag['companyUid'];
                            }
                            break;
                        case 'function':
                            $tmpFunction = $tag['tagName'];
                            if (isset(self::$postMap[$tmpFunction])) {
                                $newPost = self::$postMap[$tmpFunction];
                            } else {
                                break;
                            }
                            $postId = Tag::findOne([
                                    'tag_name' => $newPost,
                                    'tag_type' => Tag::TAG_TYPE_POST_BASIC_CLASS_SECOND,
                                ]);
//                            var_dump($newPost);
                            $postRet = PostBasic::find()
                                ->where(['second_class' => $postId])
                                ->all();
//                            var_dump($postRet);
//                            exit;
////                            $insert['excpet_industry'] = $tmpIndustry;
                                if (!empty($postRet)) {
                                    foreach ($postRet as $row) {
                                        $insert['post'] = $row->id;
                                        break;
                                    }
//                                    $insert['fir'] = $ret->id;
//                                    $insert['industry_str'] = $newIndustry;
                                }
//                                var_dump($insert);exit;
                            break;
                    }
                }
            }

            $insert['head_count'] = 1;
            $insert['customer_fee_way'] = '二期';
            $insert['pay_time'] = '';
            $insert['recruitment_time'] = '';

            var_dump($insert);
//            exit;

            $model = Job::findOne([
                'import_job_no' => $insert['import_job_no']
            ]);
            if (empty($model)) {
                $model = new Job();
                echo 'new: ';
//                var_dump($insert);exit;
            } else {
                echo 'import: ';
            }

            echo $ct . ' ' . $insert['import_job_no'] . ' ' . $insert['job_name'] . "\n";

            $model->load($insert, '');
            $r = $model->exec();
            if (!$r) {
                var_dump($model->getFirstErrors());
            }

        }
    }

    public function actionRecommend()
    {
        $fp = @fopen('output_mCandidateFlowState.json', 'r');

        $map = [
            'positionId'    => 'post_id',
            'candidateId'   => 'document_id',
            'accountId'     => 'user_id',
            'companyId'     => 'company_id',
        ];

        $c = 0;
        while (($line = fgets($fp)) !== false) {
            $arr = json_decode($line, true);

//            var_dump($arr);exit;

            foreach ($map as $importCol => $col) {
                if (!empty($arr[$importCol])) {
//                    var_dump($importCol);
                    switch ($importCol) {
                        case 'positionId':
                            $val = $arr[$importCol];
                            $tmp = Job::findOne([
                                'import_job_no' => $val
                            ]);
                            if (!empty($tmp)) {
                                $insert[$col] = $tmp->id;
                            }
                            break;
                        case 'candidateId':
                            $val = $arr[$importCol];
                            $tmp = Documents::findOne([
                                'import_user_no' => $val
                            ]);
                            if (!empty($tmp)) {
                                $insert[$col] = $tmp->id;
                            }
                            break;
                        case 'accountId':
                            $val = $arr[$importCol];
                            $tmp = Member::findOne([
                                'old_user_no' => $val
                            ]);
                            if (!empty($tmp)) {
                                $insert[$col] = $tmp->id;
                            }
                            break;
                        case 'companyId':
                            // Todo: 得看一下导入的ID是啥字段
                            $val = $arr[$importCol];

                            $consultantMap = [
                                '2c8755443af94d99b06c0dd41b5a141a'  => '3fa03c16177048878f7fde19d3d2da18',
                                '6ebf81c7df6e4b38a3f7f9507788f604'  => '3fa03c16177048878f7fde19d3d2da18',
                            ];

                            if (isset($consultantMap[$val])) {
                                $val = $consultantMap[$val];
                            }

                            $tmp = ConsultantCompany::findOne([
                                'import_company_id' => $val
                            ]);
                            if (!empty($tmp)) {
                                $insert[$col] = $tmp->id;
                            }
                            break;
                    }
                }
            }

            echo $c++;

            echo ' ';

//            var_dump($insert);

            if (empty($insert['post_id'])) {
                echo 'no post_id, continue ... ' . "\n";
                continue;
            }

            echo 'post_id: ' . $insert['post_id'];
//                    $insert[$col] = (string)$arr[$importCol];

//                    var_dump($insert);exit;
                    // 接单
                    $orderModel = Orders::findOne([
                        'user_id'   => $insert['user_id'],
                        'post_id'   => $insert['post_id'],
                    ]);

                    if (empty($orderModel)) {
                        $orderModel = new Orders();
                        echo 'new order: ';
                    } else {
                        echo 'old order ' . $orderModel->id . ': ';
                    }

                    $orderModel->load($insert, '');
                    $r = $orderModel->save();
                    if (!$r) {
                        var_dump($orderModel->getFirstErrors());
                    }
                    $insert['order_id'] = $orderModel->id;

                    if (empty($insert['document_id'])) {
                        echo 'no document_id, continue ... '  . "\n";
                        continue;
                    }

            echo 'document_id: ' . $insert['document_id'];

                    // 推荐
                    $recommendModel = Recommend::findOne([
                        'user_id'   => $insert['user_id'],
                        'post_id'   => $insert['post_id'],
                        'order_id'  => $insert['order_id'],
                        'document_id'   => $insert['document_id'],
                    ]);

                    if (empty($recommendModel)) {
                        $recommendModel = new Recommend();
                        echo 'new recommend: ';
                    } else {
                        echo 'old recommend ' . $recommendModel->id . ': ';
                    }
                    $recommendModel->load($insert, '');
                    $recommendModel->recommend_filter = Recommend::RECOMMEND_FILTER_READ;
                    $recommendModel->recommend_filter_detail = Recommend::RECOMMEND_FILTER_READ;
                    $recommendModel->recommend_status = Recommend::RECOMMEND_STATUS_PASS;
                    $r = $recommendModel->save();
                    if (!$r) {
                        var_dump($recommendModel->getFirstErrors());
                    }
                    $insert['recommend_id'] = $recommendModel->id;

                    // 推荐历史
                    $recommendHistoryModel = RecommendHistory::findOne([
                        'recommend_id'  => $insert['recommend_id']
                    ]);

                    if (empty($recommendHistoryModel)) {
                        $recommendHistoryModel = new RecommendHistory();
                        echo 'new recommend_history: ';
                    } else {
                        echo 'old recommend_history ' . $recommendHistoryModel->id . ': ';
                    }

                    $recommendHistoryModel->load($insert, '');
                    $recommendHistoryModel->recommend_filter = Recommend::RECOMMEND_FILTER_READ;
                    $recommendHistoryModel->recommend_filter_detail = Recommend::RECOMMEND_FILTER_READ;
                    $recommendHistoryModel->recommend_status = Recommend::RECOMMEND_STATUS_PASS;
                    $r = $recommendHistoryModel->save();
                    if (!$r) {
                        var_dump($recommendModel->getFirstErrors());
                    }
                    echo  "\n";


        }
    }

    public function actionCompany()
    {
        $fp = @fopen('output_mCustomer.json', 'r');

        $ct = 0;

        $map = [
            '_id'    => 'import_company_id',
            'companyName'   => 'company_name',
//            'aliasTwo'      => 'abbreviation',
//            'companySize'   => 'company_size',
//            'companyUrl'    => 'website',
//            'company_introduce' => 'introduce',
//            'city'              => 'city_str',
//            'industry'          => 'industry_str',
        ];



        $companyTypeMap = [
            '外商独资企业'                => Company::COMPANY_TYPE_FOREIGN_SINGLE,
            '中外合资企业'                => Company::COMPANY_TYPE_JOINT,
            '国有企业'                  => Company::COMPANY_TYPE_NATIONAL,
            '私人企业'                  => Company::COMPANY_TYPE_PRIVATE_UNFUNDED,
            '政府\机关\非盈利机构'       => Company::COMPANY_TYPE_GOVERNMENT,
        ];

//        $r = rand(0, 139700);
        $c = 0;
        while (($line = fgets($fp)) !== false) {
//            if ($c++ < $r) {
//                continue;
//            }

            $arr = json_decode($line, true);
//            var_dump($arr);
//            exit;

            foreach ($map as $importCol => $col) {
                if (!empty($arr[$importCol])) {
//                    if (
//                        ($importCol == 'createTime'
//                            && !empty($line[$importCol]) && strlen($line[$importCol]) > 11)
//                        || ($importCol == 'updateTime'
//                            && !empty($line[$importCol]) && strlen($line[$importCol]) > 11)
//                    ) {
////                            $insert[$col] = 0;
//                    } else {
                        $insert[$col] = (string)$arr[$importCol];
//                    }
//
                }
            }


            if (!empty($arr['tags'])) {
                foreach ($arr['tags'] as $tag) {
                    switch ($tag['type']) {
//                        case 'city':
////                            $insert['excpet_city_str'] = $tag['tagName'];
//                            $cityStr = $tag['tagName'];
//                            list($chn, $eng) = explode(' ', $cityStr);
//                            $ret = City::find()
//                                ->where(['like', 'city_name', $chn])
//                                ->all();
//                            if (!empty($ret)) {
//                                foreach ($ret as $one) {
//                                    $insert['city'] = $one->id;
//                                }
//                            }
//                            break;
//                        case 'industry':
////                            $tmpIndustry = $tag['tagName'];
////                            $newIndustry = $industryMap[$tmpIndustry];
////                            $ret = Industry::findOne(['industry' => $newIndustry]);
//////                            $insert['excpet_industry'] = $tmpIndustry;
////                            if (!empty($ret)) {
////                                $insert['industry'] = $ret->id;
////                                $insert['industry_str'] = $newIndustry;
////                            }
//                            if (!empty($tag['companyUid'])) {
//                                $insert['import_company_id'] = $tag['companyUid'];
//                            }
//                            break;
                        case 'comNature':

                            $insert['company_type'] = Company::$companyType2Name[$companyTypeMap[$tag['tagName']]];
                            break;
                    }
                }
            }

            echo $ct++ . ' ';

            if (!empty($insert['import_company_id'])) {
                $model = Company::findOne([
                    'import_company_id' => $insert['import_company_id']
                ]);
            }

            if (empty($model)) {
                $model = Company::findOne([
                    'company_name'  => $insert['company_name']
                ]);
            } else {
                echo 'import [id] : ';
            }

            if (empty($model)) {
                $model = new Company();
                echo 'new company: ';
            } else {
                echo 'import [name] : ';
            }

            echo $insert['import_company_id'] . ' ' . $insert['company_name'];
            echo "\n";

            $model->load($insert, '');
//            var_dump($model);exit;
            $r = $model->exec();
            if (!$r) {
                var_dump($model->getFirstErrors());
            }
        }
    }

    public function actionCandidate()
    {
        $fp = @fopen('output_mCandidate.json', 'r');

        $ct = 0;
        $map = [
            'avatar' 	=> 'avatar',
            'name'		=> 'uname',
            'gender'	=> 'gender',
            'age'		=> 'age',
            'dateOfBirth'	=> 'birthday',
            '_id'	=> 'import_user_no',
            'phone'			=> 'mobile',
            'currentCompany'	=> 'current_company',
            'currentPosition'	=> 'current_post',
            'currentSalary'		=> 'current_salary',
            'channelSource'		=> 'resource',
            'mail'				=> 'email',
            'resumeUrl'			=> 'document_file',
            'createTime'		=> 'import_created_at',
            'updateTime'		=> 'import_updated_at',
            'remark'			=> 'self_evaluation',
        ];

        $industryMap = self::$industryMap;
//        $r = rand(0, 390000);
        $r = 220148;
        $r = 0;
        $c = 0;
        $startTime = strtotime('-12weeks');
        $startAdapter = false;
        while (($line = fgets($fp)) !== false) {
            if ($c++ < $r) {
                continue;
            }

            $arr = json_decode($line, true);

//            var_dump($arr['createTime']);
//            var_dump(Date('Y-m-d H:i:s', $arr['createTime']/1000));
//            exit;

            if (strlen(intval($arr['createTime'])) > 11) {
                $arr['createTime'] /= 1000;
            }

            if ($arr['createTime'] < $startTime && $startAdapter) {
                continue;
            }

            if (in_array($arr['candidateNo'], ['182466', '375558', '104682', '220148', '119553', '220148'])) {
                continue;
            }
            echo $c . ' ' .$arr['_id'] . ' ' . $arr['candidateNo'] . "\n";
//            var_dump($arr);
//            exit;
            $insert = [];
            foreach ($map as $importCol => $col) {
                if (!empty($arr[$importCol])) {
                    if ($importCol == 'currentSalary') {
                        $insert[$col] = current($arr[$importCol]);
                    } else {
                        if ($importCol == 'createTime'
                            || $importCol == 'updateTime'
                        ) {
                            $arr[$importCol] = intval($arr[$importCol]);
                            if ( strlen($arr[$importCol]) > 11) {
                                $arr[$importCol] = intval($arr[$importCol] / 1000);
                            }
                            $insert[$col] = $arr[$importCol];
                        } else {
                            $insert[$col] = (string)$arr[$importCol];
                        }

                    }
                }
            }

            if (!empty($arr['tags'])) {
                foreach ($arr['tags'] as $tag) {
                    switch ($tag['type']) {
                        case 'city':
                            $insert['excpet_city_str'] = $tag['tagName'];
                            break;
                        case 'industry':
                            $tmpIndustry = $tag['tagName'];
//                            $newIndustry = $industryMap[$tmpIndustry];
//                            $ret = Industry::findOne(['industry' => $newIndustry]);
                            $insert['excpet_industry'] = $tmpIndustry;
//                            $insert['current_industry_str'];
                            break;
                        case 'level':
//                            $insert['']
                    }
                }
            }
            $model = Documents::findOne([
                'import_user_no'   => (string)$insert['import_user_no'],
            ]);
//var_dump($insert);exit;
            if (empty($model)) {
                $model = new Documents();
            }
            $model->load($insert, '');
//            var_dump($model);exit;

            try {
                $ret = $model->save();
                if (!$ret) {
                    var_dump($model->getFirstErrors());
                }
            } catch (Exception $e) {

            }

//            exit;


        }
    }

    public function actionIndustry()
    {
        $fp = @fopen('hewa_industry.csv', 'r');

        $ct = 0;
        while (($line = fgets($fp)) !== false) {
            $ct++;
            if ($ct <= 2) {
                continue;
            }
            $splited = explode(',', $line);

            $parent = $splited[2];
            $industry = $splited[3];
            $link = $splited[4];

            if (empty($industry)) {
                continue;
            }

            $isNew = false;
            $parentData = Industry::findOne(['industry' => $parent]);
            if (empty($parentData)) {
                $model = new Industry([
                    'industry' => $parent,
                    'parent_id' => 0,
                ]);
                $parentRet = $model->save();

                $parentId = $model->id;
                $isNew = true;
            } else {
                $parentId = $parentData->id;
            }

            $industryData = Industry::findOne(['industry' => $industry]);
            if (empty($industryData)) {
                $model = new Industry([
                    'industry' => $industry,
                    'parent_id' => $parentId,
                ]);
                $industryRet = $model->save();

                $industryId = $model->id;
                $isNew = true;
            } else {
                $industryId = $industryData->id;
            }

            if (!empty($link) && !$isNew) {
//                $linkData = Industry::findOne(['like', 'industry', $link]);
//                var_dump($link);
                $linkData = Industry::find()
                    ->where([
                        'like', 'industry', $link,
                    ])
                    ->andFilterWhere(['parent_id' => 0,])
                    ->andWhere([
                        'deleted_at' => null,
                    ])
                    ->limit(1)
                    ->all();
//                var_dump($linkData);
                foreach ($linkData as $l) {
                    var_dump($l->id);

//                exit;
                    if (!empty($l->id)) {
                        $linkModel = new IndustryLink([
                            'industry_id' => $industryId,
                            'link_industry_id' => $l->id,
                        ]);
                        $linkModel->save();
                    }
                }
            }

//
//            $tagName1 = Tag::findOne([
//                'tag_name'  => $tag1,
//                'tag_type'  => Tag::TAG_TYPE_INDUSTRY_CLASS_FIRST
//            ]);
//
//            if ( empty($tagName1) ) {
//                $newTag = new Tag([
//                    'tag_name'  => $tagName1,
//                    'tag_type'  => Tag::TAG_TYPE_INDUSTRY_CLASS_FIRST,
//                ]);
//
//                $newTag->save();
//            }


//            var_dump($model->errors);
        }
    }

        public function actionPost()
    {
        $fp = @fopen('hewa_post.csv', 'r');

        $ct = 0;
        $temp = [];
        while (($line = fgets($fp)) !== false) {
            $ct++;
            if ($ct <= 2) {
                continue;
            }
            $splited = explode(',', $line);

            $post_no = $splited[0];
            $first_class = $splited[1];
            $second_class = $splited[2];
            $post = $splited[3];
//            $link = $splited[4];

            if ( empty($post) ) {
                continue;
            }

//            var_dump($temp);
            $isNew = false;
            if (empty($temp['fc'][$first_class])) {
                $firstClassRet = Tag::findOne(['tag_name' => $first_class]);
                if (empty($firstClassRet)) {
                    $model = new Tag([
                        'tag_name' => $first_class,
                        'tag_type' => Tag::TAG_TYPE_POST_BASIC_CLASS_FIRST
                    ]);
                    $firstRet = $model->save();

                    $firstClassId = $model->id;
                    $temp['fc'][$first_class] = $firstClassId;
                    $isNew = true;
                } else {
                    $firstClassId = $firstClassRet->id;
                    $temp['fc'][$first_class] = $firstClassId;
                }
            } else {
                $firstClassId = $temp['fc'][$first_class];
            }

            if (empty($temp['sc'][$first_class])) {
                $secondClassRet = Tag::findOne(['tag_name' => $second_class]);
                if (empty($secondClassRet)) {
                    $model = new Tag([
                        'tag_name' => $second_class,
                        'tag_type' => Tag::TAG_TYPE_POST_BASIC_CLASS_SECOND
                    ]);
                    $secondRet = $model->save();

                    $secondClassId = $model->id;
                    $temp['sc'][$second_class] = $secondClassId;
                    $isNew = true;
                } else {
                    $secondClassId = $secondClassRet->id;
                    $temp['sc'][$second_class] = $secondClassId;
                }
            } else {
                $secondClassId = $temp['sc'][$second_class];
            }

            if (empty($temp['tg'][$post])) {
                $postRet = Tag::findOne(['tag_name' => $post]);
                if (empty($postRet)) {
                    $model = new Tag([
                        'tag_name' => $post,
                        'tag_type' => Tag::TAG_TYPE_POST_BASIC
                    ]);
                    $pRet = $model->save();

                    $postId = $model->id;
                    $temp['tg'][$post] = $postId;
                    $isNew = true;
                } else {
                    $postId = $postRet->id;
                    $temp['tg'][$post] = $postId;
                }
            } else {
                $postId = $temp['tg'][$post];
            }

            $tempRet = PostBasic::findOne([
                'post_id' => $postId,
            ]);

            if (!empty($tempRet)) {
                $tempRet->first_class = $firstClassId;
                $tempRet->second_class = $secondClassId;
                $tempRet->post_no = $post_no;
                $tempRet->save();
            } else {
                $tempRet = new PostBasic([
                    'post_no' => $post_no,
                    'first_class' => $firstClassId,
                    'second_class' => $secondClassId,
                    'post_id' => $postId,
                ]);
                $tempRet->save();
            }

//
//            $tagName1 = Tag::findOne([
//                'tag_name'  => $tag1,
//                'tag_type'  => Tag::TAG_TYPE_INDUSTRY_CLASS_FIRST
//            ]);
//
//            if ( empty($tagName1) ) {
//                $newTag = new Tag([
//                    'tag_name'  => $tagName1,
//                    'tag_type'  => Tag::TAG_TYPE_INDUSTRY_CLASS_FIRST,
//                ]);
//
//                $newTag->save();
//            }


//            var_dump($model->errors);
        }
    }

    public function actionCity()
    {
        $cities = City::find()
            ->where([
                'deleted_at' => null,
            ])
            ->all();

        $convert = new Chinese2pinyin();
        foreach ($cities as $c) {
            $name = $c->city_name;
            $eng = $convert->transformUcWords($name);
            $c->first_word = strtoupper(mb_substr($eng, 0, 1, 'UTF-8'));
            var_dump($name . ' ' . $c->first_word);
            $r = $c->save();
            var_dump($r);
        }

    }


    public function actionCcompany()
    {
        $data = file('./change_company.csv');

        foreach ($data as $row) {
            $row = str_replace("\n", '', $row);
            var_dump($row);
            $one = explode(',', $row);
            $mobile = $one[3];
            $oldCompany = $one[4];
            $newCompany = $one[5];
            $type = $one[6];

            $user = Member::findOne(['mobile' => $mobile]);

            echo 'Doing user : ' . $mobile . "\n";

            if (empty($user)) {
                echo 'no user, continue ... ' . "\n";
                continue;
            } else {
                echo 'user: ' . $user->id . ' ' . $user->true_name . "\n";
            }

            echo 'Initializing old company ' . $oldCompany . "\n";
            $oldCompanyModel = ConsultantCompany::findOne([
                'company_name' => $oldCompany,
            ]);

//            $newCompany = '测试一个公司';
            echo 'Initializing new company ' . $newCompany . "\n";
            $newCompanyModel = ConsultantCompany::findOne([
                'company_name' => $newCompany,
            ]);

            echo 'Initializing type ' . $type . "\n";
            $typeId = 0;
            foreach (Member::$memberType2Name as $typeId => $name) {
                if ($name == $type) break;
            }

//            if (!empty($oldCompanyModel) && !empty($newCompanyModel)) {
//                echo 'Old Company Id: ' . $oldCompanyModel->id . "\n";
//                echo 'New Company Id: ' . $newCompanyModel->id . "\n";
//
//                echo 'Setting o_user_company ... ' . "\n";
//                $ret = UserCompany::updateAll([
//                    'user_company_id' => $newCompanyModel->id
//                ], [
//                    'user_company_id' => $oldCompanyModel->id,
//                    'user_id' => $user->id,
//                ]);
//
//                var_dump($ret);
//
//                echo 'Setting o_job ... ' . "\n";
//                $ret = Job::updateAll([
//                    'user_company_id' => $newCompanyModel->id
//                ], [
//                    'user_company_id' => $oldCompanyModel->id,
//                    'user_id' => $user->id,
//                ]);
//                var_dump($ret);
//
//                echo 'Setting o_orders ... ' . "\n";
//                $ret = Orders::updateAll([
//                    'company_id' => $newCompanyModel->id
//                ], [
//                    'company_id' => $oldCompanyModel->id,
//                    'user_id' => $user->id,
//                ]);
//                var_dump($ret);
//
//                echo 'Setting o_recommend ... ' . "\n";
//                $ret = Recommend::updateAll([
//                    'company_id' => $newCompanyModel->id
//                ], [
//                    'company_id' => $oldCompanyModel->id,
//                    'user_id' => $user->id,
//                ]);
//                var_dump($ret);
//
//            }

            if ($typeId == Member::MEMBER_TYPE_ADMIN) {
                echo 'Remove Consultant Company Other Admin ... ' . "\n";
                $ret = Member::updateAll([
                    'type' => Member::MEMBER_TYPE_CONSULTANT
                ], [
                    'company_id' => $newCompanyModel->id,
//                    'user_id'
                ]);
                var_dump($ret);
            }

            echo 'Setting o_member ... ' . "\n";
            $user->company_id = $newCompanyModel->id;
            $user->type = $typeId;
            $r = $user->save();
            var_dump($r);

//            echo 'Setting Consultant Company Administrator ... ' . "\n";
//            if (!empty($newCompanyModel->user_id)
//                && $newCompanyModel->user_id != $user->id
//                && $typeId == Member::MEMBER_TYPE_ADMIN
//            ) {
//                $newCompanyModel->user_id = $user->id;
//                $ret = $newCompanyModel->save();
//                var_dump($ret);
//            }


        }
    }

}