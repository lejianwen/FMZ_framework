<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/9/7
 * Time: 19:55
 */

namespace app\controllers\admin;

use app\models\Admin;

class AdminController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->checkAuth()) {
            if (IS_AJAX) {
                $this->jsonError(11, '没有权限！')->send();
            } else {
                $this->response->redirect('/admin/index', '没有权限!', 3);
            }
            exit;
        }
    }

    protected function checkAuth()
    {
        if (app('session')->get('admin_group_id') != 1) {
            return false;
        }
        return true;
    }

    public function index()
    {
        $admins = Admin::all()->toArray();
        $this->response->with(['admins' => $admins])->view('admin/admin/index');
    }

    public function add()
    {
        $this->response->view('admin/admin/add');
    }

    public function add_post()
    {
        try {
            $data = $this->request->post();
            if (!$data['username']) {
                throw new \Exception('用户名不能为空！', 1001);
            }
            if (!$data['password']) {
                throw new \Exception('密码不能为空！', 1001);
            }
            if (!$data['group_id']) {
                throw new \Exception('请选择用户组！', 1001);
            }
            if (Admin::query()->where(['username' => $data['username']])->count() > 0) {
                throw new \Exception('用户名已存在！', 1001);
            }

            $data['password'] = md5($data['password']);
            $new = Admin::create($data);
            if (!$new || !$new->id) {
                throw new \Exception('添加失败！', 101);
            }
            $this->jsonSuccess();
        } catch (\Exception $e) {
            $this->jsonError($e->getCode() ?: 102, $e->getMessage());
        }
    }

    public function update($id)
    {
        $admin = Admin::find($id);
        if (!$id || !$admin->id) {
            return $this->response->redirect('/admin/admin/index');
        }
        return $this->response->with(['admin' => $admin])->view('admin/admin/update');
    }

    public function update_post($id)
    {
        try {
            $admin = Admin::find($id);
            if (!$id || !$admin->id) {
                throw new \Exception(101, '管理员不存在');
            }
            $data = $this->request->post();
            if (!$data['username']) {
                throw new \Exception('用户名不能为空！', 1001);
            }
            if (!$data['password']) {
                throw new \Exception('密码不能为空！', 1001);
            }
            if (!$data['group_id']) {
                throw new \Exception('请选择用户组！', 1001);
            }
            if ($data['password'] == '******') {
                unset($data['password']);
            } else {
                $data['password'] = md5($data['password']);
            }
            $admin->update($data);
            $this->jsonSuccess();
        } catch (\Exception $e) {
            $this->jsonError($e->getCode() ?: 102, $e->getMessage());
        }
    }

    public function delete()
    {
        try {
            $id = $this->request->post('id');
            if (!$id) {
                throw new \Exception(101, '管理员不存在');
            }
            $admin = Admin::find($id);
            if (!$admin || !$admin->id) {
                throw new \Exception(101, '管理员不存在');
            }
            $admin->delete();
            $this->jsonSuccess();
        } catch (\Exception $e) {
            $this->jsonError($e->getCode() ?: 102, $e->getMessage());
        }
    }
}