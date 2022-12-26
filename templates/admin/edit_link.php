<?php include_once('header.php'); ?>
<?php include_once('left.php'); 
$iconUP = UGet('iconUP');?>
<div class="layui-body">
<!-- 内容主体区域 -->
<div class="layui-row content-body">
    <div class="layui-col-lg12">
    <form class="layui-form">
    <?php if($iconUP == 0 ) echo "<div class=\"setting-msg\"  >提示:  当前版本已支持上传链接图标啦,在网站管理找到图标上传并设为允许就可以啦,支持1M内的 jpg,png,svg,ico 文件 (该功能订阅可用)</div>"; ?>
    <div class="layui-form-item" style = "display:none;">
    <label class="layui-form-label">链接ID</label>
    <div class="layui-input-block">
      <input type="text" name="id" required  lay-verify="required" value = '<?php echo $id; ?>' autocomplete="off" class="layui-input">
    </div>
    </div>
    <div class="layui-form-item">
    <label class="layui-form-label">URL</label>
    <div class="layui-input-block">
      <input type="url" id = "url" name="url" value = "<?php echo $link['url']; ?>" required  lay-verify="required" placeholder="请输入有效链接" autocomplete="off" class="layui-input">
    </div>
  </div>
    <div class="layui-form-item">
    <label class="layui-form-label">备用URL</label>
    <div class="layui-input-block">
      <input type="url" id = "url_standby" name="url_standby" value = "<?php echo $link['url_standby']; ?>" placeholder="请输入备用链接，如果没有，请留空" autocomplete="off" class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">链接名称</label>
    <div class="layui-input-block">
      <input type="text" id = "title" name="title" value = "<?php echo $link['title']; ?>" required  lay-verify="required" placeholder="请输入链接名称" autocomplete="off" class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">所属分类</label>
    <div class="layui-input-block">
      <select name="fid" lay-verify="required" lay-search>
        <option value="<?php echo $link['fid'] ?>"><?php echo $cat_name; ?></option>
        <?php foreach ($categorys as $category) {
          # code...
        ?>
        <option value="<?php echo $category['id'] ?>"><?php  echo ($category['fid'] == 0 ? "":"├ ").$category['name']; ?></option>
        <?php } ?>
      </select>
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">权重</label>
    <div class="layui-input-block">
      <input type="number" name="weight" value = "<?php echo $link['weight']; ?>" min = "0" max = "999" value = "0" required  lay-verify="required|number" placeholder="权重越高，排名越靠前，范围为0-999" autocomplete="off" class="layui-input">
    </div>
  </div>
  
  <div class="layui-form-item">
    <label class="layui-form-label">图标URL</label>
    <div class="layui-input-block">
      <!--<input type="text" id = "iconurl" name="iconurl" value = "<?php echo $link['iconurl']; ?>" placeholder="自定义图标的URL地址，如果没有，请留空" autocomplete="off" class="layui-input">-->
      <textarea name="iconurl" id = "iconurl" placeholder="自定义图标的URL地址，如果没有，请留空" class="layui-textarea" rows = "1" style="min-height:50px"><?php echo $link['iconurl']; ?></textarea>
    </div>
  </div>

  <div class="layui-form-item" id='ico_preview' <?php echo $iconUP == 1 ? '':'style="display:none;"';?>;>
    <label class="layui-form-label">图标预览</label>
    <img class="layui-upload-img" id="icon" style="width: 80px; height: 80px;" >
  </div>

  <div class="layui-form-item" style = "display:none;">
    <label class="layui-form-label">图标内容</label>
    <div class="layui-input-block">
      <input type="text" name="icon_base64" id="icon_base64" autocomplete="off" class="layui-input">
    </div>
  </div>

  <div class="layui-form-item">
    <label class="layui-form-label">是否私有</label>
    <div class="layui-input-block">
      <input type="checkbox" name="property" value = "1" lay-skin="switch" <?php echo $link['checked'];?> lay-text="是|否">
    </div>
  </div>
  
  <div class="layui-form-item layui-form-text">
    <label class="layui-form-label">描述</label>
    <div class="layui-input-block">
      <textarea name="description" id = "description" placeholder="请输入内容" class="layui-textarea"><?php echo $link['description']; ?></textarea>
    </div>
  </div>
  <div class="layui-form-item">
    <div class="layui-input-block">
      <button class="layui-btn" lay-submit lay-filter="edit_link" id="edit_link">更新</button>
      <a href="javascript:;" class="layui-btn" onclick="get_link_info()">识别</a>
      <button type="button" class="layui-btn" id="up_icon" <?php echo $iconUP == 1 ? '':'style="display:none;"';?>>上传图标</button>
      <a href="javascript:;" class="layui-btn" onclick="del_icon('edit_link')" <?php echo $iconUP == 1 ? '':'style="display:none;"';?>>删除图标</a>
      <!--<button type="reset" class="layui-btn layui-btn-primary">重置</button>-->
    </div>
  </div>
  
  <div class="layui-form-item" <?php echo $iconUP == 1 ? '':'style="display:none;"';?>>
    <label class="layui-form-label">注意事项</label>
    <div class="layui-form-mid layui-word-aux">因前台主页有缓存加速机制,如果您上传图标后未更新请按CTRL+F5刷新!<br />图标格式:jpg|png|svg|ico  大小上限:1M</div>
  </div>
  
</form>
    </div>
</div>
<!-- 内容主题区域END -->
</div>
<?php include_once('footer.php'); 
if($iconUP != 1 ) exit;
?>

<script>
    var icourl = $("#iconurl").val();
    if(icourl != ''){
        if(icourl.substr(0,5) =='data:') {
            $('#icon').attr('src',icourl) ;
        }else{
            $('#icon').attr('src',icourl + '?t=' + Math.random() ) ;
        }
        
    }
</script>
