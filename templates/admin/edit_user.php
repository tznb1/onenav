<?php 
include_once('header.php');
include_once('left.php');
$session =getconfig('session');
?>

<div class="layui-body">
<!-- 内容主体区域 -->
<div class="layui-row content-body layui-show layui-form layui-form-pane" lay-filter="EditUser">
<div class="layui-col-lg12">
 <div class="layui-form-item">
    <div class="layui-inline">
      <label class="layui-form-label">邮箱</label>
      <div class="layui-input-inline">
        <input type="text" name="Email"  value = '' placeholder='<?php if (getconfig('Email') !=''){echo getconfig('Email');}else{echo"未设置";}?>'  autocomplete="off" class="layui-input">
      </div>
      <div class="layui-form-mid layui-word-aux">找回密码时请提供邮箱!</div>
    </div>
 </div>
 <div class="layui-form-item">
    <div class="layui-inline">
      <label class="layui-form-label">令牌</label>
      <div class="layui-input-inline">
        <input type="text" name="NewToken" id="NewToken" value = '' placeholder='<?php if (getconfig('Token') !=''){echo'已设置,为了安全不显示!';}else{echo"未设置";}?>'  autocomplete="off" class="layui-input">
      </div>
      <div class="layui-form-mid layui-word-aux">API接口使用的Token,通常不需要配置,等同于账号密码,请勿泄露!</div>
    </div>
 </div>
 <div class="layui-form-item">
    <div class="layui-inline">
      <label class="layui-form-label">原密码</label>
      <div class="layui-input-inline">
        <input type="password" name="password" id="password" value='' placeholder='原密码' autocomplete="off" class="layui-input">
      </div>
      <div class="layui-form-mid layui-word-aux">修改本页信息需要输入原密码!</div>
    </div>
 </div>
 <div class="layui-form-item">
    <div class="layui-inline">
      <label class="layui-form-label">新密码</label>
      <div class="layui-input-inline">
        <input type="password" name="newpassword"  value = '' placeholder='新密码'  autocomplete="off" class="layui-input">
      </div>
      <div class="layui-form-mid layui-word-aux">不修改请留空!</div>
    </div>
 </div> 
 <div class="layui-form-item">
    <div class="layui-inline">
      <label class="layui-form-label">二级密码</label>
      <div class="layui-input-inline">
        <input type="password" name="Pass2"  value = '<?php echo getconfig('Pass2');?>' placeholder='二级密码'  autocomplete="off" class="layui-input">
      </div>
      <div class="layui-form-mid layui-word-aux">设置后访问后台时需要输入这个密码!不需要则留空!</div>
    </div>
 </div> 
</div>
 <div class="layui-form-item">
    <div class="layui-inline">
      <label class="layui-form-label">登录入口</label>
     <div class="layui-input-inline">
      <select id="Elogin" name="Elogin" >
        <option value="0" >保持登录入口</option>
        <option value="1" >重设登录入口</option>
      </select>
      </div>
      <div class="layui-form-mid layui-word-aux">接口泄漏时可以选择重设登陆入口,更换后请及时保存!</div>
    </div>
 </div>
 <div class="layui-form-item">
    <div class="layui-inline">
      <label class="layui-form-label">Key安全</label>
     <div class="layui-input-inline">
      <select id="Skey" name="Skey" >
        <option value="0" <?php if($Skey==0){echo'selected=""';}?>>0级(无)</option>
        <option value="1" <?php if($Skey==1){echo'selected=""';}?>>1级(UA)</option>
        <option value="2" <?php if($Skey==2){echo'selected=""';}?>>2级(UA + IP )</option>
      </select>
      </div>
      <div class="layui-form-mid layui-word-aux">保持登陆状态的Key算法,更高级别的算法可以降低被窃取的风险!</div>
    </div>
 </div>
 <div class="layui-form-item">
    <div class="layui-inline">
      <label class="layui-form-label">HttpOnly</label>
     <div class="layui-input-inline">
      <select id="HttpOnly" name="HttpOnly" >
        <option value="0" >禁止HttpOnly</option>
        <option value="1" <?php if (getconfig('HttpOnly') == '1'){echo'selected=""';}?>>使用HttpOnly(荐)</option>
      </select>
      </div>
      <div class="layui-form-mid layui-word-aux">使用HttpOnly可以防止跨站脚本窃取Cookie</div>
    </div>
 </div>
 <div class="layui-form-item">
    <div class="layui-inline">
      <label class="layui-form-label">登陆保持</label>
     <div class="layui-input-inline">
      <select id="session" name="session" lay-filter="session">
        <option value="0" <?php if($session=='0'){echo'selected=""';}?>>浏览器关闭时</option>
        <option value="7" <?php if($session=='7'){echo'selected=""';}?>>7天</option>
        <option value="15" <?php if($session=='15'){echo'selected=""';}?>>15天</option>
        <option value="30" <?php if($session=='30'){echo'selected=""';}?>>30天</option>
        <option value="60" <?php if($session=='60'){echo'selected=""';}?>>60天</option>
        <option value="90" <?php if($session=='90'){echo'selected=""';}?>>90天</option>
        <option value="180" <?php if($session=='180'){echo'selected=""';}?>>180天</option>
        <option value="360" <?php if($session=='360'){echo'selected=""';}?>>360天</option>
      </select>
      </div>
      <div class="layui-form-mid layui-word-aux">登陆后保持的时间</div>
    </div>
 </div>
 <div class="layui-input-block" <?php if($Duser==$u && empty($CookieU) ){echo 'style = "display:none;"';}?>>
  <input type="checkbox" name="DefaultHomePage" lay-filter="DefaultHomePage" lay-skin="primary" title="设为默认主页" >
 </div>
 <!--</div>-->
  <div class="layui-form-item">
    <div class="layui-input-block">
      <button class="layui-btn" lay-submit lay-filter="Gtoken">生成令牌</button>
      <button class="layui-btn" lay-submit lay-filter="edit_user">保存配置</button>
    </div>
  </div>
</div>
</div>
</div>
<!-- 内容主题区域END -->
</div>
<?php $md5=true; include_once('footer.php'); ?>