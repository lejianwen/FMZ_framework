<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/3/22
 * Time: 11:46
 * QQ: 84855512
 */

namespace app\controllers\admin;

use app\controllers\admin\html\Form;
use app\controllers\admin\html\Grid;
use app\controllers\admin\html\SearchForm;
use app\models\User;
use Illuminate\Database\Capsule\Manager as DB;

class BaseController
{

    /**
     * @var \lib\request
     */
    protected $request;
    protected $session;

    /**
     * @var \lib\response
     */
    protected $response;

    protected $is_login;
    //类名
    protected $class_name;
    protected $model_name;
    protected $model;
    //搜索的表单
    /** @var SearchForm */
    protected $search_form;
    //关联的数据
    protected $with = [];
    /** @var Grid */
    protected $grid;

    public function __construct()
    {
        $this->session = app('session');
        $this->request = app('request');
        $this->response = app('response');
        $this->is_login = $this->checkLogin();

        $path = explode('\\', static::class);
        $class_name = array_pop($path);
        $class_name = str_replace('Controller', '', $class_name);
        $this->class_name = strtolower($class_name);

        $this->model_name = ucfirst($this->class_name);
        $this->model = 'app\\models\\' . $this->model_name;
        if (!$this->is_login) {
            if (IS_AJAX) {
                $this->jsonError(11, '登录超时！')->send();
            } else {
                $this->response->redirect('/admin/login/index', '请先登录!', 3);
            }
            exit;
        }
        app('session')->set('admin_expire', strtotime("+1 hour"));
    }

    /**验证登录
     * @return bool
     */
    public function checkLogin()
    {
        if (app('session')->get('admin_id') && app('session')->get('admin_expire') > time()) {
            return true;
        }
        return false;
    }

    public function jsonError($code = 1001, $msg = '操作失败', $data = [])
    {
        return $this->response->json(['error' => $code, 'msg' => $msg, 'data' => $data]);
    }

    public function jsonSuccess($code = 0, $msg = '操作成功', $data = [])
    {
        return $this->response->json(['error' => $code, 'msg' => $msg, 'data' => $data]);
    }

    public function index()
    {
        $this->grid();
        $this->search();
        $this->grid->addSearchForm($this->search_form);
        $this->response->with('grid', $this->grid->toHtml());
        $this->response->with(['_model' => $this->model_name, '_class' => $this->class_name]);
        if (is_file(BASE_PATH . "app/views/admin/{$this->class_name}/index.tpl")) {
            return $this->response->view("admin/{$this->class_name}/index");
        } else {
            return $this->response->view("admin/base/index");
        }

    }

    public function lists()
    {
        $offset = intval($this->request->get('start', 0));
        $length = intval($this->request->get('length', 20)) ?: 20;
        $order_str = $this->request->get('order_str', 'id');
        $order_dir = $this->request->get('order_dir', 'desc');
        $this->search();
        if (!empty($this->with)) {
            $query = $this->model::with($this->with);
        } else {
            $query = $this->model::query();
        }
        if (!$this->search_form->isEmpty()) {
            $query = $this->searchQuery($query);
        }
        $query->orderBy($order_str, $order_dir)
            ->offset($offset)
            ->limit($length);
        $data = [];
        $data['data'] = $query->get()->toArray();
        $data['recordsTotal'] = $this->model::count('id');
        $data['recordsFiltered'] = $data['recordsTotal'];
        if (!$this->search_form->isEmpty()) {
            $filter_query = $this->model::query();
            $filter_query = $this->searchQuery($filter_query);
            $data['recordsFiltered'] = $filter_query->count('id');
        }
        $data['draw'] = intval($this->request->get('draw'));
        $this->response->json($data);
    }

    public function add()
    {
        $this->response->with('inputs', $this->form()->inputs());
        $this->response->with(['_model' => $this->model_name, '_class' => $this->class_name]);
        if (is_file(BASE_PATH . "app/views/admin/{$this->class_name}/add.tpl")) {
            return $this->response->view("admin/{$this->class_name}/add");
        } else {
            return $this->response->view("admin/base/add");
        }

    }

    public function update($id)
    {
        $item = $this->model::find($id);
        if (!$id || !$item->id) {
            return $this->response->redirect("/admin/{$this->class_name}/index");
        }
        $item = $item->toArray();
        $this->response->with('inputs', $this->form($item)->inputs());
        $this->response->with(['_model' => $this->model_name, '_class' => $this->class_name]);
        $this->response->with(['item' => $item]);
        if (is_file(BASE_PATH . "app/views/admin/{$this->class_name}/update.tpl")) {
            return $this->response->view("admin/{$this->class_name}/update");
        } else {
            return $this->response->view("admin/base/update");
        }
    }

    public function add_post()
    {
        $data = $this->request->post();
        $this->upFiles($data);
        $item = $this->model::create($data);
        if (!$item->id) {
            return $this->jsonError();
        }
        return $this->jsonSuccess();
    }

    public function update_post($id)
    {
        $data = $this->request->post();
        $this->upFiles($data);
        $item = $this->model::find($id);
        if (!$item) {
            return $this->jsonError();
        }
        $item->update($data);
        return $this->jsonSuccess();
    }

    public function delete()
    {
        try {
            $id = $this->request->post('id');
            if (!$id) {
                throw new \Exception(101, '数据不存在');
            }
            $item = $this->model::find($id);
            if (!$item || !$item->id) {
                throw new \Exception(101, '数据不存在');
            }
            $item->delete();
            $this->jsonSuccess();
        } catch (\Exception $e) {
            $this->jsonError($e->getCode() ?: 102, $e->getMessage());
        }
    }

    public function changeAttr($id)
    {
        try {
            $attr = $this->request->post('attr');
            $value = $this->request->post('value');
            if (!$id) {
                throw new \Exception(101, '数据不存在');
            }
            $item = $this->model::find($id);
            if (!$item || !$item->id) {
                throw new \Exception(101, '数据不存在');
            }
            $item->setAttribute($attr, $value);
            $item->save();
            $this->jsonSuccess();
        } catch (\Exception $e) {
            $this->jsonError($e->getCode() ?: 102, $e->getMessage());
        }
    }

    protected function form($item = null)
    {
        return new Form($item);
    }

    protected function grid()
    {
        $this->grid = new Grid();
    }

    protected function upFiles(&$data)
    {
        $files = $this->request->file();
        if (!empty($files)) {
            foreach ($files as $key => $file) {
                if (is_array($file)) {
                    $items = [];
                    foreach ($file as $_file) {
                        $path = '/uploads/' . date('Ymd') . '/';
                        $filename = $_file->moveUpFile(WEB_ROOT . $path);
                        $items[] = $path . $filename;
                    }
                    $data[$key] = implode(',', $items);
                } else {
                    $path = '/uploads/' . date('Ymd') . '/';
                    $filename = $file->moveUpFile(WEB_ROOT . $path);
                    $data[$key] = $path . $filename;
                }
            }
        }
    }

    protected function search()
    {
        $form = new SearchForm();
        $this->search_form = $form;
        $this->search_form->eq('ID', 'id');
    }

    protected function searchQuery($query)
    {
        $search = $this->request->get('search');
        if (empty($search)) {
            return $query;
        }
        $array = [];
        foreach ($search as $item) {
            $array[$item['name']] = $item['value'];
        }
        if (empty($array)) {
            return $query;
        }
        return $this->search_form->buildQuery($query, $array);
    }
}