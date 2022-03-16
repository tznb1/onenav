<?php include_once('header.php'); ?>
<?php include_once('left.php'); ?>
<div class="layui-body">
<!-- 内容主体区域 -->
<link href="<?php echo $libs?>/Font-awesome/4.7.0/css/font-awesome.css?v=4.7.0" rel="stylesheet"> 
<div class="layui-row content-body">
    <div class="layui-col-lg12">
    <form class="layui-form">
    <div class="layui-form-item" style = "display:none;">
    <label class="layui-form-label">分类ID</label>
    <div class="layui-input-block">
      <input type="text" name="id" required  lay-verify="required" value = '<?php echo $id; ?>' autocomplete="off" class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">分类名称</label>
    <div class="layui-input-block">
      <input type="text" name="name" required  lay-verify="required" value = '<?php echo strip_tags( $category['name']); ?>' placeholder="请输入分类名称" autocomplete="off" class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">分类图标</label>
    <div class="layui-input-block">
      <input type="text" name="Icon" id="demo2"  class="hide" value="<?php if( preg_match('/<i class="fa (.+)"><\/i>/i',$category['name'],$matches) == 1){echo $matches[1];}else {echo $category['Icon'];}//使用正则匹配名称,如果有图标就提取出来,没有就直接读取图标字段?>" >
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">权重</label>
    <div class="layui-input-block">
      <input type="number" name="weight" min = "0" max = "999" value = "<?php echo $category['weight']; ?>" required  lay-verify="required|number" placeholder="权重越高，排名越靠前，范围为0-999" autocomplete="off" class="layui-input">
    </div>
  </div>
  
  
  <div class="layui-form-item">
    <label class="layui-form-label">是否私有</label>
    <div class="layui-input-block">
      <input type="checkbox" name="property" value = "1" lay-skin="switch" <?php echo $category['checked']; ?> lay-text="是|否">
    </div>
  </div>
  
  <div class="layui-form-item layui-form-text">
    <label class="layui-form-label">描述</label>
    <div class="layui-input-block">
      <textarea name="description" placeholder="请输入内容" class="layui-textarea"><?php echo $category['description']; ?></textarea>
    </div>
  </div>
  <div class="layui-form-item">
    <div class="layui-input-block">
      <button class="layui-btn" lay-submit lay-filter="edit_category">更新</button>
      <button type="reset" class="layui-btn layui-btn-primary">重置</button>
    </div>
  </div>
</form>
    </div>
    
</div>
<!-- 内容主题区域END -->
</div>
  
<?php include_once('footer.php'); ?>
<script>
    layui.config({
            base: '<?php echo $libs?>'
        }).extend({
            xIcon: '/xIcon/xIcon'
        });
        
    layui.use(['xIcon'], function () {
        var xIcon = layui.xIcon;
		
        xIcon.render({
            // 选择器，推荐使用input
            elem: '#demo1',
            // xIcon组件目录路径，用于加载其它相关文件
            base: '<?php echo $libs?>/xIcon/',
            // 数据类型：layui/awesome，推荐使用layui
            type: 'layui,awesome',
            // 是否开启搜索：true/false，默认true
            search: true,
            // 是否开启分页：true/false，默认true
            page: false,
            // 每页显示数量，默认100
            limit: 100,
            // 点击回调
            click: function (data) {
                console.log(data);
            //    if (data.icon.substr(0,3) =='lay'){
            //       document.getElementsByName("name")[0].value = '<i class="layui-icon '+data.icon+'"></i>';
            //    } else {
            //       document.getElementsByName("name")[0].value = '<i class="fa '+data.icon+'"></i>';
            //    }    
            },
            // 渲染成功后的回调
            success: function (d) {
                console.log(d);
            }
        });
        xIcon.render({
            // 选择器，推荐使用input
            elem: '#demo2',
            // xIcon组件目录路径，用于加载其它相关文件
            base: '<?php echo $libs?>/xIcon/',
            // 数据类型：layui/awesome，推荐使用layui
            type: 'layui,awesome',
            // 是否开启搜索：true/false，默认true
            search: true,
            // 是否开启分页：true/false，默认true
            page: false,
            // 每页显示数量，默认100
            limit: 100,
            // 点击回调
            click: function (data) {
                console.log(data);
            //    if (data.icon.substr(0,3) =='lay'){
            //       document.getElementsByName("name")[0].value = '<i class="layui-icon '+data.icon+'"></i>';
            //    } else {
            //       document.getElementsByName("name")[0].value = '<i class="fa '+data.icon+'"></i>';
            //    }
               
                
            },
            // 渲染成功后的回调
            success: function (d) {
                console.log(d);
            }
        });
    });
</script>