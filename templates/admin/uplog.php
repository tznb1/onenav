<li class="layui-timeline-item">
    <i class="layui-icon layui-timeline-axis"></i>
    <div class="layui-timeline-content layui-text">
      <h4 class="layui-timeline-title">2022年04月05日</h4>
      <ul>
        <li>新增主题模板:小呆导航(#六度)</li>
        <li>新增主题模板:疯子(#疯子)</li>
      </ul>
    </div>
  </li>
  <li class="layui-timeline-item">
    <i class="layui-icon layui-timeline-axis"></i>
    <div class="layui-timeline-content layui-text">
      <h4 class="layui-timeline-title">2022年04月02日</h4>
      <ul>
        <li>修复分类和链接列表搜索后无法翻页的问题(#金秋十月)</li>
        <li>新增主题模板:六零导航(#六度)</li>
        <li>修复刘桐序主题未完全适配多用户的问题(#羽峰)</li>
      </ul>
    </div>
  </li>
  <li class="layui-timeline-item">
    <i class="layui-icon layui-timeline-axis"></i>
    <div class="layui-timeline-content layui-text">
      <h4 class="layui-timeline-title">2022年03月29日</h4>
      <ul>
        <li>修复禁止访问时管理员账号也无法跳转</li>
        <li>修复插件支持的伪静态导致本地图标API异常(<a href="https://doc.xiaoz.me/link/62#bkmrk-%E5%AE%89%E5%85%A8%E8%AE%BE%E7%BD%AE" target="_blank">请重新配置伪静态</a>)</li>
        <li>修复默认主题快速添加异常</li>
        <li>修复百素two无法搜索描述内容和分类图标的问题(是我适配不到位..)</li>
      </ul>
    </div>
  </li>
  <li class="layui-timeline-item">
    <i class="layui-icon layui-timeline-axis"></i>
    <div class="layui-timeline-content layui-text">
      <h4 class="layui-timeline-title">2022年03月19日</h4>
      <ul>
        <li>修复默认主题右键菜单生成二维码错误</li>
        <li>百素two:同步原作更新,添加/修改分类时:取消字体图标必填,修正修改分类提示语错误</li>
      </ul>
    </div>
  </li>
  <li class="layui-timeline-item">
    <i class="layui-icon layui-timeline-axis"></i>
    <div class="layui-timeline-content layui-text">
      <h4 class="layui-timeline-title">2022年03月15日</h4>
      <ul>
        <li>网站管理新增:插件支持,选择兼容模式时,可以使用xiaoz开发的uTools插件</li>
        <li>注:使用兼容模式2时,API将允许访客调用公开数据(分类和链接)</li>
        <li>新增API：根据ID查询单个分类信息,id支持get和post提交</li>
      </ul>
    </div>
  </li>
  <li class="layui-timeline-item">
    <i class="layui-icon layui-timeline-axis"></i>
    <div class="layui-timeline-content layui-text">
      <h4 class="layui-timeline-title">2022年03月12日</h4>
      <ul>
        <li>百素two:同步原作更新,手机端分类列表处添加后台入口</li>
        <li>修复后台首页地址和一键添加生成的URL在二级目录时不正确的问题!</li>
      </ul>
    </div>
  </li>
  <li class="layui-timeline-item">
    <i class="layui-icon layui-timeline-axis"></i>
    <div class="layui-timeline-content layui-text">
      <h4 class="layui-timeline-title">2022年03月12日</h4>
      <ul>
        <li>新增安装引导,未检测到lm.user.db3时自动进入安装引导</li>
        <li>支持从原版升级安装(检测是否存在onenav.db3和config.php).可保留除主题配置外的所有数据!</li>
        <li>支持全新安装(如果存在原版库请自行删除!未检测到原版数据库时安装模式为全新安装)</li>
        <li>注:全新安装时如果检测到用户数据库会询问是否保留,仅支持保留落幕魔改版的数据库!</li>
        <li>修复链接列表修改连接分类失效(上个版本带来的bug,我的锅)</li>
        <li>新增安全检测:db3数据库能否被下载,存在隐患时给出提示(管理员账号访问后台首页时触发)</li>
        <li>后台相关信息显示最新版本号(存在缓存机制,30分钟内不会重复获取!版本不同时仅显示红色文字!暂不弹窗提示!)</li>
        <li>简化API入口代码</li>
        <li>新增数据库更新功能 (暂不支持管理员批量升级用户数据库,访问后台首页时触发)</li>
        <li>新增备用链接功能   (开启直连模式时无效)</li>
        <li>新增过渡跳转页面   (开启直连模式时无效,已登录时1秒后跳转,未登录时5秒后跳转!存在备用链接时需手动点击)</li>
        <li>初始数据库更新</li>
        <li>注:关于API的问题,本项目和原版不同处:原版允许访客使用API读取公开数据,本项目不允许访客使用API</li>
      </ul>
    </div>
  </li>
  <li class="layui-timeline-item">
    <i class="layui-icon layui-timeline-axis"></i>
    <div class="layui-timeline-content layui-text">
      <h4 class="layui-timeline-title">2022年03月08日</h4>
      <ul>
        <li>Layui v2.5.4 升级到 v2.6.8 ,配置中静态路径不是./static的请同步更新到服务器!2.5.4资源暂留!</li>
        <li>后台UI优化移动端的兼容性</li>
        <li>百素主题:Layui,holmes.js路径改到配置中静态路径,删除主题目录下的layui,holmes.js重复资源!</li>
      </ul>
    </div>
  </li>
  <li class="layui-timeline-item">
    <i class="layui-icon layui-timeline-axis"></i>
    <div class="layui-timeline-content layui-text">
      <h4 class="layui-timeline-title">2022年03月07日</h4>
      <ul>
        <li>修复PHP8报错的问题,但还是建议使用7.4,因为我开发测试都是在7.4的!其他版本有问题在反馈吧!</li>
        <li>支持管理员修改用户账号.(修改前建议先备份data文件夹)</li>
        <li>SimpleWeb主题细节调整</li>
        <li>修复刘桐序主题直达连接错误的bug,白天/夜间模式的bug,新增后台入口,去除logo</li>
        <li>新增二级密码功能,设置后进入后台需要验证二级密码!包括首页的添加链接,删除链接均需验证!说白了不验证时只能阅览首页!(浏览器关闭时退出验证)</li>
      </ul>
    </div>
  </li>
  <li class="layui-timeline-item">
    <i class="layui-icon layui-timeline-axis"></i>
    <div class="layui-timeline-content layui-text">
      <h4 class="layui-timeline-title">2022年03月05日</h4>
      <ul>
        <li>选取原作的一些更新:手机登陆(成功返回主页),登陆漏洞,XSS漏洞,新增默认密码安全检测(仅在后台首页显示,不弹窗!)2.25</li>
        <li>修复已知bug(内容比较多,不单独列出了)</li>
        <li>登陆和注册页面优化</li>
        <li>合并API文件,分开写太麻烦了..</li>
        <li>允许用户重设登录入口,入口名算法改为随机生成,修复入口可以登陆其他账号的bug</li>
        <li>登录入口解释:因为允许站长修改入口,如果修改的入口有用户泄漏的话就和公开没太大区别,所以就有了专属入口,当用户不知道公用接口名时只能用专属接口登录</li>
        <li>站长删除用户时,禁止删除默认用户</li>
        <li>账号设置新增:Key安全,HttpOnly,登陆保持 注:需要PHP5.2以上</li>
        <li>管理员免密登陆强制使用HttpOnly,登陆保持为会话,最长有效期1小时!</li>
        <li>全局配置新增:ICP备案号,自定义代码,底部代码,取消用户设置备案号的权限</li>
        <li>新增百素大佬的新主题百素two</li>
        <li>修复首页点击logo文字或图标跳转地址错误</li>
        <li>修复部分主题二级目录运行出错问题</li>
        <li>用户管理工具栏新增一个修复按钮,可以修复部分数据库问题,升级时建议点两下看到无可修复项就行</li>
        <li>网站管理新增:防XSS脚本,防SQL注入! (会忽略自定义头部和底部代码XSS检测,因为检测的话基本上就没法用了..)</li>
        <li>书签导入调整,加入防XSS,报告标题和URL截取前面30和50位</li>
        <li>取消修改导航宽度,因为很多主题没有修改空间,准备加入单独的主题设置</li>
        <li>主页设置新增主题风格2选项,1是PC端主题,2是移动端主题,访问主页时由后端通过浏览器UA识别终端类型来载入不同的主题配置,可以解决部分主题不兼容移动端的尴尬.</li>
        <li>主题风格增加一个默认主题(原版),最大程度的保留原作的信息和样式!其他默认主题因为之前是没打算分享的,所以是按照自己的喜好精简了一些信息.</li>
        <li>网站管理>用户管理:支持修改用户组(点击用户行的用户组单元格会弹出提示),支持修改用户密码! 注:不能对自己修改!</li>
        <li>增加一个脚本,位于: /initial/SetAdmin.php 作用:全局配置错误无法访问时,管理员账号无法使用时强行修改相关配置,内有具体说明!</li>
      </ul>
    </div>
  </li>
  <li class="layui-timeline-item">
    <i class="layui-icon layui-timeline-axis"></i>
    <div class="layui-timeline-content layui-text">
      <h4 class="layui-timeline-title">2022年02月03日</h4>
      <ul>
        <li>新增用户管理,使用表格显示用户基础信息,支持搜索,一键进入用户主页,后台,删除用户!</li>
        <li>新增刘桐序主题风格</li>
      </ul>
    </div>
  </li>
  <li class="layui-timeline-item">
    <i class="layui-icon layui-timeline-axis"></i>
    <div class="layui-timeline-content layui-text">
      <h4 class="layui-timeline-title">2022年01月29日</h4>
      <ul>
        <li>为了提高易用性,取消了登录和注册的库名!新增用户表!取消账号修改功能!</li>
        <li>登录和注册页面分离,支持修改注册入口名!</li>
        <li>密码记录改为MD5,而非明文!提高了安全性!</li>
        <li>新增网站管理功能,管理员账号可以在后台修改全局配置(30日)</li>
      </ul>
    </div>
  </li>
  <li class="layui-timeline-item">
    <i class="layui-icon layui-timeline-axis"></i>
    <div class="layui-timeline-content layui-text">
      <h4 class="layui-timeline-title">2022年01月25日</h4>
      <ul>
        <li>优化:书签导入时显示加载层,导入结束返回具体的失败条目!</li>
      </ul>
    </div>
  </li>
  <li class="layui-timeline-item">
    <i class="layui-icon layui-timeline-axis"></i>
    <div class="layui-timeline-content layui-text">
      <h4 class="layui-timeline-title">2022年01月24日</h4>
      <ul>
        <li>新增:书签导入支持db3格式,可用于升级时导入旧数据,数据合并等!,新增保留属性开关,选为是时保留相关属性!</li>
        <li>调整:书签导入上传路径改为根目录下upload文件夹,API限制该文件夹外的路径!</li>
      </ul>
    </div>
  </li>
  <li class="layui-timeline-item">
    <i class="layui-icon layui-timeline-axis"></i>
    <div class="layui-timeline-content layui-text">
      <h4 class="layui-timeline-title">2022年01月23日</h4>
      <ul>
        <li>新增:网站图标获取接口配置,支持本地服务器获取(支持缓存,如果服务器不能访问外网请勿使用,会增加服务器负担,小宽度低配置请酌情使用!)</li>
      </ul>
    </div>
  </li>
  <li class="layui-timeline-item">
    <i class="layui-icon layui-timeline-axis"></i>
    <div class="layui-timeline-content layui-text">
      <h4 class="layui-timeline-title">2022年01月22日</h4>
      <ul>
        <li>修复:注册后首次访问主页有一个js加载失败的bug,调整注册后的默认配置!</li>
        <li>修复:百素主题首页添加链接无法使用</li>
        <li>优化:首页添加连接适用移动端,适配:默认主题,百素主题 (终端识别方式:js识别浏览器UA,如有不准请反馈)</li>
      </ul>
    </div>
  </li>
  <li class="layui-timeline-item">
    <i class="layui-icon layui-timeline-axis"></i>
    <div class="layui-timeline-content layui-text">
      <h4 class="layui-timeline-title">2022年01月21日</h4>
      <ul>
        <li>新增:链接列表支持置顶操作!</li>
      </ul>
    </div>
  </li>
  <li class="layui-timeline-item">
    <i class="layui-icon layui-timeline-axis"></i>
    <div class="layui-timeline-content layui-text">
      <h4 class="layui-timeline-title">2022年01月20日</h4>
      <ul>
        <li>新增:链接列表新增所属分类搜索,可搜索全部或指定分类! PS:我可以不用,但你不能不支持!</li>
        <li>修复:单元格编辑后没有更新修改时间</li>
        <li>新增:连接列表新增批量修改分类</li>
      </ul>
    </div>
  </li>
  <li class="layui-timeline-item">
    <i class="layui-icon layui-timeline-axis"></i>
    <div class="layui-timeline-content layui-text">
      <h4 class="layui-timeline-title">2022年01月19日</h4>
      <ul>
        <li>后台:分类列表搜索框支持回车搜索,新增链接数列(显示分类目录下的链接数),开启修改时间和链接数排列支持</li>
        <li>后台:链接列表支持记忆筛选状态,支持搜索功能</li>
        <li>修复:分类搜索和连接搜索时底部共计条数错误</li>
        <li>后台:分类和连接列表支持单元格编辑同步修改数据,分类开放:名称,权重,描述!连接开放:标题,权重</li>
        <li>待修复:修改导航宽度后手机端异常,原因是宽度被限定了,CSS无法动态适配!</li>
      </ul>
    </div>
  </li>
  <li class="layui-timeline-item">
    <i class="layui-icon layui-timeline-axis"></i>
    <div class="layui-timeline-content layui-text">
      <h4 class="layui-timeline-title">2022年01月18日</h4>
      <ul>
        <li>后台:分类列表支持多选删除和强行删除(如果分类下有链接则删除链接),多选删除时需要输入随机数字确认来防止手贱误删!</li>
        <li>后台:分类列表支持搜索功能,可搜索分类名称和描述!</li>
        <li>后台:批量删除API和JS优化,一次请求提交所有需要删除的id,避免短时间的频繁请求被认为是CC攻击!</li>
      </ul>
    </div>
  </li>
   <li class="layui-timeline-item">
    <i class="layui-icon layui-timeline-axis"></i>
    <div class="layui-timeline-content layui-text">
      <h4 class="layui-timeline-title">2022年01月17日</h4>
      <ul>
        <li>新增:菜鸟教程的主题,为了方便调试新增Get参数Theme=runoob&Style=0 用于载入指定主题!</li>
      </ul>
    </div>
  </li>    
   <li class="layui-timeline-item">
    <i class="layui-icon layui-timeline-axis"></i>
    <div class="layui-timeline-content layui-text">
      <h4 class="layui-timeline-title">2022年01月15/16日</h4>
      <ul>
        <li>新增:webstack主题,并修复左侧栏特定分辨率无法显示图标的bug,左侧栏添加登陆入口和后台入口</li>
        <li>新增:SimpleWeb主题</li>
        <li>优化:数据库升级脚本</li>
      </ul>
    </div>
  </li>
   <li class="layui-timeline-item">
    <i class="layui-icon layui-timeline-axis"></i>
    <div class="layui-timeline-content layui-text">
      <h4 class="layui-timeline-title">2022年01月14日</h4>
      <ul>
        <li>优化:目录结构调整,更新jquery</li>
        <li>优化:整合百素主题资源,新增百素极速样式(其实就是去掉了Logo字体!)</li>
        <li>修复:默认首页判断错误的bug (if少打了1个=号)</li>
        <li>修复:百素主题深色模式,搜索框文本和未找到提示文本看不清的问题</li>
        <li>安全:账号设置独立一个菜单,修改邮箱.账号.密码.令牌(Token)时需要输入原密码!</li>
      </ul>
    </div>
  </li>
  <li class="layui-timeline-item">
    <i class="layui-icon layui-timeline-axis"></i>
    <div class="layui-timeline-content layui-text">
      <h4 class="layui-timeline-title">2022年01月13日</h4>
      <ul>
        <li>优化:主题风格由输入框改为选择框,并支持多个样式</li>
        <li>新增:支持自定义CSS,支持修改首页左侧栏宽度</li>
        <li>新增:百素主题,2个样式(显示描述和隐藏描述)</li>
        <li>优化:后台首页列出所用框架的相关文档,相关信息,更新记录!</li>
        <li>其他:修正一些代码(图标对齐问题,对样式1和2修正)</li>
      </ul>
    </div>
  </li>
  <li class="layui-timeline-item">
    <i class="layui-icon layui-timeline-axis"></i>
    <div class="layui-timeline-content layui-text">
      <h4 class="layui-timeline-title">2022年01月11日</h4>
      <ul>
        <li>后台:支持配置首页功能,直连模式.返回顶部.快速添加.后台入口.默认首页</li>
        <li>安全:入口支持修改登录入口名称,可隐藏入口提高安全性!</li>
        <li>后台:优化注册接口,限制用户库名和账号只能用字母和数字注册!</li>
        <li>后台:布局优化</li>
        <li>其他:修正一些代码,使用IE浏览是弹出不兼容提示!</li>
      </ul>
    </div>
  </li>
  <li class="layui-timeline-item">
    <i class="layui-icon layui-timeline-axis"></i>
    <div class="layui-timeline-content layui-text">
      <h4 class="layui-timeline-title">2022年01月08日</h4>
      <ul>
        <li>去除data的php配置文件,配置存入用户的db3数据库</li>
        <li>后台:支持新增账号设置页面,方便用户快捷修改配置!</li>
        <li>后台:取消自定义JavaScript功能,可以直接使用底部代码代替!</li>
        <li>支持用户注册,入口可以配置是否允许注册!</li>
        <li>支持记录用户注册IP,注册时间</li>
        <li>支持记录登录日志(登陆成功或失败时,记录库:login.log.db)</li>
        <li>安全:新增登陆失败保护机制!降低被爆力的风险</li>
        <li>安全:调整保持登陆的Key生成策略!保护Key防止篡改!</li>
        <li>修改ApiToken逻辑,可直接修改值,而不是通过USER+TOKEN计算!</li>
        <li>修改Api接口,支持Api查询私有分类(原作只能查公开分类)</li>
        <li>修改API接口,禁止未传递Token时查询公开连接和分类</li>
        <li>修改API接口,添加分类时禁止空分类名称</li>
        <li>优化一些接口的和鉴权逻辑</li>
      </ul>
    </div>
  </li>
  <li class="layui-timeline-item">
    <i class="layui-icon layui-timeline-axis"></i>
    <div class="layui-timeline-content layui-text">
      <h4 class="layui-timeline-title">2021年12月17日</h4>
      <ul>
        <li>调整目录结构,db文件夹改名为initial,存放初始数据!</li>
        <li>调整入口,支持多用户!URL的参数u是数据库名!</li>
        <li>首页:去掉一些元素:右上角头像,底部技术支持,侧边栏About等!</li>
        <li>首页:右键删除提示框改为中文提示!</li>
        <li>修正无法在二级目录运行的问题!</li>
        <li>一键添加支持识别分类ID!修改脚本中的category=id即可!</li>
        <li>相关框架和库本地储存,支持配置CDN路径!</li>
        <li>后台:新增一键添加,显示生成好的脚本和相关说明!</li>
        <li>后台:新增XIcon图标选择器,扩展分类表新增Icon存放图标名称!</li>
        <li>后台:分类和连接列表中私有标记改为可操作的开关组件!</li>
        <li>后台:优化分类和连接列表,默认显示20条/页,支持自适应高度!</li>
      </ul>
    </div>
  </li>
  <li class="layui-timeline-item">
    <i class="layui-icon layui-timeline-axis"></i>
    <div class="layui-timeline-content layui-text">
      <h4 class="layui-timeline-title">2021年07月26日 原作者发布的版本!</h4>
    </div>
  </li>