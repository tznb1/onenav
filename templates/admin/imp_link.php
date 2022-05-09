<?php include_once('header.php'); ?>
<?php include_once('left.php'); ?>

<div class="layui-body">
<!-- 内容主体区域 -->

<div class="layui-row content-body">
    <div class="layui-col-lg6 layui-col-md-offset3">
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
    <legend>书签导入</legend>
    </fieldset> 
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
    <form class="layui-form layui-form-pane" >
        <div class="setting-msg"  id='guide' >第一步:请上传数据,支持db3数据库和HTML格式，使用前请参考<a href="https://doc.xiaoz.me/books/onenav-extend/page/6c834" target="_blank" rel = "nofollow">帮助文档</a></div>
    <div class="layui-form-item" style = "display:none;" id='filed'>
    <label class="layui-form-label">书签路径</label>
    <div class="layui-input-block">
      <input type="text" id = "filename" name="filename" value="" required  lay-verify="required" placeholder="请输入书签路径" autocomplete="off" class="layui-input">
    </div>
  </div>
  <div class="layui-form-item" style = "display:none;" id='fid'>
    <label class="layui-form-label">所属分类</label>
    <div class="layui-input-block">
      <select name="fid" lay-verify="" lay-search>
        <option value=""></option>
        <?php foreach ($categorys as $category) {
        ?>
        <option value="<?php echo $category['id'] ?>"><?php echo ($category['fid'] == 0 ? "":"├ ").$category['name']; ?></option>
        <?php } ?>
      </select>
    </div>
  </div>
   <div class="layui-form-item" style = "display:none;" id='AutoClass'>
    <div class="layui-inline">
      <label class="layui-form-label">自动分类</label>
      <div class="layui-input-inline" style="width: 70px;">
        <input lay-filter="AutoClass" type="checkbox" name="AutoClass" value = "1" lay-skin="switch" lay-text="是|否">
      </div>
      <div class="layui-form-mid layui-word-aux">自动创建分类目录</div>
    </div>
 </div> 
   <div class="layui-form-item" style = "display:none;" id='ADD_DATE'>
    <div class="layui-inline">
      <label class="layui-form-label">保留时间</label>
      <div class="layui-input-inline" style="width: 70px;">
        <input type="checkbox" name="ADD_DATE" value = "1" lay-skin="switch" lay-text="是|否">
      </div>
      <div class="layui-form-mid layui-word-aux">尝试保留浏览器书签的添加时间</div>
    </div>
 </div>
 <div class="layui-form-item" style = "display:none;" id='icon'>
    <div class="layui-inline">
      <label class="layui-form-label">提取图标</label>
      <div class="layui-input-inline" style="width: 70px;">
        <input type="checkbox" name="icon" value = "1" lay-skin="switch" lay-text="是|否">
      </div>
      <div class="layui-form-mid layui-word-aux">尝试提取浏览器书签的图标(仅支持小于2kb的png格式)</div>
    </div>
 </div>
   <div class="layui-form-item" style = "display:none;" id='property'>
    <div class="layui-inline">
      <label class="layui-form-label">是否私有</label>
      <div class="layui-input-inline" style="width: 70px;">
        <input  type="checkbox" name="property" value = "1" lay-skin="switch" lay-text="是|否">
      </div>
      <div class="layui-form-mid layui-word-aux" id="propertytxt">导入的链接将设为私有</div>
    </div>
 </div> 
   <div class="layui-form-item" style = "display:none;" id='all'>
    <div class="layui-inline">
      <label class="layui-form-label">保留属性</label>
      <div class="layui-input-inline" style="width: 70px;">
        <input type="checkbox" name="all" value = "1" lay-skin="switch" lay-text="是|否">
      </div>
      <div class="layui-form-mid layui-word-aux">将保留添加时间,修改时间,权重,点击数</div>
    </div>
 </div> 
  <div class="layui-form-item" style = "display:none;" id='imp_link'>
  <button class="layui-btn" lay-submit lay-filter="imp_link">开始导入</button>
  </div>
</form>
</div>

<div class="layui-col-lg6 layui-col-md-offset3">
    
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 50px;">
    <legend>书签导出</legend>
    </fieldset> 
    
    <blockquote class="layui-elem-quote" style="margin-top: 30px;">
    <a  target="_blank" style="cursor:pointer;"  rel = "nofollow" onclick = "export_bookmarks('db3')">导出数据库 ( 支持导入OneNav Extend ) &nbsp;>>&nbsp; 备份推荐用这个 </a>
    </blockquote>
    
    <blockquote class="layui-elem-quote" style="margin-top: 10px;">
    <a  target="_blank" style="cursor:pointer;"  rel = "nofollow" onclick = "export_bookmarks('html')">导出HTML ( 支持导入浏览器 / OneNav / OneNav Extend ) &nbsp;>>&nbsp; 导出支持层级,导入不支持</a>
    </blockquote>
</div>
</div>
<!-- 内容主题区域END -->
</div>
<?php include_once('footer.php'); ?>