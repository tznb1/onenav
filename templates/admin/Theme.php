<?php include_once('header.php'); ?>
<?php include_once('left.php'); ?>

<div class="layui-body ">
<!-- 内容主体区域 -->
<style type="text/css">
.screenshot{
    width: 99%;  
    height: 99%;  
    max-width: 100%;  
    max-height: 100%;   
    aspect-ratio:16/9;
}
</style> 
<blockquote class="layui-elem-quote " style="margin-top: 0px;border-left: 5px solid <?php echo $cache?"#1e9fff":($offline?"":"#639d11") ?>;padding: 6px;">&nbsp;
  <span class="layui-breadcrumb" lay-separator="|">
  <a href="https://gitee.com/tznb/OneNav/blob/data/template.md" target="_blank" >更新记录</a>
  <a href="https://gitee.com/tznb/OneNav/wikis/%E4%BD%BF%E7%94%A8%E8%AF%B4%E6%98%8E/%E6%9B%B4%E6%8D%A2%E4%B8%BB%E9%A2%98" target="_blank" >使用文档</a>
  <?php echo $admin?"<a href=\"./index.php?c=admin&page=Theme&other={$_GET['other']}&cache=no&u={$u}\" >刷新数据</a>":""; ?>
  <?php if($_GET['other'] == 'yes'){ 
            echo "<a href=\"./index.php?c=admin&page=Theme&other=no&u=$u\"  >主题模板</a>"; 
        }else{
            echo "<a href=\"./index.php?c=admin&page=Theme&other=yes&u=$u\"  >其他模板</a>"; 
            } ?>
  
</span>
</blockquote>
<div class="layui-bg-gray" style="padding: 1px;">
  <div class="layui-row layui-col-space15">
    <!-- 主题列表 -->
