<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) surpaimb <surpaimb@126.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Surpaimb\KuaiShou\BasicService\QrCode;

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
    protected $baseUri = 'https://api.weixin.qq.com/cgi-bin/';

    public const DAY = 86400;
    public const SCENE_MAX_VALUE = 100000;
    public const SCENE_QR_CARD = 'QR_CARD';
    public const SCENE_QR_TEMPORARY = 'QR_SCENE';
    public const SCENE_QR_TEMPORARY_STR = 'QR_STR_SCENE';
    public const SCENE_QR_FOREVER = 'QR_LIMIT_SCENE';
    public const SCENE_QR_FOREVER_STR = 'QR_LIMIT_STR_SCENE';

    /**
     * Create forever QR code.
     *
     * @param string|int $sceneValue
     *
     * @return \Psr\Http\Message\ResponseInterface|\Surpaimb\KuaiShou\Kernel\Support\Collection|array|object|string
     */
    public function forever($sceneValue)
    {
        if (is_int($sceneValue) && $sceneValue > 0 && $sceneValue < self::SCENE_MAX_VALUE) {
            $type = self::SCENE_QR_FOREVER;
            $sceneKey = 'scene_id';
        } else {
            $type = self::SCENE_QR_FOREVER_STR;
            $sceneKey = 'scene_str';
        }
        $scene = [$sceneKey => $sceneValue];

        return $this->create($type, $scene, false);
    }

    /**
     * Create temporary QR code.
     *
     * @param string|int $sceneValue
     * @param int|null   $expireSeconds
     *
     * @return \Psr\Http\Message\ResponseInterface|\Surpaimb\KuaiShou\Kernel\Support\Collection|array|object|string
     */
    public function temporary($sceneValue, $expireSeconds = null)
    {
        if (is_int($sceneValue) && $sceneValue > 0) {
            $type = self::SCENE_QR_TEMPORARY;
            $sceneKey = 'scene_id';
        } else {
            $type = self::SCENE_QR_TEMPORARY_STR;
            $sceneKey = 'scene_str';
        }
        $scene = [$sceneKey => $sceneValue];

        return $this->create($type, $scene, true, $expireSeconds);
    }

    /**
     * Return url for ticket.
     * Detail: https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1443433542 .
     *
     * @param string $ticket
     *
     * @return string
     */
    public function url($ticket)
    {
        return sprintf('https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=%s', urlencode($ticket));
    }

    /**
     * Create a QrCode.
     *
     * @param string $actionName
     * @param array  $actionInfo
     * @param bool   $temporary
     * @param int    $expireSeconds
     *
     * @return \Psr\Http\Message\ResponseInterface|\Surpaimb\KuaiShou\Kernel\Support\Collection|array|object|string
     *
     * @throws \Surpaimb\KuaiShou\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function create($actionName, $actionInfo, $temporary = true, $expireSeconds = null)
    {
        null !== $expireSeconds || $expireSeconds = 7 * self::DAY;

        $params = [
            'action_name' => $actionName,
            'action_info' => ['scene' => $actionInfo],
        ];

        if ($temporary) {
            $params['expire_seconds'] = min($expireSeconds, 30 * self::DAY);
        }

        return $this->httpPostJson('qrcode/create', $params);
    }
}
