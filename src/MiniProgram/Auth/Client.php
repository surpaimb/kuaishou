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

use Surpaimb\KuaiShou\Kernel\BaseClient;

/**
 * Class Auth.
 *
 * @author mingyoung <mingyoungcheung@gmail.com>
 */
class Client extends BaseClient
{
    /**
     * Get session info by code.
     *
     * @param string $code
     *
     * @return \Psr\Http\Message\ResponseInterface|\Surpaimb\KuaiShou\Kernel\Support\Collection|array|object|string
     *
     * @throws \Surpaimb\KuaiShou\Kernel\Exceptions\InvalidConfigException
     */
    public function session(string $code)
    {
        $params = [
            'app_id' => $this->app['config']['app_id'],
            'app_secret' => $this->app['config']['secret'],
        ];
        $params['js_code'] = $code;
    
        return $this->httpPost('oauth2/mp/code2session', $params);
    }
}
