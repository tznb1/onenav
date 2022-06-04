OneNav Extend  ( 原:魔改版 ) 是一款开源免费的书签（导航）管理程序，使用PHP + SQLite 3开发，界面简洁，安装简单，使用方便。OneNav可帮助你你将浏览器书签集中式管理，解决跨设备、跨平台、跨浏览器之间同步和访问困难问题，做到一处部署，随处访问。是基于xiaoz原创的OneNav基础上进行大量的修改,实现了更多的功能!现与xiaoz版是分开发布,独立维护的!


- 演示站 : [https://demo.lm21.top]( https://demo.lm21.top) 

### 2022年06月04日
- 优化链接识别,提高识别率
- 修复已知bug(登录入口异常,0521分类列表异常)
- API接口更新
- 新增登录页和过渡页模板支持,位于主题模板>其他模板 (下载/更新/切换都在这里操作)
- 其他模板>新上架:涂山简约(含登录页/过渡页)
- 其他细节调整
- Docker支持:请使用容器安装的用户更新一下镜像!已支持主题下载和系统更新!

### 2022年05月31日
- 恢复0411去除的设为默认主页功能,位于账号设置显示(特定条件下不显示)
- 本地主题预览图优先使用本地资源,避免因图床或网络问题造成无法加载(遇到在线图片无法加载时不影响下载)
- 下载主题时如果存在说明则显示说明
- 修复主题模板非admin账号刷新数据异常的问题(普通用户不显示刷新数据)
- 关闭注册时(登录页不显示注册入口,禁止访问注册页面)

### 2022年05月27日
- 新增涂山的简约主题
- 新增公告栏,在后台首页展示(如果有)
- 新增离线模式,位于网站管理,适用服务器无法访问互联网的环境,关闭后不在获取新版本,公告,禁止书签克隆,在线主题,链接识别等!
- 备注:离线模式只管服务器不访问互联网,部分主题需要访问第三方资源,如果客户端也未联网,建议图标API使用离线图标,使用默认主题并关闭天气功能!
- 新增主题在线下载功能(缓存30分钟,仅管理员可下载和更新),此版本后只集成默认主题,其他主题请按需下载!
- 主题设置页面顶部新增导航条,正在被使用的主题标题显示为蓝色
- 新增一键更新支持,可更新时在管理员后台首页显示一键更新按钮!
- 修复已知bug

### 2022年05月21日
- 书签管理新增链接复刻功能
- 修复兼容模式2(API接口)数据泄漏的问题,使用此模式的站长请尽快更新!非必要请勿使用此模式
- 修复删除分类的bug

### 2022年05月18日
- 优化书签管理>数据清空的处理逻辑
- 应粉丝要求,给刘桐序主题添加书签搜索
- 默认主题搜索支持匹配URL
- 修改默认主题角标大小
- 登录日志新增注册和初始安装记录
- 默认主题原版新增设置:链接描述开关和全宽模式开关


## 功能特色

* 支持后台管理
* 支持私有链接
* 支持Chrome/Firefox/Edge书签批量导入
* 支持多种主题风格
* 支持链接信息自动识别
* 支持API
* 支持Docker部署
* 支持uTools插件
* 支持二级分类
* 支持Chromium内核的[浏览器扩展](https://doc.xiaoz.me/books/onenav-extend/page/chrome)（插件）

## 新增功能
- 支持多用户
- 支持隐藏登陆入口和注册入口
- 支持登陆保护机制
- 支持二级密码
- 支持静态库离线和CDN加速
- 支持非根目录运行
- 支持分类选择字体图标(927个图标)
- 支持分类和链接的关键字搜索
- 支持筛选链接分类和批量修改分类
- 支持在列表单元格上快速修改数据
- 支持从原版升级安装

## 项目地址

- [https://gitee.com/tznb/OneNav](https://gitee.com/tznb/OneNav)

## 技术支持

- QQ:271152681

- QQ群：147687134

- 社区支持：[https://dwz.ovh/vd0bw](https://dwz.ovh/vd0bw)

- 安装说明：[https://doc.xiaoz.me/books/onenav-extend/page/1651c](https://doc.xiaoz.me/books/onenav-extend/page/1651c)

![img](https://doc.xiaoz.me/yuque/0/2021/png/192152/1617787025352-bb6e63df-e843-49d4-84e1-680c604f10dc.png)

![](https://img.rss.ink/imgs/2022/03/cba9f1946776a8f0.png)

![](https://img.rss.ink/imgs/2022/03/42ed3ef2c4a50f6d.png)

![img](https://doc.xiaoz.me/yuque/0/2020/png/192152/1608005352818-4105b24b-e650-42a7-9b20-f35ffa023504.png)

[![QQ截图20220313000658.jpg](https://doc.xiaoz.me/uploads/images/gallery/2022-03/scaled-1680-/qq20220313000658.jpg)](https://doc.xiaoz.me/uploads/images/gallery/2022-03/qq20220313000658.jpg)
[![QQ截图20220313000643.jpg](https://doc.xiaoz.me/uploads/images/gallery/2022-03/scaled-1680-/qq20220313000643.jpg)](https://doc.xiaoz.me/uploads/images/gallery/2022-03/qq20220313000643.jpg)
[![QQ截图20220313000718.jpg](https://doc.xiaoz.me/uploads/images/gallery/2022-03/scaled-1680-/qq20220313000718.jpg)](https://doc.xiaoz.me/uploads/images/gallery/2022-03/qq20220313000718.jpg)
[![QQ截图20220313000747.jpg](https://doc.xiaoz.me/uploads/images/gallery/2022-03/scaled-1680-/qq20220313000747.jpg)](https://doc.xiaoz.me/uploads/images/gallery/2022-03/qq20220313000747.jpg)