<?php include_once('header.php'); ?>
<?php include_once('left.php'); ?>
<div class="layui-body" style="padding-bottom: 0px;">
<!-- 内容主体区域 -->
<div class="layui-row content-body">
    <div class="layui-col-lg12">
        <label class="layui-form"></label>
        <div class="layui-inline" >
        <input class="layui-input" name="keyword" id="C_keyword" placeholder='请输入分类名称或描述' value=''autocomplete="off" style="height: 32px;">
        </div>
        <div class="layui-btn-group">
        <button class="layui-btn layui-btn-sm " data-type="C_reload">搜索</button>
        <button class="layui-btn layui-btn-sm " data-type="addcategory">添加</button>
        <button class="layui-btn layui-btn-sm layui-btn-danger"   data-type="C_Delete">删除选中</button>
        <button class="layui-btn layui-btn-sm layui-btn-danger"   data-type="C_ForceDel">强行删除</button></div>
        <table id="category_list" lay-filter="category_list"></table>
    </div>
    <script type="text/html" id="nav_operate">
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del" onclick = "">删除</a>
    </script>
</div>
<!-- 内容主题区域END -->
</div>
<?php include_once('footer.php'); ?>