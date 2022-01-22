<!DOCTYPE html>
<html lang="zh-cn" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8" />
	<?php if (isset($_GET['u'])){
	    echo "<title>OneNav登录</title>";}
	else{
	    echo "<title>OneNav登录与注册</title>";
	} ?>
	
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel='stylesheet' href='<?php echo $libs?>/Layui/v2.5.4/css/layui.css'>
	  <link rel='stylesheet' href='./templates/admin/static/style.css'>
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
		<div class="login-logo">
		    	<?php if (isset($_GET['u'])){
	    echo "<h1>OneNav登录</h1>";}
	else{
	    echo "<h1>OneNav登录与注册</h1>";
	} ?>
			
		</div>
		<div class="layui-col-lg4 layui-col-md-offset4" style ="margin-top:4em;">
		<form class="layui-form layui-form-pane" action="">
  <div class="layui-form-item">
    <label class="layui-form-label"><i class="layui-icon layui-icon-form"></i></label>
    <div class="layui-input-block">
      <input type="text" name="u" required value="<?php echo $u; ?>" lay-verify="required" placeholder="数据库名" autocomplete="off" class="layui-input">
    </div>
  </div>
  
  <div class="layui-form-item">
    <label class="layui-form-label"><i class="layui-icon layui-icon-username"></i></label>
    <div class="layui-input-block">
      <input type="text" name="user" required value="<?php echo $u; ?>" lay-verify="required" placeholder="请输入用户名" autocomplete="off" class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label"><i class="layui-icon layui-icon-password"></i></label>
    <div class="layui-input-block">
      <input type="password" name="password" required  lay-verify="required" placeholder="请输入密码" autocomplete="off" class="layui-input">
    </div>
  </div>
  
  <div class="layui-form-item">
    <button class="layui-btn" lay-submit lay-filter="login" style = "width:100%;">登录</button>
  </div>
 <?php if(($reg ==1 or $reg ==2) && (strip_tags(@$_GET['u'])=='' || $username =='') ){ echo '<div class="layui-form-item">'."\n".'<button class="layui-btn" lay-submit lay-filter="register" style = "width:100%;">注册</button>'."\n".'</div>';}?>

</form>
		</div>
	</div>
</div>


<script src = '<?php echo $libs?>/jquery/jquery-3.6.0.min.js'></script>
<script src = '<?php echo $libs?>/Layui/v2.5.4/layui.js'></script>
<script src="./templates/admin/static/embed.js"></script>
</body>
</html>