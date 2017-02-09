# simple_framework
A simple framework
#安装

运行composer create-project ljw/framework ljw dev-master

#简介
* 自己开发的一个简单框架，功能比较简单，以后有时间会慢慢扩展
* 依赖的类库请查看composer.json
* ORM使用的是[eloquent](https://laravel.com/docs/5.4/eloquent)，即laravel使用的ORM
* 视图使用的是[smarty](http://www.smarty.net/)

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