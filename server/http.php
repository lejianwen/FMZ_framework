<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * QQ: 84855512
 */

namespace server;

use lib\response;

class http
{
    protected $setting = [];
    protected $server = null;

    public function __construct()
    {
        $this->setting = require_once BASE_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'swoole.php';
    }

    public function run()
    {
        $setting = [
            'worker_num' => $this->setting['worker_num'],
            'task_worker_num' => $this->setting['task_worker_num'],
            'task_ipc_mode ' => $this->setting['task_ipc_mode'],
            'task_max_request' => $this->setting['task_max_request'],
            'daemonize' => $this->setting['daemonize'],
            'max_request' => $this->setting['max_request'],
            'dispatch_mode' => $this->setting['dispatch_mode'],
            'log_file' => $this->setting['log_file'],
            'heartbeat_check_interval' => $this->setting['heartbeat_check_interval'],
            'heartbeat_idle_time' => $this->setting['heartbeat_idle_time'],
            'user' => $this->setting['user'],
            'group' => $this->setting['group']
        ];

        if ($this->setting['open_ssl']) {
            $setting['ssl_cert_file'] = $this->setting['ssl_cert_file'];
            $setting['ssl_key_file'] = $this->setting['ssl_key_file'];
            $this->server = new \swoole_http_server($this->setting['host'], $this->setting['port'], SWOOLE_PROCESS,
                SWOOLE_SOCK_TCP | SWOOLE_SSL);
        } else {
            $this->server = new \swoole_http_server($this->setting['host'], $this->setting['port']);
        }
        $this->server->set($setting);
        $this->server->on('Start', [$this, 'onStart']);
//        $this->server->on('Connect', [$this, 'onConnect']);
        $this->server->on('WorkerStart', [$this, 'onWorkerStart']);
        $this->server->on('ManagerStart', [$this, 'onManagerStart']);
        $this->server->on('WorkerStop', [$this, 'onWorkerStop']);
//        $this->server->on('Receive', [$this, 'onReceive']);
        $this->server->on('Task', [$this, 'onTask']);
        $this->server->on('Finish', [$this, 'onFinish']);
        $this->server->on('Shutdown', [$this, 'onShutdown']);
        //http
        $this->server->on('Request', [$this, 'onRequest']);
        //websocket
//        $this->server->on('open', [$this, 'onOpen']);
//        $this->server->on('message', [$this, 'onMessage']);
//        $this->server->on('close', [$this, 'onClose']);
        $this->server->start();
    }

    public function onRequest(\Swoole\Http\Request $request, \Swoole\Http\Response $response)
    {
        request()->reset();
        response()->reset();
        $server = $request->server ?? [];
        $headers = $request->header ?? [];
        $_GET = $request->get ?? [];
        $_POST = $request->post ?? [];
        $_FILES = $request->files ?? [];
        foreach ($headers as $k => $v) {
            $key = 'http_' . str_replace('-', '_', $k);
            $server[$key] = $v;
        }
        foreach ($server as $k => $v) {
            $_SERVER[strtoupper($k)] = $v;
            putenv(strtoupper($k) . "={$v}");
        }

        ob_start();
        var_dump($_FILES);
        try {
            // 路由
            \Ljw\Route\Route::run();
            //响应
            response()->send();

        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL . $e->getTraceAsString();
        }
        $content = ob_get_contents();
        ob_end_clean();
        $response->end($content);
        //日志
        \bootstrap::log();
    }

    /**
     * 设置swoole进程名称
     * @param string $name swoole进程名称
     */
    private function setProcessName($name)
    {
        if (function_exists('cli_set_process_title')) {
            cli_set_process_title($name);
        } else {
            if (function_exists('swoole_set_process_name')) {
                swoole_set_process_name($name);
            } else {
                trigger_error(__METHOD__ . " failed. require cli_set_process_title or swoole_set_process_name.");
            }
        }
    }

    /**
     * Server启动在主进程的主线程回调此函数
     * @param $serv
     */
    public function onStart($server)
    {
        if (!$this->setting['daemonize']) {
            echo 'Date:' . date('Y-m-d H:i:s') . ' server master worker start' . PHP_EOL;
        }
        $this->setProcessName($this->setting['process_name'] . '-master');
        //记录进程id,脚本实现自动重启
        $pid = "{$server->master_pid}\n{$server->manager_pid}";
        file_put_contents(TASK_PID_PATH, $pid);
    }

    /**
     * worker start 加载业务脚本常驻内存
     * @param $server
     * @param $workerId
     */
    public function onWorkerStart($server, $workerId)
    {
        if ($workerId >= $this->setting['worker_num']) {
            $this->setProcessName($this->setting['process_name'] . '-task');
        } else {
            $this->setProcessName($this->setting['process_name'] . '-event');
        }
        // 引入入口文件
        require_once __DIR__ . '/../vendor/autoload.php';
        //将server放到bootstrap中方便调用
        \bootstrap::$server = $server;
        \bootstrap::init();

    }

    /**
     * 监听连接进入事件
     * @param $serv
     * @param $fd
     */
    public function onConnect($server, $fd)
    {
        if (!$this->setting['daemonize']) {
            echo 'Date:' . date('Y-m-d H:i:s') . " connect[" . $fd . "]\n";
        }
    }

    /**
     * worker 进程停止
     * @param $server
     * @param $workerId
     */
    public function onWorkerStop($server, $workerId)
    {
        if (!$this->setting['daemonize']) {
            echo 'Date:' . date('Y-m-d H:i:s') . " server {$this->setting['process_name']}  worker:{$workerId} shutdown\n";
        }
    }

    /**
     * 当管理进程启动时调用
     * @param $server
     */
    public function onManagerStart($server)
    {
        if (!$this->setting['daemonize']) {
            echo 'Date:' . date('Y-m-d H:i:s') . " server manager worker start\n";
        }
        $this->setProcessName($this->setting['process_name'] . '-manager');
    }

    /**
     * 此事件在Server结束时发生
     */
    public function onShutdown($server)
    {
        if (file_exists(TASK_PID_PATH)) {
            unlink(TASK_PID_PATH);
        }
        if (!$this->setting['daemonize']) {
            echo 'Date:' . date('Y-m-d H:i:s') . ' server shutdown !' . PHP_EOL;
        }
    }

    /**
     * 监听数据发送事件
     * @param $serv
     * @param $fd
     * @param $from_id
     * @param $data
     */
    public function onReceive($server, $fd, $from_id, $data)
    {
    }

    /**
     * 监听连接Task事件
     * @param $server
     * @param $task_id
     * @param $from_id
     * @param $data
     */
    public function onTask(\swoole_http_server $server, $task_id, $from_id, $data)
    {
        if (!$this->setting['daemonize']) {
            echo "Task {$task_id} start\n\n";
            $d_data = json_encode($data);
            echo "Result: {$d_data}\n\n";
        }
        //实现任务方法
        \bootstrap::task($data);
        //2.0可以直接return
        $server->finish($data);
    }

    /**
     * 监听连接Finish事件
     * @param $serv
     * @param $task_id
     * @param $data
     */
    public function onFinish($server, $task_id, $data)
    {
        if (!$this->setting['daemonize']) {
            echo "Task {$task_id} finish\n\n";
            $data = json_encode($data);
            echo "Result: {$data}\n\n";
        }
    }

}