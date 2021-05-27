<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) surpaimb <surpaimb@126.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Surpaimb\KuaiShou\MiniProgram;

use Surpaimb\KuaiShou\BasicService;
use Surpaimb\KuaiShou\Kernel\ServiceContainer;

/**
 * Class Application.
 *
 * @author mingyoung <mingyoungcheung@gmail.com>
 *
 * @property \Surpaimb\KuaiShou\MiniProgram\Auth\AccessToken           $access_token
 * @property \Surpaimb\KuaiShou\MiniProgram\Auth\Client                $auth
 * @property \Surpaimb\KuaiShou\OfficialAccount\Server\Guard           $server
 * @property \Surpaimb\KuaiShou\MiniProgram\Encryptor                  $encryptor
 * @property \Surpaimb\KuaiShou\MiniProgram\TemplateMessage\Client     $template_message
 * @property \Surpaimb\KuaiShou\MiniProgram\Plugin\Client              $plugin
 * @property \Surpaimb\KuaiShou\MiniProgram\Plugin\DevClient           $plugin_dev
 * @property \Surpaimb\KuaiShou\MiniProgram\SubscribeMessage\Client    $subscribe_message
 */
class Application extends ServiceContainer
{
    /**
     * @var array
     */
    protected $providers = [
        Auth\ServiceProvider::class,
        Server\ServiceProvider::class,
        TemplateMessage\ServiceProvider::class,
        Plugin\ServiceProvider::class,
        Base\ServiceProvider::class,
        // Base services
        BasicService\Media\ServiceProvider::class,
        BasicService\ContentSecurity\ServiceProvider::class,
        // for mine
        SubscribeMessage\ServiceProvider::class,
        Payment\ServiceProvider::class,
        Content\ServiceProvider::class,
        QrCode\ServiceProvider::class,
    ];

    /**
     * Handle dynamic calls.
     *
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        return $this->base->$method(...$args);
    }
}
