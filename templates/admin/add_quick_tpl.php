<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="renderer" content="webkit|ie-comp|ie-stand">
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
		<meta http-equiv="Cache-Control" content="no-transform">
		<meta name="applicable-device" content="pc,mobile">
		<meta name="MobileOptimized" content="width">
		<meta name="HandheldFriendly" content="true">
		<title>快速添加</title>
		<link rel="stylesheet" type="text/css" href="./templates/admin/static/add_quick_tpl.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo $libs?>/Layui/v2.6.8/css/layui.css" />
	</head>

	<body>
		<div class="quick-main">
			<div class="title">
				<i class="iconfont icon--_tianjia"></i>快速添加当前连接
			</div>
			<form class="layui-form">
				<div class="list">
					<input type="text" name="url" id="url" required  lay-verify="required" placeholder="URL" autocomplete="off">
				</div>
				<div class="list">
					<input type="text" name="title" id="title" required  lay-verify="required" placeholder="标题" autocomplete="off">
				</div>

				<div class="list">
					 <select name="fid" lay-verify="required" lay-search>
        <option value=""></option>
        <?php foreach ($categorys as $category) {
          # code...
        ?>
        <option <?php $fid = @$_GET['category']; if ($fid === $category['id'] ){echo 'selected';}?> value="<?php echo $category['id'] ?>"><?php echo ($category['fid'] == 0 ? "":"├ ").$category['name']; ?></option>
        <?php } ?>
      </select>
				</div>
				<div class="list list-2">
					<div class="li">
					权重
					<input type="text" name="weight" min = "0" max = "999" value = "0" required  lay-verify="required|number" autocomplete="off" >
					</div>
					<div class="li">
					是否私有
					<input type="checkbox" lay-skin="switch" lay-text="是|否" name="property" value = "1">
					</div>
				</div>
				<div class="list">
					<textarea name="description" id = "description" placeholder="请输入站点描述（选填）" ></textarea>
				</div>
				<div class="list-3">
					<button class="close">关闭</button>
					<button lay-submit lay-filter="add_link">添加</button>
				</div>

			</form>
		</div>
		<!--JQ-->
        <script src="<?php echo $libs?>/jquery/jquery-3.6.0.min.js" type="text/javascript" charset="utf-8"></script>
        <!--Layui-->
		<script src="<?php echo $libs?>/Layui/v2.6.8/layui.js" type="text/javascript" charset="utf-8"></script>
		<!--iconfont-->
		<link rel="stylesheet" type="text/css" href="<?php echo $libs?>/Other/font_2474757_xsupv5wqpn.css" />
		<script src="<?php echo $libs?>/Other/font_2474757_xsupv5wqpn.js" type="text/javascript" charset="utf-8"></script>
		<!--admin-->
		<!--js-->
		<script>
		var u = '<?php echo $u?>';
			layui.use('form', function() {
				var form = layui.form;
				form.render();
				//添加链接
				form.on('submit(add_link)', function(data) {
					$.post('./index.php?c=api&method=add_link&u='+u, data.field, function(data, status) {
						//如果添加成功 time是提示存在时间!
						if(data.code == 0) {
							layer.msg('保存成功', {
								icon: 1,
								time: 1
							}, function() {
								window.close();
							});

						} else {
							layer.msg(data.msg, {
								icon: 5
							});
						}
					});
					console.log(data.field) //当前容器的全部表单字段，名值对形式：{name: value}
					return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
				});
			});

			function getQueryVariable(variable) {
				var query = window.location.search.substring(1);
				var vars = query.split("&");
				for(var i = 0; i < vars.length; i++) {
					var pair = vars[i].split("=");
					if(pair[0] == variable) {
						return pair[1];
					}
				}
				return(false);
			};
			var urls = decodeURIComponent(getQueryVariable("url"));
			var titles = decodeURIComponent(getQueryVariable("title"));
			$('input#url').val(urls);
			$('input#title').val(titles);
			$('button.close').click(function() {
				window.close();
			});
		</script>
	</body>
</html>