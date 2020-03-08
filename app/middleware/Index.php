<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/4/25
 * Time: 18:20
 * QQ: 84855512
 */

namespace app\middleware;

use Ljw\Route\Middleware;

class Index implements Middleware
{
    public function handle($params, $next)
    {
        $next();
    }
}
