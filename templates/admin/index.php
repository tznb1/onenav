<?php include_once('header.php'); ?>
<?php include_once('left.php'); ?>

<style type="text/css">
.layui-layout-admin .layui-body {top: 40px;}
</style>
<div class="layui-tab layui-tab-card layui-body layui-row content-body">
<ul class="layui-tab-title">
 <li class="layui-this">相关信息</li>
 <li>开发文档</li>
</ul>
<div class="layui-tab-content">
<div class="layui-tab-item layui-show layui-form layui-form-pane"><!--相关信息--> 
<?php 
 if($password === md5(md5('admin').$RegTime)){
     ?>
      <div class="layui-form-item" style="color:#FF0000">
      <label class="layui-form-label">风险提示</label>
      <div class="layui-input-block" >
        <input value='系统检测到您使用的默认密码，请尽快修改！'disabled class="layui-input" style="color:#FF0000">
      </div>
    </div>
 <?php }
?>
<?php 
 if($Skey ==''){
     ?>
      <div class="layui-form-item" style="color:#FF0000">
      <label class="layui-form-label">配置缺失</label>
      <div class="layui-input-block" >
        <input value='系统检测到您Key安全未配置,请到账号设置更新配置！'disabled class="layui-input" style="color:#FF0000">
      </div>
    </div>
 <?php }
?>
  <div class="layui-form-item">
    <div class="layui-inline">
      <label class="layui-form-label">当前版本</label>
        <div class="layui-input-inline">
        <input value='<?php echo get_version(); ?>'disabled class="layui-input">
      </div> 
    </div>
    <!--<div class="layui-inline">-->
    <!--  <label class="layui-form-label">最新版本</label>-->
    <!--    <div class="layui-input-inline">-->
    <!--    <input value='<?php echo file_get_contents('https://gitee.com/tznb/OneNav/raw/master/initial/version.txt'); ?>'disabled class="layui-input">-->
    <!--  </div> -->
    <!--</div>-->
    <div class="layui-inline">
      <label class="layui-form-label">数据库</label>
        <div class="layui-input-inline">
        <input value='<?php echo $userdb['SQLite3'];?>'disabled class="layui-input">
      </div> 
    </div>
    <div class="layui-inline">
      <label class="layui-form-label">账号</label>
        <div class="layui-input-inline">
        <input value='<?php echo $userdb['User'];?>'disabled class="layui-input">
      </div> 
    </div>    
    <div class="layui-inline">
      <label class="layui-form-label">注册时间</label>
      <div class="layui-input-inline">
        <input value='<?php echo date("Y-m-d H:i:s",$RegTime);;?>'disabled class="layui-input">
      </div>
    </div>
    <div class="layui-inline">
      <label class="layui-form-label">注册IP</label>
      <div class="layui-input-inline">
        <input value='<?php echo $userdb['RegIP'];?>'disabled class="layui-input">
      </div>
    </div>
    <div class="layui-inline">
      <label class="layui-form-label">当前IP</label>
      <div class="layui-input-inline">
        <input value='<?php echo getIP();?>'disabled class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">浏览器UA</label>
      <div class="layui-input-block">
        <input value='<?php echo $_SERVER['HTTP_USER_AGENT'];?>'disabled class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">登录入口</label>
      <div class="layui-input-block">
        <input value='<?php 
        $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' :'http://';
        echo $http_type.$_SERVER['HTTP_HOST'].'/index.php?c='.$Elogin.'&u='.$u;?>         注:请保存好您的专属入口,避免特定情况造成无法登陆!'disabled class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">我的首页</label>
      <div class="layui-input-block">
        <input value='<?php 
        $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' :'http://';
        echo $http_type.$_SERVER['HTTP_HOST'].'/index.php?u='.$u;?>'disabled class="layui-input">
      </div>
    </div>
  </div><!--表单End-->
<ul class="layui-timeline">
  <li class="layui-timeline-item">
    <i class="layui-icon layui-timeline-axis"></i>
    <div class="layui-timeline-content layui-text">
      <h4 class="layui-timeline-title">2022年02月--日</h4>
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
</ul>     
</div><!--相关信息End-->
<div class="layui-tab-item"><!--开发文档-->


