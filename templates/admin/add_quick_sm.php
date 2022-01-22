<?php include_once('header.php'); ?>
<?php include_once('left.php'); ?>
<?php function get_url() {
  $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
  $php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
  $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
 // $relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : $path_info);
  return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
} ?>
<div class="layui-body">
<!-- 内容主体区域 -->
<div class="layui-row content-body">
    <div class="layui-col-lg12">
    <form class="layui-form layui-form-pane">
  <!-- <div style = "margin-bottom:1.6em;"><h3>自定义JavaScript，仅对默认主题有效：</h3></div> -->
  <div class="layui-form-item layui-form-text">
  <label class="layui-form-label">快速添加当前连接/一键加入书签使用说明：添加收藏>URL填下面的内容!</label>
  <textarea name="content" rows="20" required placeholder="提示内容" class="layui-textarea"><?php echo "javascript: var url = location.href;\nvar title = document.title;\nvoid(open('".get_url().'/index.php?c=admin&page=add_quick_tpl&u='.$u."&category=0&url=' + encodeURIComponent(url) + '&title=' + encodeURIComponent(title), ".'"_blank", "toolbar=yes, location=yes, directories=no, status=no, menubar=yes, scrollbars=yes, resizable=no, copyhistory=yes, left=200,top=200,width=400, height=460"));' ; ?></textarea>
  </div>
  <div class="layui-form-item">
    <div>
      <!-- <button class="layui-btn" lay-submit lay-filter="add_js">添加</button> -->
      <!-- <button type="reset" class="layui-btn layui-btn-primary">重置</button> -->
    </div>
  </div>
</form>
    </div>
</div>
<!-- 内容主题区域END -->
</div>
<?php include_once('footer.php'); ?>