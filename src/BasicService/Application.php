<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) surpaimb <surpaimb@126.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Surpaimb\KuaiShou\BasicService;

use Surpaimb\KuaiShou\Kernel\ServiceContainer;

/**
 * Class Application.
 *
 * @author surpaimb <surpaimb@126.com>
 *
 * @property \Surpaimb\KuaiShou\BasicService\Jssdk\Client           $jssdk
 * @property \Surpaimb\KuaiShou\BasicService\Media\Client           $media
 * @property \Surpaimb\KuaiShou\BasicService\QrCode\Client          $qrcode
 * @property \Surpaimb\KuaiShou\BasicService\Url\Client             $url
 * @property \Surpaimb\KuaiShou\BasicService\ContentSecurity\Client $content_security
 */
class Application extends ServiceContainer
{
    /**
     * @var array
     */
    protected $providers = [
        Jssdk\ServiceProvider::class,
        QrCode\ServiceProvider::class,
        Media\ServiceProvider::class,
        Url\ServiceProvider::class,
        ContentSecurity\ServiceProvider::class,
    ];
}