<blockquote class="layui-elem-quote layui-text">此版项目地址：<a href="https://gitee.com/tznb/OneNav" target="_blank">Gitee 码云 (唯一地址)  </a>&ensp; QQ: 271152681&ensp; PS:各位大哥,如果可以的话麻烦大家帮忙宣传一下..目前用户数太少了,根本没动力更新!</blockquote>
<blockquote class="layui-elem-quote layui-text">原著项目地址 ：<a href="https://github.com/helloxz/onenav" target="_blank">github</a>&ensp; QQ群1：147687134 &ensp;QQ群2：932795364&ensp;QQ:337003006</blockquote>
<blockquote class="layui-elem-quote layui-text">帮助文档 ：<a href="https://doc.xiaoz.me/books/onenav" target="_blank">藏经阁</a></blockquote>
<blockquote class="layui-elem-quote layui-text">原著博客 ：<a href="https://www.xiaoz.me" target="_blank">www.xiaoz.me</a></blockquote>
<blockquote class="layui-elem-quote layui-text">社区支持 ：<a href="https://dwz.ovh/vd0bw" target="_blank" >dwz.ovh/vd0bw</a></blockquote>
<blockquote class="layui-elem-quote layui-text">分类图标 ：<a href="http://www.fontawesome.com.cn/faicons" target="_blank">fontawesome</a></blockquote>
<blockquote class="layui-elem-quote layui-text">Layui文档：
<a href="https://gitee.com/sentsin/layui"  target="_blank">gitee码云 </a>&ensp;
<a href="https://www.layui.site/doc/index.htm"  target="_blank">Layui文档 </a>&ensp;
<a href="https://www.layui.site/demo/form.html" target="_blank">Layui示例 </a>&ensp;
<a href="https://soultable.yelog.org/#/zh-CN/component/basic/cache" target="_blank">Layui示例2 </a>
<a href="https://gitee.com/saodiyang/layui-soul-table" target="_blank">Layui示例2的插件 </a>
<a href="https://www.layuion.com/demo/table.html" target="_blank">数据表格</a>
<a href="https://layer.itze.cn/" target="_blank">弹出层组件</a>
</blockquote>
<blockquote class="layui-elem-quote layui-text">Medoo文档：<a href="https://medoo.lvtao.net/1.2/doc.php" title="数据库框架" target="_blank">中文帮助手册</a>
<a href="https://medoo.lvtao.net/doc.where.php" title="数据库框架" target="_blank">中文帮助手册</a>
</blockquote>
<blockquote class="layui-elem-quote layui-text">XIcon项目：<a href="https://gitee.com/imlzw/xicon/tree/master" title="分类图标选择器" target="_blank">gitee</a></blockquote>
<blockquote class="layui-elem-quote layui-text">MDUI 文档：<a href="https://www.mdui.org/docs" title="前台用的框架" target="_blank">官方文档</a></blockquote>
<blockquote class="layui-elem-quote layui-text">百素主题：<a href="https://gitee.com/baisucode/onenav-theme/tree/master/templates/baisu" target="_blank">gitee</a></blockquote>
<blockquote class="layui-elem-quote layui-text">Webstack：<a href="https://github.com/WebStackPage/WebStackPage.github.io" target="_blank">github</a>&ensp;
<a href="https://webstack.cc/cn/index.html" target="_blank">官方</a></blockquote>
<blockquote class="layui-elem-quote layui-text">SimpleWeb主题：<a href="https://github.com/KrunkZhou/SimpleWebNavigation" target="_blank">github</a></blockquote>
<blockquote class="layui-elem-quote layui-text">hotkeys快捷键：<a href="https://github.com/jaywcjlove/hotkeys/" target="_blank">github </a>&ensp;
<a href="https://wangchujiang.com/hotkeys/" target="_blank">官方</a></blockquote>
<blockquote class="layui-elem-quote layui-text">getFavicon：<a href="https://github.com/owen0o0/getFavicon" target="_blank">官方文档</a></blockquote>

</div><!--开发文档End-->
</div>
</div>
<?php include_once('footer.php'); ?>
