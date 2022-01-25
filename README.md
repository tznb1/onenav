## 使用须知
* 当前版本为开发版,不建议用于生产环境!为了提高易用性下个版本将取消库名,并支持站长管理用户的功能!调整会比较大!
* 反馈和建议联系QQ:271152681

## 安装说明
* 调试基于php 7.4,其他版本自行测试,已知8.0不兼容
* 全新安装:复制到服务器上直接访问地址,Web直接注册即可!默认库名admin不建议改
* 原版升级:
* 1.压缩备份原来的整站数据,删掉除data目录外的文件和目录,然后覆盖上传!
* 2.把原来data目录下的onenav.db3改名为admin.db3
* 3.把原来data目录下的config.php改名为admin.php
* 4.打开data/admin.php 删除或注释掉3到8行,即require 'class/Medoo.php';开始到 ]);结束!
* 5.访问主页,看到升级提示刷新2次即可,账号密码是从php配置里复制的!如果没有配置密码默认为admin,账号是库名即admin
* 访问非默认库(其他用户):url/?u=admin  (admin是库名,即data目录下的数据库,库名不等于账号!登陆后台可设置成默认首页!)

## 修改说明
* 支持多用户注册使用 Web自助申请 自助修改用户名和密码 (可关闭注册)
* 支持注册时记录用户注册IP和时间
* 支持登陆保护机制 多次登陆失败后会被限制 (防止被爆破)
* 支持隐藏登陆注册接口 (防止被爆破)
* 支持在非根目录运行
* 支持js css等静态库本地储存 同时也支持CDN
* 支持后台Web配置:标题,Logo,关键字,描述,备案号等等操作 (废弃原来的PHP配置,因为要注册多用户)
* 支持开关首页是否显示:回到顶部,快速添加,后台入口,以及可以隐藏登陆入口,关闭图标加载等
* 支持自定义CSS(头部代码),自定义底部代码 (考虑到多用户支持,取消了原有的自定义JS,可用底部代码代替!)
* 后台分类和链接列表做了一些优化,链接支持搜索(可指定分类),批量修改分类,置顶,记忆筛选状态等
* 分类列表支持搜索,批量删除和批量强制删除(分类下有链接时删除链接)
* 支持在列表直接修改数据,设置是否私有等便捷的操作
* 集成了一些主题 因为改动比较大 所以原来的第三方主题是无法直接使用的!需要适配的可以联系我! 
* 后台列表:支持自适应高度 默认显示20条 
* 分类列表:支持显示图标,新增修改可以直接选择图标,而不是用代码,支持 Layui 和 Font Awesome! 
* 后台新增一键添加,显示相关操作说明和生成好的代码 并加入参数识别,可传入分类ID,这样就自动帮你选好默认分类了.
* 其他安全性的优化,支持记录用户登陆日志 (目前只是记录到数据库,并未实现查看功能)
* 支持升级,自动修改数据表结构,自动把PHP的配置写入数据库 (但仍建议您备份好数据在升级!!!)
* 还有啥,想不起来了...因为前期修改没有考虑过要发布的,所以也没写更新记录! 
* 具体的自己测试吧..PHP7.4

下面的说明是原著的!
# OneNav  
使用PHP + SQLite 3开发的简约导航/书签管理器，xiaoz新作，欢迎体验。

[外链图片转存失败,源站可能有防盗链机制,建议将图片保存下来直接上传(img-i5XAHeCh-1642820298024)(https://i.bmp.ovh/imgs/2020/12/40f222b7da7a89c9.png)]

![](https://img-blog.csdnimg.cn/img_convert/bd10e31f4e069fe222ca5f647586baf2.png)

![](https://img-blog.csdnimg.cn/img_convert/fbfa42b1bc9a4ff500f1746b8f84574d.png)

![](https://img-blog.csdnimg.cn/img_convert/ed3780e8d6e5cca40bcb88d502279817.png)

## 功能特色

* 支持后台管理
* 支持私有链接
* 支持书签批量导入
* 支持多种主题风格
* 支持链接信息自动识别
* 支持API
* 支持Docker部署
* 支持uTools插件

## 安装

**常规安装：**

1. 需安装PHP环境，并确保支持SQLite3
2. 下载源码解压到站点根目录
3. 将`config.simple.php`复制为`data/config.php`并填写自己的站点信息
5. 访问后台：`http://IP/index.php?c=login`

**Docker部署：**

```bash
docker run -itd --name="onenav" -p 80:80 \
    -e USER='xiaoz' -e PASSWORD='xiaoz.me' \
    -v /data/onenav:/data/wwwroot/default/data \
    helloz/onenav
```

* `USER`：设置用户名，上述设置为`xiaoz`
* `PASSWORD`：设置密码，上述设置为`xiaoz.me`
* `/data/onenav`：本机挂载目录，用于持久存储Onenav数据

> 更多说明，请参考帮助文档：https://www.yuque.com/helloz/onenav

## Demo

以下是OneNav部分用户演示站，排名不分先后。

* OneNav：[https://nav.rss.ink/](https://nav.rss.ink/)
* 千行书签：[http://www.52qx.club/](http://www.52qx.club/)
* 纽及书签：[http://www.1006788.com/](http://www.1006788.com/)

## 联系我

* Blog:https://www.xiaoz.me/
* QQ:337003006
* QQ群：147687134
* 社区支持：[https://dwz.ovh/vd0bw](https://dwz.ovh/vd0bw)

## 鸣谢

OneNav诞生离不开以下项目，在此表示感谢（排名不分先后）。

* [WebStackPage](https://github.com/WebStackPage/WebStackPage.github.io)
* [LayUI](https://github.com/sentsin/layui)
* [Medoo](https://github.com/catfan/Medoo)
* [MDUI](https://github.com/zdhxiong/mdui)
