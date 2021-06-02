<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) surpaimb <surpaimb@126.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Surpaimb\KuaiShou\MiniProgram\SubscribeMessage;

use Surpaimb\KuaiShou\Kernel\BaseClient;
use Surpaimb\KuaiShou\Kernel\Exceptions\InvalidArgumentException;
use ReflectionClass;

/**
 * Class Client.
 *
 * @author hugo <rabbitzhang52@gmail.com>
 */
class Client extends BaseClient
{
    /**
     * {@inheritdoc}.
     */
    protected $message = [
        'to_user' => '',
        'template_id' => '',
        'page' => '',
        'data' => [],
    ];

    /**
     * {@inheritdoc}.
     */
    protected $required = ['to_user', 'template_id', 'page', 'data'];

    /**
     * Send a template message.
     *
     * @param array $data
     *
     * @return \Psr\Http\Message\ResponseInterface|\Surpaimb\KuaiShou\Kernel\Support\Collection|array|object|string
     *
     * @throws \Surpaimb\KuaiShou\Kernel\Exceptions\InvalidArgumentException
     * @throws \Surpaimb\KuaiShou\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send(array $data = [])
    {
        $params = $this->formatMessage($data);

        $this->restoreMessage();

        $params = $this->withCommonParams($params);
        
        return $this->httpPost('openapi/mp/developer/message/template/send', $params);
    }

    /**
     * @param array $data
     *
     * @return array
     *
     * @throws \Surpaimb\KuaiShou\Kernel\Exceptions\InvalidArgumentException
     */
    protected function formatMessage(array $data = [])
    {
        $params = array_merge($this->message, $data);

        foreach ($params as $key => $value) {
            if (in_array($key, $this->required, true) && empty($value) && empty($this->message[$key])) {
                throw new InvalidArgumentException(sprintf('Attribute "%s" can not be empty!', $key));
            }

            $params[$key] = empty($value) ? $this->message[$key] : $value;
        }


        foreach ($params['data'] as $key => $value) {
            if (is_array($value)) {
                if (\array_key_exists('value', $value)) {
                    $params['data'][$key] = ['value' => $value['value']];

                    continue;
                }

                if (count($value) >= 1) {
                    $value = [
                        'value' => $value[0],
//                        'color' => $value[1],// color unsupported
                    ];
                }
            } else {
                $value = [
                    'value' => strval($value),
                ];
            }

            $params['data'][$key] = $value;
        }
        // 转为JSON字符串
        $params['data'] = json_encode($params['data']);

        return $params;
    }

    /**
     * Restore message.
     */
    protected function restoreMessage()
    {
        $this->message = (new ReflectionClass(static::class))->getDefaultProperties()['message'];
    }

    /**
     * Combine templates and add them to your personal template library under your account.
     *
     * @param string      $tid
     * @param array       $kidList
     * @param string|null $sceneDesc
     *
     * @return array|\Surpaimb\KuaiShou\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws \Surpaimb\KuaiShou\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function addTemplate(string $tid, array $kidList, string $sceneDesc = null)
    {
        $sceneDesc = $sceneDesc ?? '';
        $data = \compact('tid', 'kidList', 'sceneDesc');

        return $this->httpPost('wxaapi/newtmpl/addtemplate', $data);
    }

    /**
     * Delete personal template under account.
     *
     * @param string $id
     *
     * @return array|\Surpaimb\KuaiShou\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws \Surpaimb\KuaiShou\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function deleteTemplate(string $id)
    {
        return $this->httpPost('wxaapi/newtmpl/deltemplate', ['priTmplId' => $id]);
    }

    /**
     * Get keyword list under template title.
     *
     * @param string $tid
     *
     * @return array|\Surpaimb\KuaiShou\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws \Surpaimb\KuaiShou\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getTemplateKeywords(string $tid)
    {
        return $this->httpGet('wxaapi/newtmpl/getpubtemplatekeywords', compact('tid'));
    }

    /**
     * Get the title of the public template under the category to which the account belongs.
     *
     * @param array $ids
     * @param int   $start
     * @param int   $limit
     *
     * @return array|\Surpaimb\KuaiShou\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws \Surpaimb\KuaiShou\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getTemplateTitles(array $ids, int $start = 0, int $limit = 30)
    {
        $ids = \implode(',', $ids);
        $query = \compact('ids', 'start', 'limit');

        return $this->httpGet('wxaapi/newtmpl/getpubtemplatetitles', $query);
    }

    /**
     * Get list of personal templates under the current account.
     *
     * @return array|\Surpaimb\KuaiShou\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws \Surpaimb\KuaiShou\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getTemplates()
    {
        return $this->httpGet('wxaapi/newtmpl/gettemplate');
    }

    /**
     * Get the category of the applet account.
     *
     * @return array|\Surpaimb\KuaiShou\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws \Surpaimb\KuaiShou\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getCategory()
    {
        return $this->httpGet('wxaapi/newtmpl/getcategory');
    }
}
