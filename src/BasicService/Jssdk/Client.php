<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) surpaimb <surpaimb@126.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Surpaimb\KuaiShou\BasicService\Jssdk;

use Surpaimb\KuaiShou\Kernel\BaseClient;
use Surpaimb\KuaiShou\Kernel\Exceptions\RuntimeException;
use Surpaimb\KuaiShou\Kernel\Support;
use Surpaimb\KuaiShou\Kernel\Traits\InteractsWithCache;

/**
 * Class Client.
 *
 * @author surpaimb <surpaimb@126.com>
 */
class Client extends BaseClient
{
    use InteractsWithCache;

    /**
     * @var string
     */
    protected $ticketEndpoint = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket';

    /**
     * Current URI.
     *
     * @var string
     */
    protected $url;

    /**
     * Get config json for jsapi.
     *
     * @param array       $jsApiList
     * @param bool        $debug
     * @param bool        $beta
     * @param bool        $json
     * @param array       $openTagList
     * @param string|null $url
     *
     * @return array|string
     *
     * @throws \Surpaimb\KuaiShou\Kernel\Exceptions\InvalidArgumentException
     * @throws \Surpaimb\KuaiShou\Kernel\Exceptions\InvalidConfigException
     * @throws \Surpaimb\KuaiShou\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function buildConfig(array $jsApiList, bool $debug = false, bool $beta = false, bool $json = true, array $openTagList = [], string $url = null)
    {
        $config = array_merge(compact('debug', 'beta', 'jsApiList', 'openTagList'), $this->configSignature($url));

        return $json ? json_encode($config) : $config;
    }

    /**
     * Return jsapi config as a PHP array.
     *
     * @param array       $apis
     * @param bool        $debug
     * @param bool        $beta
     * @param array       $openTagList
     * @param string|null $url
     *
     * @return array|string
     * @throws \Surpaimb\KuaiShou\Kernel\Exceptions\InvalidArgumentException
     * @throws \Surpaimb\KuaiShou\Kernel\Exceptions\InvalidConfigException
     * @throws \Surpaimb\KuaiShou\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getConfigArray(array $apis, bool $debug = false, bool $beta = false, array $openTagList = [], string $url = null)
    {
        return $this->buildConfig($apis, $debug, $beta, false, $openTagList, $url);
    }

    /**
     * Get js ticket.
     *
     * @param bool   $refresh
     * @param string $type
     *
     * @return array
     *
     * @throws \Surpaimb\KuaiShou\Kernel\Exceptions\InvalidArgumentException
     * @throws \Surpaimb\KuaiShou\Kernel\Exceptions\InvalidConfigException
     * @throws \Surpaimb\KuaiShou\Kernel\Exceptions\RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getTicket(bool $refresh = false, string $type = 'jsapi'): array
    {
        $cacheKey = sprintf('easywechat.basic_service.jssdk.ticket.%s.%s', $type, $this->getAppId());

        if (!$refresh && $this->getCache()->has($cacheKey)) {
            return $this->getCache()->get($cacheKey);
        }

        /** @var array<string, mixed> $result */
        $result = $this->castResponseToType(
            $this->requestRaw($this->ticketEndpoint, 'GET', ['query' => ['type' => $type]]),
            'array'
        );

        $this->getCache()->set($cacheKey, $result, $result['expires_in'] - 500);

        if (!$this->getCache()->has($cacheKey)) {
            throw new RuntimeException('Failed to cache jssdk ticket.');
        }

        return $result;
    }

    /**
     * Build signature.
     *
     * @param string|null $url
     * @param string|null $nonce
     * @param null        $timestamp
     *
     * @return array
     *
     * @throws \Surpaimb\KuaiShou\Kernel\Exceptions\InvalidArgumentException
     * @throws \Surpaimb\KuaiShou\Kernel\Exceptions\InvalidConfigException
     * @throws \Surpaimb\KuaiShou\Kernel\Exceptions\RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    protected function configSignature(string $url = null, string $nonce = null, $timestamp = null): array
    {
        $url = $url ?: $this->getUrl();
        $nonce = $nonce ?: Support\Str::quickRandom(10);
        $timestamp = $timestamp ?: time();

        return [
            'appId' => $this->getAppId(),
            'nonceStr' => $nonce,
            'timestamp' => $timestamp,
            'url' => $url,
            'signature' => $this->getTicketSignature($this->getTicket()['ticket'], $nonce, $timestamp, $url),
        ];
    }

    /**
     * Sign the params.
     *
     * @param string $ticket
     * @param string $nonce
     * @param int    $timestamp
     * @param string $url
     *
     * @return string
     */
    public function getTicketSignature($ticket, $nonce, $timestamp, $url): string
    {
        return sha1(sprintf('jsapi_ticket=%s&noncestr=%s&timestamp=%s&url=%s', $ticket, $nonce, $timestamp, $url));
    }

    /**
     * @return string
     */
    public function dictionaryOrderSignature()
    {
        $params = func_get_args();

        sort($params, SORT_STRING);

        return sha1(implode('', $params));
    }

    /**
     * Set current url.
     *
     * @param string $url
     *
     * @return $this
     */
    public function setUrl(string $url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get current url.
     *
     * @return string
     */
    public function getUrl(): string
    {
        if ($this->url) {
            return $this->url;
        }

        return Support\current_url();
    }

    /**
     * @return string
     */
    protected function getAppId()
    {
        return $this->app['config']->get('app_id');
    }

    /**
     * @return string
     */
    protected function getAgentId()
    {
        return $this->app['config']->get('agent_id');
    }
}
