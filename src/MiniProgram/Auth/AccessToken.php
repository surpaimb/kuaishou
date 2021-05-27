<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) surpaimb <surpaimb@126.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Surpaimb\KuaiShou\MiniProgram\Auth;

use Surpaimb\KuaiShou\Kernel\AccessToken as BaseAccessToken;

/**
 * Class AccessToken.
 *
 * @author mingyoung <mingyoungcheung@gmail.com>
 */
class AccessToken extends BaseAccessToken
{
    /**
     * @var string
     */
    protected $endpointToGetToken = 'https://open.kuaishou.com/oauth2/access_token';

    /**
     * {@inheritdoc}
     */
    protected function getCredentials(): array
    {
        return [
            'grant_type' => 'client_credentials',
            'app_id' => $this->app['config']['app_id'],
            'app_secret' => $this->app['config']['secret'],
        ];
    }
}
