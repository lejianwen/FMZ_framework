# A simple framework for FMZ！

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
├─vendor                第三方类库目录（Composer依赖库）
├─composer.json         composer 定义文件
└─README.md             README 文件
~~~
## 配置简介

* [app.php](#1-appphp)           项目配置文件
* [routes.php](#2-routesphp)       路由配置文件
* [redis.php](#3-redisphp)          redis配置文件
* [database.php](#4-databasephp)       数据库配置文件

###1. <i id="1-appphp">app.php</i> 
~~~
return [
    //session存放 为空表示用自带的, '' || mysql_pdo || redis || prdis, 具体看lib/session中的文件
    'session'          => '',
    'session_table'    => 'session',    //session 存放在mysql中的表名
    'session_lefttime' => 1000,         //session有效时间
    'session_redis_db' => 0,            //session 存放在redis中的某个db
    'debug'            => true,         //调试模式
    'view'             => 'smarty',     //模板 smarty||native
    'smarty'           => [             //smarty配置
        'debug'           => false,     //是否弹出debug窗口
        'force_compile'   => false,     //检查模板是否改动,开发时打开,正式 关闭
        'cache'           => false,     //是否缓存
        'cache_lifetime'  => 120,       //缓存时间
        'cache_dir'       => BASE_PATH . 'data/smarty/cache/',          //缓存目录
        'compile_dir'     => BASE_PATH . 'data/smarty/templates_c/',    //编译目录
        'left_delimiter'  => '<{',      //左定界符
        'right_delimiter' => '}>'       //右定界符
    ],
    'cache'            => 'predis',       //缓存类型，file||redis||predis
    'cache_expire'     => 120,          //缓存时间
    'cache_file_dir'   => BASE_PATH . 'data/cache/',
    'cache_redis_db'   => 1


];
~~~
##### *debug*   是否打开DEBUG模式

####1) SESSION的相关配置
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
    * 使用此配置时,请填写session_redis_db,表示session存储的db   
* 'predis'       使用redis存储session, **不用php_redis扩展**
    * 使用此配置时,请填写session_redis_db,表示session存储的db
    
##### *session_table* 用mysql存储session时的表名
##### *session_redis_db* 用redis存储session时的db
##### *session_lefttime* session的过期时间,单位秒

####2) 视图配置
**视图模板都用.tpl作为文件后缀**
##### *view* 视图
* 'smarty'  使用smarty作为视图
    * 使用此配置时，请填写smarty详细配置
* 'native'  使用原生PHP作为视图,在视图模板使用原生php

####3) 缓存配置
##### *cache*  缓存使用方式
* 'file'    使用文件缓存
* 'redis'   使用redis缓存,**必须安装php_redis扩展**

##### *cache_expire*   缓存有效时间
##### *cache_file_dir* 缓存文件目录
##### *cache_redis_db* 缓存使用的redis db
###2. <i id="2-routesphp">routes.php</i> 
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
    'host' => '127.0.0.1',
    'port' => 6379,
    'db' => 0,
    'password' => null
];
~~~
###4.    <i id="4-databasephp">database.php</i>
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

### 添加命令行模式
~~~php
php console demo/first[/param1[/param2[...]]]
~~~
