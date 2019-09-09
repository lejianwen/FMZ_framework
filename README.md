# A simple framework for FMZ！

# in-swoole分支，让FMZ运行在swoole中

## 运行

~~~
php webServer start
php webServer stop
php webServer restart
php webServer reload
~~~

# 简介
* 通过开发框架，让自己对web开发有了更好的认识
* ORM使用的是[eloquent](https://laravel.com/docs/5.4/eloquent)，即laravel使用的ORM
* 视图使用的是[smarty](http://www.smarty.net/)或者原生PHP
* session支持存放到mysql和redis和PHP自带方式
* cache支持文件和redis
 
#安装

## 方法一
* 运行composer create-project ljw/framework ljw --prefer-dist
## 方法二
* 直接下载或克隆 (git clone https://github.com/lejianwen/FMZ_framework.git)
* 然后运行composer install

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
├─runtime
│  ├─cache              缓存文件目录
│  ├─log                日志文件目录
│  └─smarty             smarty使用的目录
|
├─vendor                第三方类库目录（Composer依赖库）
├─.env                  环境配置文件，请自行创建或复制.env.example
├─.env.example          环境配置文件示例，请复制修改为.env
├─composer.json         composer 定义文件
└─README.md             README 文件
~~~
## 配置简介

### env($name, $default = null)方法会读取文件 *.env* 中的配置项。
每个环境可能不同，顾没有加入到git版本控制中，请自行添加，或者复制 *.env.example* 文件为 *.env*

* [app.php](#1-appphp)           项目配置文件
* [routes.php](#2-routesphp)       路由配置文件
* [redis.php](#3-redisphp)          redis配置文件
* [database.php](#4-databasephp)       数据库配置文件



###1. <i id="1-appphp">app.php</i> 
~~~
return [
    //session存放 为空表示用自带的, '' || mysql_pdo || redis , 具体看lib/session中的文件
    'session'           => '',
    'session_table'     => 'session',    //session 存放在mysql中的表名
    'session_lefttime'  => 1000,         //session有效时间
    'session_redis_dir' => 'default',            //session 使用的redis配置
    'debug'             => env('APP_DEBUG', false),         //调试模式
    'view'              => 'smarty',     //模板 smarty||native
    'smarty'            => [             //smarty配置
        'debug'           => env('SMARTY_DEBUG', false),            //是否弹出debug窗口
        'force_compile'   => env('SMARTY_FORCE_COMPILE', false),    //检查模板是否改动,开发时打开,正式 关闭
        'cache'           => env('SMARTY_CACHE', true),             //是否缓存
        'cache_lifetime'  => 1200,       //缓存时间
        'cache_dir'       => RUNTIME_PATH . 'smarty/cache/',          //缓存目录
        'compile_dir'     => RUNTIME_PATH . 'smarty/templates_c/',    //编译目录
        'left_delimiter'  => '<{',      //左定界符
        'right_delimiter' => '}>'       //右定界符
    ],
    'cache'             => 'file',       //缓存类型，file||redis
    'cache_expire'      => 1200,         //缓存时间
    'cache_file_dir'    => RUNTIME_PATH . 'cache/',
    'cache_redis_dir'   => 'default',   //cache 使用的redis配置
    'sys_log'           => env('SYS_LOG', true),                 //系统日志是否开启
    'sys_log_level'     => env('SYS_LOG_LEVEL', 'debug'),        //系统日志级别
    'sys_error_log'     => env('SYS_ERROR_LOG', true)            //系统错误日志是否开启

];
~~~
##### *debug*   是否打开DEBUG模式

#### 1) SESSION的相关配置
##### *session* session存储方式
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

##### *session_table* 用mysql存储session时的表名
##### *session_redis_dir* 用redis存储session时的配置
##### *session_lefttime* session的过期时间,单位秒

#### 2) 视图配置
**视图模板都用.tpl作为文件后缀**
##### *view* 视图
* 'smarty'  使用smarty作为视图
    * 使用此配置时，请填写smarty详细配置
* 'native'  使用原生PHP作为视图,在视图模板使用原生php

#### 3) 缓存配置
##### *cache*  缓存使用方式
* 'file'    使用文件缓存
* 'redis'   使用redis缓存,**必须安装php_redis扩展**

##### *cache_expire*   缓存有效时间
##### *cache_file_dir* 缓存文件目录
##### *session_redis_dir* 缓存使用的redis 配置
### 2. <i id="2-routesphp">routes.php</i> 
#### 路由配置 [参考ljw/route](https://github.com/lejianwen/route)
~~~php
use \Ljw\Route\Route;
Route::space('app\\controllers\\', 'app\\middleware\\');

Route::get('', 'IndexController@index');
Route::get('index/middle','Index@index', 'IndexController@middle');

Route::error(function (){
    app('response')->status('404');
    echo '404 Not Found!';
});
Route::run();
~~~
#### *get*  get请求
#### *post*  post请求
#### *error*  错误

###3.    <i id="3-redisphp">redis.php</i>
####    redis相关配置
~~~
return [
    'default' => [
        'host'     => env('REDIS_HOST', '127.0.0.1'),
        'pwd'      => env('REDIS_PASSWORD', null),
        'port'     => env('REDIS_PORT', 6379),
        'database' => 0,
    ]
];
~~~
###4.    <i id="4-databasephp">database.php</i>
####    数据库相关配置

~~~
return [
    'driver'      => 'mysql',
    'host'        => env('DB_HOST', '127.0.0.1'),
    'port'        => env('DB_PORT', '3306'),
    'database'    => env('DB_DATABASE', 'forge'),
    'username'    => env('DB_USERNAME', 'forge'),
    'password'    => env('DB_PASSWORD', ''),
    'unix_socket' => env('DB_SOCKET', ''),
    'charset'     => 'utf8mb4',
    'collation'   => 'utf8mb4_unicode_ci',
    'prefix'      => '',
    'strict'      => true,
    'engine'      => null,
];
~~~

### 添加命令行模式
~~~php
php console demo/first[/param1[/param2[...]]]
~~~
