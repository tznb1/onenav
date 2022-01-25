<?php include_once('header.php'); ?>
<?php include_once('left.php'); ?>

<div class="layui-body">
<!-- 内容主体区域 -->
<div class="layui-row content-body">
    <div class="layui-col-lg6 layui-col-md-offset3">
      <div class="setting-msg">仅支持 <em>.html和.db3</em> 格式导入，使用前请参考<a href="https://dwz.ovh/ij3mq" target="_blank" rel = "nofollow">帮助文档</a> 。</div>
    <!-- 上传 -->
    <div class="layui-upload-drag" id="up_html">
      <i class="layui-icon layui-icon-upload"></i>
      <p>点击上传，或将书签拖拽到此处</p>
      <div class="layui-hide" id="file">
        <hr>
        <img src="" alt="上传成功后渲染" style="max-width: 100%">
      </div>
    </div>
    <!-- 上传END -->
    <form class="layui-form layui-form-pane">
    <div class="layui-form-item">
    <label class="layui-form-label">书签路径</label>
    <div class="layui-input-block">
      <input type="text" id = "filename" name="filename" required  lay-verify="required" placeholder="请输入书签路径" autocomplete="off" class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">所属分类</label>
    <div class="layui-input-block">
      <select name="fid" lay-verify="" lay-search>
        <option value=""></option>
        <?php foreach ($categorys as $category) {
        ?>
        <option value="<?php echo $category['id'] ?>"><?php echo $category['name']; ?></option>
        <?php } ?>
      </select>
    </div>
  </div>
   <div class="layui-form-item">
    <div class="layui-inline">
      <label class="layui-form-label">是否私有</label>
      <div class="layui-input-inline" style="width: 70px;">
        <input type="checkbox" name="property" value = "1" lay-skin="switch" lay-text="是|否">
      </div>
      <div class="layui-form-mid layui-word-aux">是:导入的链接将设为私有,导入db3时无效!</div>
    </div>
 </div> 
   <div class="layui-form-item">
    <div class="layui-inline">
      <label class="layui-form-label">保留属性</label>
      <div class="layui-input-inline" style="width: 70px;">
        <input type="checkbox" name="all" value = "1" lay-skin="switch" lay-text="是|否">
      </div>
      <div class="layui-form-mid layui-word-aux">是:将保留添加时间,修改时间,权重,点击数(仅对导入db3时有效)!</div>
    </div>
 </div> 
  <div class="layui-form-item">
  <button class="layui-btn" lay-submit lay-filter="imp_link">开始导入</button>
  </div>
</form>
    </div>
    
</div>
<!-- 内容主题区域END -->
</div>
  
<?php include_once('footer.php'); ?>