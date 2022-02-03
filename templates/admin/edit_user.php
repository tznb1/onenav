<?php include_once('header.php'); ?>
<?php include_once('left.php'); ?>
<div class="layui-body">
<!-- 内容主体区域 -->
<div class="layui-row content-body layui-show layui-form layui-form-pane">
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
        <input type="text" name="password" lay-verify="required" value='<?php//echo getconfig('password');?>' placeholder='原密码' autocomplete="off" class="layui-input">
      </div>
      <div class="layui-form-mid layui-word-aux">修改本页信息需要输入原密码!</div>
    </div>
 </div>
 <div class="layui-form-item">
    <div class="layui-inline">
      <label class="layui-form-label">新密码</label>
      <div class="layui-input-inline">
        <input type="text" name="newpassword"  value = '' placeholder='新密码'  autocomplete="off" class="layui-input">
      </div>
      <div class="layui-form-mid layui-word-aux">不修改请留空!</div>
    </div>
 </div> 
</div>  
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