<?php include_once('header.php'); ?>
<?php include_once('left.php'); ?>
<div class="layui-body ">
<!-- 内容主体区域 -->
<link href="<?php echo $libs?>/Font-awesome/4.7.0/css/font-awesome.css?v=4.7.0" rel="stylesheet"> 
<div class="layui-row content-body">
    <div class="layui-col-lg12">
        <div class="layui-inline layui-form">
        <label class="layui-form-label " style="width:60px;padding-left: 5px;padding-right: 5px;">所属分类:</label>
        <div class="layui-input-inline">
        <select id="fid" name="categorys" lay-filter="aihao" >
        <option value="0" selected="">全部</option>
<?php 
        $categorys = $db->select('on_categorys','*',["ORDER" =>  ["weight" => "DESC"]]);
        foreach ($categorys as $category) {
        echo '        <option value="'.$category['id'].'">'.$category['name']."</option>\n";
}?>
        </select>
        </div>
        </div>  
        
        <div class="layui-inline layui-form" >
            <label class="layui-form-label layui-hide-sm" style="width:60px;padding-left: 5px;padding-right: 5px;">关键字:</label>
            <div class="layui-input-inline">
                <input class="layui-input" name="keyword" id="link_keyword" placeholder='请输入标题或描述或URL' value=''autocomplete="off" >
            </div>
        </div>
        <div class="layui-btn-group ">
        <button class="layui-btn layui-btn " data-type="link_reload">搜索</button>
        </div>
        <table id="link_list" lay-filter="mylink"></table>
        <!-- 开启表格头部工具栏 <button class="layui-btn layui-btn-sm " lay-event="tiquan">提权</button> -->
        <script type="text/html" id="linktool">
            <div class="layui-btn-container">
            <button class="layui-btn layui-btn-sm layui-btn-danger" lay-event="getCheckData">删除选中</button>
            <button class="layui-btn layui-btn-sm " lay-event="MC">修改分类</button>
            <button class="layui-btn layui-btn-sm " lay-event="zhiding">置顶</button>
            <button class="layui-btn layui-btn-sm " lay-event="addlink">添加</button>
        </div>
        </script>
        <!-- 开启表格头部工具栏END -->
    </div>
    <script type="text/html" id="link_operate">
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del" onclick = "">删除</a>
    </script>
</div>
<!-- 内容主题区域END -->
</div>
<?php include_once('footer.php'); ?>