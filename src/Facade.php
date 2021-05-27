<?php

/*
 * This file is part of the surpaimb/kuaishou.
 *
 * (c) alim <tuple@youshui.ren>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Surpaimb\KuaiShou;

use Illuminate\Support\Facades\Facade as LaravelFacade;

/**
 * Class Facade.
 *
 * @author alim <tuple@youshui.ren>
 */
class Facade extends LaravelFacade
{
    /**
     * 默认为 Server.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'kuaishou.mini_program';
    }

    /**
     * @return \Surpaimb\KuaiShou\MiniProgram\Application
     */
    public static function miniProgram($name = '')
    {
        return $name ? app('kuaishou.mini_program.'.$name) : app('kuaishou.mini_program');
    }
}
