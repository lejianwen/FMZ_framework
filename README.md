# A simple framework

#安装

运行composer create-project ljw/framework ljw --prefer-dist

#简介
* 自己开发的一个简单框架，功能比较简单，以后有时间会慢慢扩展
* 依赖的类库请查看composer.json
* ORM使用的是[eloquent](https://laravel.com/docs/5.4/eloquent)，即laravel使用的ORM
* 视图使用的是[smarty](http://www.smarty.net/)

#目录结构
~~~
ljw                     WEB部署目录（或者子目录）
├─app                   应用目录
│  ├─controllers        控制器
│  ├─models             模型
│  └─views              视图  
├─bootstrap             
│  └─bootstrap.php      应用启动文件
├─config                配置文件目录
│  ├─app.php            项目配置
│  ├─routes.php         路由配置文件
│  ├─redis.php          redis配置
│  └─database.php       数据库配置文件
├─data
│  ├─log                日志文件目录
│  └─smarty             smarty使用的目录
├─lib                   框架系统目录
│  ├─http               http目录
│  ├─session            session存放实现目录
│  ├─view               视图目录
│  └─ ...               更多系统模块
│
├─public                WEB目录（对外访问目录）
│  ├─static             静态文件目录，比如js，image
│  ├─index.php          入口文件
│  └─.htaccess          用于apache的重写
├─vendor                第三方类库目录（Composer依赖库）
├─composer.json         composer 定义文件
└─README.md             README 文件
~~~
## 配置简介

* app.php            项目配置文件
* routes.php         路由配置文件
* redis.php          redis配置文件
* database.php       数据库配置文件

###1. app.php 
~~~
return [
    //session存放 为空表示用自带的, mysql_pdo || redis, 具体看lib/session中的文件
    'session'          => '',
    'session_table'    => 'session',    //session 存放在mysql中的表名
    'session_lefttime' => 1000,
    'session_redis_db' => 0,            //session 存放在redis中的某个db
    'view'             => 'smarty',     //模板
    'debug'            => true,         //调试模式
    'smarty'           => [
        'debug'           => false,     //是否弹出debug窗口
        'force_compile'   => false,     //检查模板是否改动,开发时打开,正式 关闭
        'cache'           => false,     //是否缓存
        'cache_lifetime'  => 120,       //缓存时间
        'cache_dir'       => BASE_PATH . 'data/smarty/cache/',
        'compile_dir'     => BASE_PATH . 'data/smarty/templates_c/', //编译目录
        'left_delimiter'  => '<{',
        'right_delimiter' => '}>'
    ],
    'cache'            => 'redis',       //缓存类型，file||redis
    'cache_expire'     => 120,          //缓存时间
    'cache_file_dir'   => BASE_PATH . 'data/cache/',
    'cache_redis_db'   => 1


];
~~~
##### debug   是否打开DEBUG模式

####1) SESSION的相关配置
##### session session存储方式
* ''             使用PHP默认的session存储方式,此时session_lefttime配置失效,请查看**php.ini**;
* 'mysql_pdo'    使用mysql存储session,**暂时只支持pdo，请安装好pdo扩展**
    * 使用此配置时,请填写session_table,表示session存储的表名
    * 创建session表
~~~
     CREATE TABLE `table` (
         id varchar(255) NOT NULL,
         expire TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
         data blob,
         UNIQUE KEY `id` (`id`) USING BTREE,
         KEY `expire` (`expire`) USING BTREE
       )ENGINE=myisam default charset=utf8;
~~~
* 'redis'        使用redis存储session, **必须安装php_redis扩展**
    * 使用此配置时,请填写session_redis_db,表示session存储的db   
* 'predis'       使用redis存储session, **不用php_redis扩展**
    * 使用此配置时,请填写session_redis_db,表示session存储的db
    
##### session_table 用mysql存储session时的表名
##### session_redis_db 用redis存储session时的db
##### session_lefttime session的过期时间,单位秒

####2) 视图配置
**视图模板都用.tpl作为文件后缀**
##### view 视图
* 'smarty'  使用smarty作为视图
    * 使用此配置时，请填写smarty详细配置
* 'native'  使用原生PHP作为视图,在视图模板使用原生php

####3) cache   缓存配置
##### cache  缓存使用方式
* 'file'    使用文件缓存
* 'redis'   使用redis缓存,**必须安装php_redis扩展**

##### cache_expire   缓存有效时间
##### cache_file_dir 缓存文件目录
##### cache_redis_db 缓存使用的redis db
###2. routes.php 
#### 路由配置
~~~
return [
    'get'  => [
        ''        => 'app\controllers\IndexController@index',
        'demo/(:num)' => 'app\controllers\DemoController@num'
    ],
    'post' => [

    ],
    'error' => [
        function () {
            app\controllers\ErrorController::NotFound_404();
        }
    ]

];
~~~
#### get    get请求
#### post   post请求
#### error  错误

* 如果要自动匹配,加入以下代码
~~~
'(:str)/(:str)' => function ($controller, $method) {
            $class = 'app\\controllers\\' . ucwords($controller) . 'Controller';
            if (method_exists($class, $method) && is_callable([$class, $method]))
            {
                $object = new $class;
                $object->$method();
            } else
                app\controllers\ErrorController::NotFound_404();
        }
~~~

###3.    redis.php
####    redis相关配置
~~~
return [
    'host' => '127.0.0.1',
    'port' => 6379,
    'db' => 0,
    'password' => null
];
~~~
###4.    database.php
####    数据库相关配置

~~~
return [
    'driver'    => 'mysql',
    'host'      => '127.0.0.1',
    'database'  => 'ljw',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => ''
];
~~~


