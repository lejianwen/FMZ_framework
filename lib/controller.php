<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/2/14
 * Time: 9:50
 * QQ: 84855512
 */

namespace lib;

class controller
{
    /**
     * @var \lib\request
     */
    protected $request;
    /**
     * @var \lib\response
     */
    protected $response;

    public function __construct()
    {
        $this->request = request();
        $this->response = response();
    }

}
