<?php
/**
 * Created by PhpStorm.
 * User: Lejianwen
 * QQ: 84855512
 * Date: 2019/9/30
 * Time: 21:05
 */

namespace app\controllers\api\admin;

use app\models\Admin;
use lib\controller;

class BaseController extends controller
{
    protected $is_login;
    /** @var Admin */
    protected $admin;
    protected $except;
    protected $model_name;
    /** @var \Illuminate\Database\Eloquent\Model */
    protected $model;
    protected $filters;
    protected $with = [];

    protected $form_ignore = [];    //新增,修改是忽略的字段

    public function __construct()
    {
        /** @var \lib\request */
        $this->request = request();
        $this->response = response();
        $this->is_login = $this->checkLogin();

        if (!$this->is_login) {
            $this->jsonError(403, '登录超时！')->send();
            exit;
        }

        $path = explode('\\', static::class);
        $class_name = array_pop($path);
        $class_name = str_replace('Controller', '', $class_name);
        $this->model_name = ucfirst($class_name);
        $this->model = 'app\\models\\' . $this->model_name;
    }

    /**验证登录
     * @return bool
     */
    public function checkLogin()
    {
        $token = request()->header('admin-token');
        if (!$token) {
            return false;
        }
        $admin = Admin::where('token', $token)->first();
        if (!$admin || strtotime($admin->token_expire_time) < time()) {
            return false;
        }
        $this->admin = $admin;
        return true;
    }


    public function jsonError($code = 1001, $msg = '操作失败')
    {
        return $this->response->json(['code' => $code, 'msg' => $msg]);
    }

    public function jsonSuccess($data = [], $code = 200, $msg = '操作成功')
    {
        return $this->response->json(['code' => $code, 'msg' => $msg, 'data' => $data]);
    }

    /**
     * @param $query
     * @param array $filters
     * @return mixed
     * @author lejianwen
     */
    protected function buildQuery($query, $filters = [])
    {
        if (empty($filters)) {
            return;
        }
        if (!empty($this->filters)) {
            foreach ($this->filters as $attr => $op) {
                if (!is_numeric($attr)) {
                    if (isset($filters[$attr])) {
                        if ($op == 'like') {
                            $filters[$attr] && $query->where($attr, $op, "%{$filters[$attr]}%");
                        } elseif ($op == 'between') {
                            is_array($filters[$attr]) && $query->whereBetween($attr, $filters[$attr]);
                        } else {
                            $query->where($attr, $op, $filters[$attr]);
                        }
                    }
                } else {
                    if (isset($filters[$op]) && $filters[$op]) {
                        $query->where($op, $filters[$op]);
                    }
                }
            }
        }
        if (isset($filters['_order']) && ($orders = json_decode($filters['_order'], true))) {
            foreach ($orders as $prop => $order) {
                if ($order === 'descending') {
                    $query->orderBy($prop, 'desc');
                } elseif ($order === 'ascending') {
                    $query->orderBy($prop, 'asc');
                }
            }
        }

        return;
    }

    public function index()
    {
        $page = $this->request->get('page', 1);
        $page_size = $this->request->get('page_size', 10);
        $query = $this->model::query();
        if (!empty($this->with)) {
            $query->with($this->with);
        }
        $this->buildQuery($query, $this->request->get());
        $total = $query->count();
        $data = $query->forPage($page, $page_size)->get();
        $this->jsonSuccess(['list' => $data, 'total' => $total]);
    }

    public function detail()
    {
        $id = $this->request->get('id');
        if (!$id) {
            return $this->jsonError();
        }
        $model = $this->model::find($id);
        if (!$model) {
            return $this->jsonError();
        }
        return $this->jsonSuccess($model->toArray());
    }

    public function update()
    {
        $post_data = $this->getPostData();
        /** @var \Illuminate\Database\Eloquent\Model $model */
        $model = $this->model::find($post_data['id']);
        if (!$model) {
            return $this->jsonError();
        }
        $model->update($post_data);
        $this->afterUpdate($model);
        return $this->jsonSuccess();
    }

    public function delete()
    {
        $id = $this->request->post('id');
        if (!$id) {
            return $this->jsonError();
        }
        /** @var \Illuminate\Database\Eloquent\Model $model */
        $model = $this->model::find($id);
        if (!$model) {
            return $this->jsonError();
        }
        $model->delete();
        return $this->jsonSuccess();
    }

    public function create()
    {
        $post_data = $this->getPostData();
        /** @var \Illuminate\Database\Eloquent\Model $model */
        $model = $this->model::create($post_data);
        if ($model && $model->id) {
            return $this->jsonSuccess();
        }
        $this->afterUpdate($model);
        return $this->jsonError();
    }

    protected function getPostData()
    {
        $post_data = request()->post();
        if (!empty($this->form_ignore)) {
            foreach ($this->form_ignore as $item) {
                unset($post_data[$item]);
            }
        }
        return $post_data;
    }

    protected function afterUpdate($item)
    {

    }
}
