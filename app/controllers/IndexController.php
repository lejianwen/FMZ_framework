<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2016/12/6
 * Time: 18:11
 * QQ: 84855512
 */

namespace app\controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends BaseController
{
    public function index()
    {
        $this->response
            ->with(['framework' => 'FMZ_framework'])
            ->view('index/index');
    }

    public function test()
    {
//        var_dump($this->request->getClientIp());
//        var_dump($this->request->file());
//        echo '==================================<br>';
        $this->request = Request::createFromGlobals();
//        var_dump($this->request->query);
//        var_dump($this->request->request);
//        var_dump($this->request->files->get('a')[0][0]->move(WEB_ROOT));
        $response = new Response();
        $response->prepare($this->request);
        $this->response
            ->with(['framework' => 'FMZ_framework'])
            ->view('index/index');
        /** @var \lib\view\smarty $view */
        $view = app('view');
        $response->setContent($view->with(['framework' => 'FMZ_framework'])->fetch())->send();
        exit;
    }

    public function testSy()
    {
    }

    public function middle($middle_result)
    {
        echo $middle_result;
    }
}