<?php 
if($_GET['other'] != 'yes'){
foreach ($themes as $key => $theme) {
if($theme['info']->type == 'o'){ 
    continue; 
} 
$online = !empty($theme['info']->low); //在线主题!
if($current_themes1 == $key && $current_themes2 == $key){
    $icon ='<i class="layui-icon layui-icon-cellphone" style="color: #03a9f4;" title = "移动终端正在使用此主题"> </i><i class="fa fa-tv" style="color: #03a9f4;" title = "PC终端正在使用此主题"></i> ';
}elseif($current_themes1 == $key){
    $icon ='<i class="fa fa-tv" style="color: #03a9f4;" title = "PC终端正在使用此主题"></i> ';
}elseif($current_themes2 == $key){
    $icon ='<i class="layui-icon layui-icon-cellphone" style="color: #03a9f4;" title = "移动终端正在使用此主题"></i> ';
}else{
    $icon ='';
}
?>
    <div class="layui-col-xs layui-col-sm4 layui-col-md3">
      <div class="layui-card">
          
        <div class="layui-card-header"> 
            <div style="float:left; cursor:pointer;<?php echo $current_themes1 == $key || $current_themes2 == $key ?"color: #03a9f4;":"" ?>" title = "<?php echo $key ?>"><?php echo $icon ?><?php echo $theme['info']->name ?></div>
            <div style="float:right;cursor:pointer;" title = "<?php echo $theme['info']->update ?>"><?php echo $theme['info']->version ?></div>
        </div>
        <div class="layui-card-body" >
          <div class="img-list"><img class="screenshot" src="<?php echo $theme['info']->screenshot ?>" /></div>
        </div>
        <div class="layui-card-header" style = "height: 1px;"></div>
        <div class="layui-card-header" style = "height: auto;">
            <div class="layui-btn-group"><?php  
            if($online ){
                echo "\n<button type=\"button\" class=\"layui-btn layui-btn-sm layui-btn-danger\" onclick = \"download_theme('$key','{$theme['info']->name}','{$theme['info']->desc}')\">下载</button>";
            }elseif($theme['info']->up == 1){
                echo "\n<button type=\"button\" class=\"layui-btn layui-btn-sm layui-btn-danger\" onclick = \"download_theme('$key','{$theme['info']->name}','{$theme['info']->desc}')\">更新</button>";
                echo "\n<button type=\"button\" class=\"layui-btn layui-btn-sm\" onclick = \"set_theme('$key','{$theme['info']->name}')\">使用</button>";
            }else{
                echo "\n<button type=\"button\" class=\"layui-btn layui-btn-sm\" onclick = \"set_theme('$key','{$theme['info']->name}')\">使用</button>";
            } ?>
                
                <button type="button" class="layui-btn layui-btn-sm" onclick = "theme_detail('<?php echo $theme['info']->name; ?>','<?php echo $theme['info']->description; ?>','<?php echo $theme['info']->version; ?>','<?php echo $theme['info']->update; ?>','<?php echo $theme['info']->author; ?>','<?php echo $theme['info']->homepage; ?>','<?php echo $theme['info']->screenshot; ?>','<?php echo $key ?>')">详情</button>
                <button type="button" class="layui-btn layui-btn-sm" onclick = "theme_preview('<?php echo $key ?>','<?php echo $theme['info']->name; ?>')" <?php echo $online? 'style="display:none;"':''; ?>>预览</button>
                <button type="button" class="layui-btn layui-btn-sm" onclick = "theme_config('<?php echo $key ?>','<?php echo $theme['info']->name ?>')" <?php echo $theme['info']->config == '1'? '':'style="display:none;"'; ?>>配置</button>
<?php  if($admin){ ?> 
                <button type="button" class="layui-btn layui-btn-sm layui-btn-danger" onclick = "theme_del('<?php echo $key ?>')" <?php echo $key != 'default' && !$online? '':'style="display:none;"'; ?>>删除</button>
<?php  } ?> 
            </div>
        </div>
      </div>
    </div>
<?php }
}else{ 
foreach ($themes as $key => $theme) {
if($theme['info']->type != 'o'){ 
    continue;
} 
$online = !empty($theme['info']->low); //在线主题!


if($current_Themeo == $key){
    $icon ='<i class="fa fa-wrench" style="color: #03a9f4;" title = "使用中"></i> ';
}else{
    $icon ='';
}
?>
    <div class="layui-col-xs layui-col-sm4 layui-col-md3">
      <div class="layui-card">
        <div class="layui-card-header"> 
            <div style="float:left; cursor:pointer;<?php echo $current_Themeo == $key ?"color: #03a9f4;":"" ?>" title = "<?php echo $key ?>"><?php echo $icon ?><?php echo $theme['info']->name ?></div>
            <div style="float:right;cursor:pointer;" title = "<?php echo $theme['info']->update ?>"><?php echo $theme['info']->version ?></div>
        </div>
        <div class="layui-card-body" >
          <div class="img-list"><img class="screenshot" src="<?php echo $theme['info']->screenshot ?>" /></div>
        </div>
        <div class="layui-card-header"  style = "height: 1px;"></div>
        <div class="layui-card-header">
            <div class="layui-btn-group"><?php  
            if($online ){
                echo "\n<button type=\"button\" class=\"layui-btn layui-btn-sm layui-btn-danger\" onclick = \"download_theme('$key','{$theme['info']->name}','{$theme['info']->desc}')\">下载</button>";
            }elseif($theme['info']->up == 1){
                echo "\n<button type=\"button\" class=\"layui-btn layui-btn-sm layui-btn-danger\" onclick = \"download_theme('$key','{$theme['info']->name}','{$theme['info']->desc}')\">更新</button>";
            }
            if(!$online ){
                if($current_Themeo == $key){
                    echo "\n<button type=\"button\" class=\"layui-btn layui-btn-sm layui-btn-danger\" onclick = \"set_theme2('defaulto','o')\">取消</button>";
                }else{
                    echo "\n<button type=\"button\" class=\"layui-btn layui-btn-sm\" onclick = \"set_theme2('$key','{$theme['info']->type}')\">使用</button>";
                }
                
            }
            
            ?>
                
                <button type="button" class="layui-btn layui-btn-sm" onclick = "theme_detail('<?php echo $theme['info']->name; ?>','<?php echo $theme['info']->description; ?>','<?php echo $theme['info']->version; ?>','<?php echo $theme['info']->update; ?>','<?php echo $theme['info']->author; ?>','<?php echo $theme['info']->homepage; ?>','<?php echo $theme['info']->screenshot; ?>','<?php echo $key ?>')">详情</button>
<?php  if($admin){ ?> 
                <button type="button" class="layui-btn layui-btn-sm layui-btn-danger" onclick = "theme_del('<?php echo $key ?>')" <?php echo $key != 'default' && !$online? '':'style="display:none;"'; ?>>删除</button>
<?php  } ?> 
            </div>
        </div>
      </div>
    </div>
<?php }} ?>
    <!-- 主题列表END -->
  </div>
</div> 
<!-- 内容主题区域END -->
</div>

<?php include_once('footer.php'); exit; ?>