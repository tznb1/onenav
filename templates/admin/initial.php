<?php 
//如果有原版数据库
if( file_exists('./data/onenav.db3') && file_exists('./data/config.php') ){
    require ('./data/config.php');//载入配置(和数据库)
    $USER = $site_setting['user'];
    $PASS = $site_setting['password'];
    $site_setting['Email']      =   EMAIL;
    $site_setting['password']   =   PASSWORD;
    $Email = $site_setting['Email'];
    $xiaoz = true ; //标注这是原版数据库!
}else{
    $USER = 'admin';
    $PASS = 'admin';
    $Email = '';
}

?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
	<meta charset="utf-8" />
	<title>OneNav安装引导</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel='stylesheet' href='<?php echo $libs?>/Layui/v2.6.8/css/layui.css'>
	<style>
	    body{ background-color:rgba(0, 0, 51, 0.8); }
	    .login-logo h1 { color:#FFFFFF; text-align: center; }
	    .login-logo { max-width: 400px; height: auto; margin-left: auto; margin-right: auto; margin-top:5em; }
	</style>
</head>
<body>
<div class="layui-container">
<div class="layui-row">
<div class="login-logo"><h1>OneNav安装引导</h1></div>
<div class="layui-col-lg4 layui-col-md-offset4" style ="margin-top:4em;">
<form class="layui-form layui-form-pane" action="">
  <div class="layui-form-item">
    <label class="layui-form-label">管理员账号</i></label>
    <div class="layui-input-block">
      <input type="text" name="user" required value="<?php echo $USER; ?>" lay-verify="required" <?php if($xiaoz) {echo 'readonly="readonly"';} ?> placeholder="请输入账号" autocomplete="off" class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">管理员密码</label>
    <div class="layui-input-block">
      <input type="text" name="pass" required  value="<?php echo $PASS; ?>" lay-verify="required" <?php if($xiaoz) {echo 'readonly="readonly"';} ?> placeholder="请输入密码" autocomplete="off" class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">Email</label>
    <div class="layui-input-block">
      <input type="text" name="Email" required value="<?php echo $Email; ?>" lay-verify="required|email"  placeholder="请输入邮箱"  autocomplete="off" class="layui-input">
    </div>
  </div>
<?php if( $xiaoz ){ 
    echo '<div class="layui-form-mid layui-word-aux">检测到原版数据库, 支持(含): 0.9.23 以下的版本<br /></div>';
    echo '<div class="layui-form-mid layui-word-aux">安装方式：升级安装 (保留主题外的全部数据)<br /></div>';
}else{
    echo '<div class="layui-form-mid layui-word-aux">安装方式：全新安装 &ensp;&ensp;&ensp;&ensp;&ensp;</div>';
}
?>
 <div class="layui-form-mid layui-word-aux">推荐配置：Nginx-1.20 +&ensp;PHP-7.4 </div>
 <button class="layui-btn" lay-submit lay-filter="register" style = "width:100%;">开始安装</button>
</form>

</div>
</div>
</div>
<script src = '<?php echo $libs?>/jquery/jquery-3.6.0.min.js'></script>
<script src = '<?php echo $libs?>/Layui/v2.6.8/layui.js'></script>
<script>
layui.use(['form'], function(){
    var form = layui.form;
    form.on('submit(register)', function(data){
        if(!/^[A-Za-z0-9]{3,13}$/.test(data.field.user)){
            layer.msg('账号只能是3到13位的数字和字母!', {icon: 5});
            return false;
        }else if(data.field.pass.length<3){
            //这里为了兼容原版降低了限制!
            layer.msg('密码长度不能小于5个字符!', {icon: 5});
            return false;
        }
        $.post('./index.php?retain=default',data.field,function(Re,status){
            if(Re.code == 0){
                open_msg(Re.user,Re.pass);
            }else{
                if( Re.code == -1002){
                    layer.confirm(Re.msg, {btn: ['保留', '不保留'],btn2: function(index, btn2){
                        $.post('./index.php?retain=no',data.field,function(Re,status){
                            if(Re.code == 0){
                                open_msg(Re.user,Re.pass);
                            }else{
                                layer.msg(Re.msg, {icon: 5});
                            }
                        });
                    }}, function(btn2){
                        $.post('./index.php?retain=yes',data.field,function(Re,status){
                            if(Re.code == 0){
                                open_msg(Re.user,Re.pass);
                            }else{
                                layer.msg(Re.msg, {icon: 5});
                            }
                        });
                    });
                }else{
                    layer.msg(Re.msg, {icon: 5});
                }
                
            }
        });
        return false;
    });
});

function open_msg(u,p){
    layer.closeAll();
    layer.open({ //弹出结果
    type: 1
    ,title: '安装成功'
    ,area: ['230px', '220px']
    ,maxmin: false
    ,shadeClose: false
    ,resize: false
    ,closeBtn: 0
    ,content: '<div style="padding: 15px;">管理员账号: '+u+'<br>管理员密码: '+p+'<br><h3><a href="?c=admin&u='+u+'" style="color: #0000FF;" class="fl">  <br> >>点我进入后台</a></h3><h3><a href="?u='+u+'" style="color: #0000FF;" class="fl">  <br> >>点我进入首页</a></h3></div>'
    
    });
}
</script>
</body>
</html>