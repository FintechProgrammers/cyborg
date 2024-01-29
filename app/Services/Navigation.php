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
                'hasPermission' => auth()->user()->can('manage user') ?? false
            ],
            (object) [
                'name'  => 'Administrators',
                'route' => 'admin.administrators.index',
                'icon'  => 'users',
                'hasPermission' => auth()->user()->can('admin user') ?? false
            ],
            (object) [
                'name'      => 'Media',
                'icon'      => 'grid',
                'routes'    => ['admin.news.index', 'admin.banner.index'],
                'hasPermission' => auth()->user()->can('manage media') ?? false,
                'subMenu'   => (object) [
                    (object) [
                        'name'  => 'Banner',
                        'route' => 'admin.banner.index',
                        'hasPermission' => auth()->user()->can('manage banner') ?? false
                    ],
                    (object) [
                        'name'  => 'News',
                        'route' => 'admin.news.index',
                        'hasPermission' => auth()->user()->can('manage news') ?? false
                    ]
                ],
            ],
            (object) [
                'name'  => 'Bot',
                'route' => 'admin.bot.index',
                'icon'  => 'cpu',
                'hasPermission' => auth()->user()->can('manage strategy') ?? false
            ],
            (object) [
                'name'      => 'Finance',
                'icon'      => 'align-justify',
                'routes'    => ['admin.transactions.index', 'admin.banner.index'],
                'hasPermission' => auth()->user()->can('manage finance') ?? false,
                'subMenu'   => (object) [
                    (object) [
                        'name'  => 'Transactions',
                        'route' => 'admin.transactions.index',
                        'hasPermission' => auth()->user()->can('manage transactions') ?? false
                    ],
                    (object) [
                        'name'  => 'Pending Withdrawals',
                        'route' => 'admin.transactions.withdrawals.index',
                        'hasPermission' => auth()->user()->can('manage withdrawal') ?? false
                    ]
                ],
            ],
            (object) [
                'name'  => 'Trades',
                'route' => 'admin.trades.index',
                'icon'  => 'bar-chart-2',
                'hasPermission' => auth()->user()->can('manage trades') ?? false
            ],
            (object) [
                'name'  => 'Support',
                'route' => 'admin.supports.index',
                'icon'  => 'headphones',
                'hasPermission' => auth()->user()->can('manage ticket') ?? false
            ],
            (object) [
                'name'  => 'Roles and Permissions',
                'route' => 'admin.roles.index',
                'icon'  => 'anchor',
                'hasPermission' => auth()->user()->can('roles permissions') ?? false
            ],
            (object) [
                'name'  => 'Settings',
                'route' => 'admin.settings.index',
                'icon'  => 'settings',
                'hasPermission' => auth()->user()->can('manage settings') ?? false
            ],
        ];
    }
}
