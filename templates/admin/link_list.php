<?php include_once('header.php'); ?>
<?php include_once('left.php'); ?>
<div class="layui-body " style="padding-bottom: 0px;">
<!-- 内容主体区域 -->
<div class="layui-row content-body">
    <div class="layui-col-lg12">
        <div class="layui-inline layui-form">
        <label class="layui-form-label " style="width:60px;padding-left: 5px;padding-right: 5px;">所属分类:</label>
        <div id="fidmsg" class="layui-input-inline">
        <select id="fid" name="categorys" lay-search >
        <option value="0" selected="">全部</option>
        <optgroup label="用户分类">
<?php 
        $categorys = get_category();
        foreach ($categorys as $category) {
            if($category['fid'] == 0){
                echo '        <option value="'.$category['id'].'">'.$category['name']."</option>\n";
            }else{
                echo '        <option value="'.$category['id'].'">├ '.$category['name']."</option>\n";
            }
        
}?>
        </optgroup>
        </select>
        </div>
        </div>  
        
        <div class="layui-inline layui-form">
        <label class="layui-form-label " style="width:60px;padding-left: 5px;padding-right: 5px;">所属标签:</label>
        <div id="tagidmsg" class="layui-input-inline">
        <select id="tagid" name="tagid" lay-search >
        <option value="-1" selected="">全部</option>
        <option value="0" >无标签</option>
        <optgroup label="用户标签">
<?php 
        $tags = get_tags();
        foreach ($tags as $tag) {
            echo '        <option value="'.$tag['id'].'">'.$tag['name']."</option>\n";
        
}?>
        </optgroup>
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
        </div>&emsp;
        <span id = "testing" style = "display:none;">测试中...</span>
        <span id = "subscribe" style = "display:none;"><?php echo is_subscribe(true)?'1':'0' ?></span>
        <table id="link_list" lay-filter="mylink"></table>
        <script type="text/html" id="linktool">
            <div class="layui-btn-container">
            <button class="layui-btn layui-btn-sm layui-btn-danger" lay-event="getCheckData">删除选中</button>
            <button class="layui-btn layui-btn-sm " lay-event="MC">修改分类</button>
            <button class="layui-btn layui-btn-sm " lay-event="zhiding">置顶</button>
            <button class="layui-btn layui-btn-sm " lay-event="addlink">添加</button>
            <button class="layui-btn layui-btn-sm " lay-event="set_private">设为私有</button>
            <button class="layui-btn layui-btn-sm " lay-event="set_public">设为公有</button>
            <button class="layui-btn layui-btn-sm " lay-event="set_tag">设标签</button>
            <button class="layui-btn layui-btn-sm layui-btn-danger" <?php echo $offline?'style="display:none;"':''?> lay-event="testing">检测</button>
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
<?php 
$categorys = $db->select('on_categorys',['id','Icon'],["ORDER" =>  ["weight" => "DESC"]]);
echo "
<script>
var icos={};";
foreach ($categorys as $category) {
    echo "icos[{$category['id']}]=\"{$category['Icon']}\";";
}
echo "
</script>
";
?>
</div>
<?php include_once('footer.php'); ?>