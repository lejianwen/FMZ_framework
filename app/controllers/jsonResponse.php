<?php


namespace app\controllers;


trait jsonResponse
{
    /**
     * @param string $msg
     * @param int $code
     * @param array $data
     * @return \lib\response
     */
    protected function jsonError($msg = '操作失败', $code = 1001, $data = [])
    {
        return response()->json(compact('msg', 'code', 'data'));
    }

    /**
     * @param array $data
     * @param string $msg
     * @param int $code
     * @return \lib\response|mixed
     */
    protected function jsonSuccess($data = [], $msg = '', $code = 200)
    {
        return response()->json(compact('data', 'msg', 'code'));
    }

}