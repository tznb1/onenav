OneNav Extend  是一款开源免费的书签（导航）管理程序，使用PHP + SQLite 3开发，界面简洁，安装简单，使用方便。OneNav可帮助你你将浏览器书签集中式管理，解决跨设备、跨平台、跨浏览器之间同步和访问困难问题，做到一处部署，随处访问。是基于xiaoz原创的OneNav基础上进行大量的修改,实现了更多的功能!现与xiaoz版是分开发布,独立维护的!


- 演示站 : [https://demo.lm21.top]( https://demo.lm21.top) 
- 如果您觉得还不错的话可以订阅支持下我,订阅后可使用更多的功能! [购买订阅/打赏]( https://gitee.com/tznb/OneNav/wikis/%E8%AE%A2%E9%98%85%E6%9C%8D%E5%8A%A1%E6%8C%87%E5%BC%95)  

### 2022年11月11日
- 网站管理>注册用户>新增:邀请注册 (该模式下注册时需通过邀请注册连接注册)(订阅可用)
- 默认主题(加强):设置中新增隐私保护选项(直连模式有效)
- 站点设置>功能配置:新增隐私模式(该模式不携带referer,起到防跟踪的效果)
- 修复登陆状态下跳转方式无效的bug
- 调整直连模式如果存在备用链接时将强制使用过渡页
- 修复PHP8下用户管理修改用户组没反应的问题
- 链接列表所属分类支持显示分类图标,支持点击分类名跳转到分类编辑
- 新增备份数据库下载

### 2022年11月01日
- 优化导入db3数据库,提高兼容性,修复导入二级分类异常,未导入图标URL和备用链接的bug,支持导入标签组
- 修复从原版升级安装时二级分类变一级的bug
- 修复默认主题(加强)右键生成二维码失效的问题(原调用第三方API改为本地JS生成,避免API不可用时无法生成,也解决了内网用户无法生成的问题)
- 书签导入HTML时允许选择保留二级分类(使用自动分类时可选)
- 书签导入HTML时分类目录无法识别时将链接加入选择的分类(如目录名为空)

### 2022年10月15日
- 修复PHP8下放入第三方非标准主题可能引发后台报错的问题
- html书签导入提取图标存放路径由favicon/xxx改为data/user/xxx/favicon (xxx代表用户名)
- 后台添加/编辑链接时支持上传URL图标(需管理员在网站管理>图标上传设为允许)
- 修复已知bug,其他细节调整

### 2022年10月06日
- 书签管理新增本地备份,在原版的基础新增: 支持显示分类数和链接数,支持备注!支持检测数据库是否被篡改或损坏!
- 订阅管理处显示您的域名,以免大家订阅时填错!
- 调整后台左侧导航栏突出显示当前菜单

### 2022年10月04日
- 修复已知bug,兼容性优化(PHP8)
- 优化主题模板预览图的显示效果
- 网站管理新增强制私有
- 订阅通道更变,新增查询订阅按钮(输入邮箱即可查询订单号)



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
* 支持Chromium内核的[浏览器扩展]

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
- 支持标签组 (可加密分享书签)
- 支持链接有效性检测
- 支持上传链接图标

## 项目地址

- [https://gitee.com/tznb/OneNav](https://gitee.com/tznb/OneNav)

- [https://github.com/tznb1/onenav](https://github.com/tznb1/onenav)

## 技术支持

- QQ:271152681

- 安装说明：[https://gitee.com/tznb/OneNav/wikis](https://gitee.com/tznb/OneNav/wikis)

![输入图片说明](https://gitee.com/tznb/OneNav/raw/data/picture/1617787025352-bb6e63df-e843-49d4-84e1-680c604f10dc.png)
![输入图片说明](https://gitee.com/tznb/OneNav/raw/data/picture/42ed3ef2c4a50f6d.png)
![输入图片说明](https://gitee.com/tznb/OneNav/raw/data/picture/cba9f1946776a8f0.png)
![输入图片说明](https://gitee.com/tznb/OneNav/raw/data/picture/QQ截图20221006162043.png)
![输入图片说明](https://gitee.com/tznb/OneNav/raw/data/picture/QQ截图20221006162050.png)
![输入图片说明](https://gitee.com/tznb/OneNav/raw/data/picture/QQ截图20221006162057.png)
![输入图片说明](https://gitee.com/tznb/OneNav/raw/data/picture/QQ截图20221006162105.png)
![输入图片说明](https://gitee.com/tznb/OneNav/raw/data/picture/QQ截图20221006162127.png)
![输入图片说明](https://gitee.com/tznb/OneNav/raw/data/picture/QQ截图20221006162135.png)
