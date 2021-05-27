<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) surpaimb <surpaimb@126.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Surpaimb\KuaiShou\BasicService\Url;

use Surpaimb\KuaiShou\Kernel\BaseClient;

/**
 * Class Client.
 *
 * @author surpaimb <surpaimb@126.com>
 */
class Client extends BaseClient
{
    /**
     * @var string
     */
    protected $baseUri = 'https://open.kuaishou.com/';

    /**
     * Shorten the url.
     *
     * @param string $url
     *
     * @return \Psr\Http\Message\ResponseInterface|\Surpaimb\KuaiShou\Kernel\Support\Collection|array|object|string
     *
     * @throws \Surpaimb\KuaiShou\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function shorten(string $url)
    {
        $params = [
            'action' => 'long2short',
            'long_url' => $url,
        ];

        return $this->httpPostJson('cgi-bin/shorturl', $params);
    }
}
