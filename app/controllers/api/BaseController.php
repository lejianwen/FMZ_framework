<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2016/12/19
 * Time: 10:27
 * QQ: 84855512
 */

namespace app\controllers\api;

use app\models\User;
use lib\controller;

/**
 * @SWG\Swagger(
 *    swagger="2.0",
 *     schemes={"http", "https"},
 *     host="www.taoke.com",
 *     basePath="/",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="前端 Api 文档",
 *         description="前端 Api 文档"
 *     )
 * )
 *
 * @SWG\Definition(definition="commonResponse",type="object",required={"code","msg"},
 *     @SWG\Property(property="code", type="number", default=200),
 *     @SWG\Property(property="msg", type="string")
 * )
 * @SWG\Definition(
 *     definition="Paging",
 *     @SWG\Property(property="total",type="number"),
 *     @SWG\Property(property="page_size", type="number")
 * )
 * @SWG\SecurityScheme(
 *     securityDefinition="apiUserToken",
 *     description="前台用户token",
 *     type="apiKey",
 *     in="header",
 *     name="api-token"
 * )
 */
class BaseController extends controller
{
    /** @var User||null */
    protected $user;

    protected function checkLogin()
    {
        $token = request()->header('api-token');
        if (!$token) {
            return false;
        }
        $user = User::where('token', $token)->first();
        if (!$user || strtotime($user->token_expire) < time()) {
            return false;
        }
        $this->user = $user;
        return true;
    }

    protected function jsonError($msg = '操作失败', $code = 1001)
    {
        return $this->response->json(compact('msg', 'code'));
    }

    /**
     * @param array $data
     * @param string $msg
     * @param int $code
     * @return \lib\response|mixed
     */
    protected function jsonSuccess($data = [], $msg = '', $code = 200)
    {
        return $this->response->json(compact('data', 'msg', 'code'));
    }
}
