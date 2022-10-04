<!DOCTYPE html>
<html lang="zh-cn">
<head>
	<meta charset="utf-8" />
	<title>申请收录</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel='stylesheet' href='<?php echo $libs?>/Layui/v2.6.8/css/layui.css'>
	<link rel='stylesheet' href='./templates/admin/static/style.css?v=<?php echo $version;?>'>
	<style>
	body{
		 /*background:url(templates/admin/static/bg.jpg); */
		background-color:rgba(0, 0, 51, 0.8);
		}
	</style>
</head>
<body>
<div class="layui-container">
<div class="layui-row">
<div class="login-logo">
<h1>申请收录</h1>
</div>
<div class="layui-col-lg6 layui-col-md-offset3" style ="margin-top:3em;">
<form class="layui-form layui-form-pane" action="" lay-filter="apply">
    
  <div class="layui-form-item" style="color: #fbfbfb;">
    <?php echo getconfig('apply_Notice');?>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">网站标题</label>
    <div class="layui-input-block">
      <input type="text" name="title" required value="" lay-verify="required" placeholder="例如 百度一下" autocomplete="off" class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">网站链接</label>
    <div class="layui-input-block">
      <input type="url" name="url" required value="" lay-verify="required|url" placeholder="例如 https://www.baidu.com" autocomplete="off" class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">网站图标</label>
    <div class="layui-input-block">
      <input type="url" name="iconurl" required value="" lay-verify="url" placeholder="例如 https://www.baidu.com/favicon.ico" autocomplete="off" class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">网站描述</label>
    <div class="layui-input-block">
      <input type="text" name="description"  value=""  placeholder="例如 搜索引擎" autocomplete="off" class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">网站分类</label>
    <div class="layui-input-block">
      <select name="category_id" lay-verify="required" lay-search>
        <option value=""></option>
        <?php foreach ($categorys as $category) {
        ?>
        <option value="<?php echo $category['id'] ?>"><?php echo ($category['fid'] == 0 ? "":"├ ").$category['name']; ?></option>
        <?php } ?>
      </select>
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">联系邮箱</label>
    <div class="layui-input-block">
      <input type="text" name="email"  value="" lay-verify="required" placeholder="例如 admin@qq.com" autocomplete="off" class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <button class="layui-btn" lay-submit lay-filter="submit" style = "width:100%;">提交</button>
  </div>
  </form>
  <?php if( is_login2() ) { echo 
  '<div class="layui-form-item"><button class="layui-btn" lay-submit lay-filter="test" style = "width:100%;">生成测试数据 (自己登录时才显示此按钮)</button></div>'
  ;} ?> 
</div>
</div>
</div>
<script src = '<?php echo $libs?>/jquery/jquery-3.6.0.min.js'></script>
<script src = '<?php echo $libs?>/jquery/jquery.md5.js'></script>
<script src = '<?php echo $libs?>/Layui/v2.6.8/layui.js'></script>
<script>
layui.use(['form'], function(){
    var form = layui.form;
    form.on('submit(submit)', function(data){
        $.post('./index.php?c=apply&u=<?php echo $u ?>',data.field,function(re,status){
            if(re.code == 0) {
                layer.msg(re.msg, {icon: 1});
            }else{
                layer.msg(re.msg, {icon: 5});
            }
        });
    return false; 
    });<?php if( is_login2() ) { echo '
    //生成测试数据
    form.on("submit(test)", function(data){
        form.val("apply", {
                "title": "百度一下"
                ,"url": "https://"+ Math.round(new Date()) +".baidu.com"
                ,"iconurl": "https://www.baidu.com/favicon.ico"
                ,"description": "搜索引擎"
                ,"email": "admin@qq.com"
            });
    return false; 
    });' ;} ?> 
});
</script>
</body>
</html>