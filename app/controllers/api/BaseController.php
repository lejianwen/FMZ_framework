<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2016/12/19
 * Time: 10:27
 * QQ: 84855512
 */

namespace app\controllers\api;

use app\controllers\jsonResponse;
use lib\controller;

/**
 * @OA\OpenApi(
 *    openapi="3.0.0",
 *     @OA\Info(
 *         version="1.0.0",
 *         title="前端 Api 文档",
 *         description="前端 Api 文档"
 *     )
 * )
 *
 * @OA\Schema(schema="commonResponse",type="object",required={"code","msg"},
 *     @OA\Property(property="code", type="number", default=200),
 *     @OA\Property(property="msg", type="string")
 * )
 * @OA\Schema(
 *     schema="Paging",
 *     @OA\Property(property="total",type="number"),
 *     @OA\Property(property="page_size", type="number")
 * )
 * @OA\SecurityScheme(
 *     securityScheme="apiUserToken",
 *     description="前台用户token",
 *     type="apiKey",
 *     in="header",
 *     name="api-token"
 * )
 */

/**
 * @OA\Get(
 *   tags={"OpenApi"},
 *   path="/swagger-api",
 *   summary="json",
 *   @OA\Response(
 *     response=200,
 *     description="OpenApi json",
 *     @OA\JsonContent(required={"data","code", "msg"},
 *        @OA\Property(property="data", type="string", description="token"),
 *        allOf={@OA\Schema(ref="#/components/schemas/commonResponse")},
 *     ),
 *   )
 * )
 */
class BaseController extends controller
{
    use jsonResponse;
}
