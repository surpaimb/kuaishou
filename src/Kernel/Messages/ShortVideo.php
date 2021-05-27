<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) surpaimb <surpaimb@126.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Surpaimb\KuaiShou\Kernel\Messages;

/**
 * Class ShortVideo.
 *
 * @property string $title
 * @property string $media_id
 * @property string $description
 * @property string $thumb_media_id
 */
class ShortVideo extends Video
{
    /**
     * Messages type.
     *
     * @var string
     */
    protected $type = 'shortvideo';
}
