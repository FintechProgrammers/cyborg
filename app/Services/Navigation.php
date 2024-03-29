<?php

namespace App\Services;


class Navigation
{
    public static function adminRoutes()
    {
        return [
            (object) [
                'name'  => 'Dashboard',
                'route' => 'admin.dashboard.index',
                'icon'  => 'home',
                'hasPermission' => true
            ],
            (object) [
                'name'  => 'Users',
                'route' => 'admin.users.index',
                'icon'  => 'users',
                'hasPermission' => true
            ],
            (object) [
                'name'      => 'Media',
                'icon'      => 'grid',
                'routes'    => ['admin.news.index', 'admin.banner.index'],
                'hasPermission' => true,
                'subMenu'   => (object) [
                    (object) [
                        'name'  => 'Banner',
                        'route' => 'admin.banner.index',
                        'hasPermission' => true
                    ],
                    (object) [
                        'name'  => 'News',
                        'route' => 'admin.news.index',
                        'hasPermission' => true
                    ]
                ],
            ],
            (object) [
                'name'  => 'Bot',
                'route' => 'admin.bot.index',
                'icon'  => 'cpu',
                'hasPermission' => true
            ],
            (object) [
                'name'      => 'Finance',
                'icon'      => 'align-justify',
                'routes'    => ['admin.transactions.index', 'admin.banner.index'],
                'hasPermission' => true,
                'subMenu'   => (object) [
                    (object) [
                        'name'  => 'Transactions',
                        'route' => 'admin.transactions.index',
                        'hasPermission' => true
                    ],
                    (object) [
                        'name'  => 'Pending Withdrawals',
                        'route' => 'admin.transactions.withdrawals.index',
                        'hasPermission' => true
                    ]
                ],
            ],
            (object) [
                'name'  => 'Trades',
                'route' => 'admin.trades.index',
                'icon'  => 'bar-chart-2',
                'hasPermission' => true
            ],
            (object) [
                'name'  => 'Support',
                'route' => 'admin.supports.index',
                'icon'  => 'headphones',
                'hasPermission' => true
            ],
            (object) [
                'name'  => 'Roles and Permissions',
                'route' => 'admin.roles.index',
                'icon'  => 'anchor',
                'hasPermission' => true
            ],
            (object) [
                'name'  => 'Settings',
                'route' => 'admin.settings.index',
                'icon'  => 'settings',
                'hasPermission' => true
            ],
        ];
    }
}
