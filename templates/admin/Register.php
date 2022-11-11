<!DOCTYPE html>
<html lang="zh-cn">
<head>
	<meta charset="utf-8" />
	<title>OneNav注册账号</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel='stylesheet' href='<?php echo $libs?>/Layui/v2.6.8/css/layui.css'>
	<link rel='stylesheet' href='./templates/admin/static/style.css?v=<?php echo $version;?>'>
	<style>
	body{
		/* background:url(templates/admin/static/bg.jpg); */
		background-color:rgba(0, 0, 51, 0.8);
		}
	</style>
</head>
<body>
<div class="layui-container">
<div class="layui-row">
<div class="login-logo"><h1>OneNav注册账号</h1></div>
<div class="layui-col-lg4 layui-col-md-offset4" style ="margin-top:4em;">
<form class="layui-form layui-form-pane" action="">
  <div class="layui-form-item">
    <label class="layui-form-label">账号</i></label>
    <div class="layui-input-block">
      <input type="text" name="user" required value="" lay-verify="required" placeholder="请输入账号" autocomplete="off" class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">密码</label>
    <div class="layui-input-block">
      <input type="password" name="pass" required  value="" lay-verify="required" placeholder="请输入密码" autocomplete="off" class="layui-input">
    </div>
  </div>
<div class="layui-form-item">
    <label class="layui-form-label">确认密码</label>
    <div class="layui-input-block">
      <input type="password" name="pass2" required value=""  lay-verify="required" placeholder="请再次输入密码" autocomplete="off" class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">Email</label>
    <div class="layui-input-block">
      <input type="text" name="Email" required value=""  lay-verify="email" placeholder="请输入邮箱" autocomplete="off" class="layui-input">
    </div>
  </div><?php if($reg == '2'){ ?>
  <div class="layui-form-item" style = "display:none;">
    <label class="layui-form-label">注册码</label>
    <div class="layui-input-block">
      <input type="text" name="key" required value="<?php echo Get('key'); ?>"  placeholder="请输入注册码" autocomplete="off" class="layui-input">
    </div>
  </div>
  <div class="layui-form-item" style = "display:none;">
    <label class="layui-form-label">邀请者</label>
    <div class="layui-input-block">
      <input type="text" name="Inviter" required value="<?php echo Get('u'); ?>"  placeholder="请输入注册码" autocomplete="off" class="layui-input">
    </div>
  </div><?php }?>
 <button class="layui-btn" lay-submit lay-filter="register" style = "width:100%;">注册</button>
</form>
<?php 
    //若为默认值则显示注册入口
    if($login === 'login'){
    echo '<p style="width: 96%; margin-top: 10px;"><a href="?c=login" style="color: #fffbfb;" class="fl">已有账号？立即登录</a>';
    $Get_Invitation = UGet('Get_Invitation');
    if($reg == 2 && !empty($Get_Invitation)){echo '<a style="color: #fffbfb;float:right" class="fl" onclick = "Get_Invitation(\''.base64_encode($Get_Invitation).'\')">获取邀请码</a>';}
    echo '</p>';
    }
?>
</div>
</div>
</div>
<script src = '<?php echo $libs?>/jquery/jquery-3.6.0.min.js'></script>
<script src = '<?php echo $libs?>/jquery/jquery.md5.js'></script>
<script src = '<?php echo $libs?>/Layui/v2.6.8/layui.js'></script>
<script>
layui.use(['form'], function(){
    var form = layui.form;
    form.on('submit(register)', function(data){
        if(!/^[A-Za-z0-9]{4,13}$/.test(data.field.user)){
            layer.msg('账号只能是4到13位的数字和字母!', {icon: 5});
            return false;
        }else if(data.field.pass != data.field.pass2){
            layer.msg('两次输入的密码不一致', {icon: 5});
            return false;
        }else if(data.field.pass.length<8){
            layer.msg('密码长度不能小于8个字符!', {icon: 5});
            return false;
        }
        data.field.pass = $.md5(data.field.pass);
        delete  data.field.pass2;
        $.post('./index.php?c=<?php echo $c; ?>',data.field,function(data,status){
            if(data.code == 0){
                window.location.href = './?u='+data.user;
            }else{
                layer.msg(data.msg, {icon: 5});
            }
        });
        return false;
    });
});

//获取邀请码
function Get_Invitation($base64) {
    var content =decodeURIComponent(escape(window.atob($base64)));
    if (content.substr(0,4) =='http'){
        window.location.href = content;
    }else{
        layer.open({title:'获取邀请码',content:content});
    }
}

</script>
</body>
</html>