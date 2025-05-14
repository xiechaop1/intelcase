<aside class="main-sidebar control-sidebar-dark">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= Yii::$app->user->identity->name ?></p>
                <a><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <?php
        $role = Yii::$app->user->identity->role;
        $specialType = !empty($_GET['special_type'][0]) ? $_GET['special_type'][0] : '';
        ?>
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
                'items' => [
                    ['label' => 'Menus', 'options' => ['class' => 'header']],

                    [
                        'label' => '总览',
                        'icon' => 'dashboard',
                        'url' =>'/'
                    ],
                    [
//                        'label' => 'Demo管理',
                        'icon' => 'folder-open',
//                        'items' => [
//                            [
                                'label' => '野蛮B库',
                                'url' => ['/music/music'],
                                'active' => in_array($this->context->route, ['music/music', 'music/detail']),
                                'visible' => \common\helpers\AdminRole::checkRole(\common\definitions\Admin::ROLE_EDITOR)
//                            ],
//                            [
//                                'label' => '上传野蛮Demo',
//                                'url' => ['/music/edit'],
//                                'active' => in_array($this->context->route, ['music/edit', ]),
//                                'visible' => \common\helpers\AdminRole::checkRole(\common\definitions\Admin::ROLE_EDITOR)
//                            ],
//                        ]
                    ],
                    [
//                        'label' => '内购Demo管理',
                        'icon' => 'folder-open',
//                        'items' => [
//                            [
                                'label' => '野蛮A库',
                                'url' => ['/music/s_music'],
                                'active' => in_array($this->context->route, ['music/s_music', 'music/s_detail' ]),
                                'visible' => \common\helpers\AdminRole::checkRole(\common\definitions\Admin::ROLE_EDITOR)
//                            ],
//                            [
//                                'label' => '上传内购Demo',
//                                'url' => ['/music/s_edit'],
//                                'active' => in_array($this->context->route, ['music/s_edit', ]),
//                                'visible' => \common\helpers\AdminRole::checkRole(\common\definitions\Admin::ROLE_EDITOR)
//                            ],
//                        ]
                    ],
                    [
                        'icon' => 'folder-open',
                        'label' => '视频管理',
                        'url' => ['/video/video'],
                        'active' => in_array($this->context->route, ['video/video', 'video/edit' ]),
                        'visible' => \common\helpers\AdminRole::checkRole(\common\definitions\Admin::ROLE_VIDEO)
                    ],
                    [
                        'label' => '分类管理',
                        'icon' => 'cubes',
                        'url' => ['/base/categories'],
                        'active' => in_array($this->context->route, ['base/categories', 'base/category_edit']),
                        'visible' => \common\helpers\AdminRole::checkRole(\common\definitions\Admin::ROLE_PLATFORM)
                    ],
                    [
                        'label' => 'Banner管理',
                        'icon' => 'image',
                        'url' => ['/banner/banners'],
                        'active' => in_array($this->context->route, ['banner/banners', 'banner/edit']),
                        'visible' => \common\helpers\AdminRole::checkRole(\common\definitions\Admin::ROLE_PLATFORM)
                    ],
                    [
                        'label' => '订单列表',
                        'icon' => 'shopping-cart',
                        'url' => ['/order/orders'],
                        'active' => in_array($this->context->route, ['order/orders', 'order/edit', ]),
                        'visible' => \common\helpers\AdminRole::checkRole(\common\definitions\Admin::ROLE_EDITOR)
                    ],
                    [
                        'label' => '用户列表',
                        'icon' => 'users',
                        'url' => ['/user/users'],
                        'active' => in_array($this->context->route, ['user/users', 'user/edit', ]),
                        'visible' => \common\helpers\AdminRole::checkRole(\common\definitions\Admin::ROLE_EDITOR)
                    ],
                    [
                        'label' => '管理员列表',
                        'icon' => 'user',
                        'url' => ['/admin/index'],
                        'active' => in_array($this->context->route, ['admin/index', 'admin/edit', ]),
                        'visible' => \common\helpers\AdminRole::checkRole(\common\definitions\Admin::ROLE_PLATFORM)
                    ],
//                    [
//                        'label' => '分类管理',
//                        'icon' => 'folder-open',
//                        'items' => [
//                            [
//                                'label' => '分类管理',
//                                'url' => ['/base/categories'],
//                                'active' => in_array($this->context->route, ['base/categories', 'base/category_edit']),
//                                'visible' => $role == 0
//                            ],
////                            [
////                                'label' => '歌手管理',
////                                'url' => ['/base/singers'],
////                                'active' => in_array($this->context->route, ['base/singers', 'base/singer_edit' ]),
////                                'visible' => $role == 0
////                            ],
//                        ]
//
//                    ],
//                    [
//                        'label' => 'Banner管理',
//                        'icon' => 'folder-open',
//                        'items' => [
//                            [
//                                'label' => 'Banner管理',
//                                'url' => ['/banner/banners'],
//                                'active' => in_array($this->context->route, ['banner/banners', 'banner/edit']),
//                                'visible' => $role == 0
//                            ],
//
//                        ]
//
//                    ],
//                    [
//                        'label' => 'Demo管理',
//                        'icon' => 'folder-open',
//                        'items' => [
//                            [
//                                'label' => '歌曲列表',
//                                'url' => ['/music/music'],
//                                'active' => in_array($this->context->route, ['music/music', ]),
//                                'visible' => $role == 0
//                            ],
//                            [
//                                'label' => '上传歌曲',
//                                'url' => ['/music/edit'],
//                                'active' => in_array($this->context->route, ['music/edit', ]),
//                                'visible' => $role == 0
//                            ],
//                        ]
//                    ],
//                    [
//                        'label' => '订单管理',
//                        'icon' => 'folder-open',
//                        'items' => [
//                            [
//                                'label' => '订单列表',
//                                'url' => ['/order/orders'],
//                                'active' => in_array($this->context->route, ['order/orders', 'order/edit', ]),
//                                'visible' => $role == 0
//                            ],
//                        ]
//                    ],
//                    [
//                        'label' => '白名单管理',
//                        'icon' => 'folder-open',
//                        'items' => [
//                            [
//                                'label' => '用户列表',
//                                'url' => ['/user/users'],
//                                'active' => in_array($this->context->route, ['user/users', 'user/edit', ]),
//                                'visible' => $role == 0
//                            ],
//                        ]
//                    ],


                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                ],
            ]
        ) ?>

    </section>

</aside>
