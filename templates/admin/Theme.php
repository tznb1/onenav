<?php include_once('header.php'); ?>
<?php include_once('left.php'); ?>

<div class="layui-body ">
<!-- 内容主体区域 -->
<style type="text/css">
.screenshot{
    /*width: -webkit-fill-available; */
    /*height: -webkit-fill-available; */
    /*height:200px; */
    /*width:355px; */
    /*object-fit: scale-down;*/
    width: auto;  
    height: 200px;  
    max-width: 100%;  
    max-height: 100%;   
}
</style>
<div class="layui-bg-gray" style="padding: 1px;">
  <div class="layui-row layui-col-space15">
    <!-- 主题列表 -->
<?php foreach ($themes as $key => $theme) {
//echo($theme['info']->config);

if($current_themes1 == $key && $current_themes2 == $key){
    $icon ='<i class="layui-icon layui-icon-cellphone"> </i><i class="fa fa-tv"></i> ';
}elseif($current_themes1 == $key){
    $icon ='<i class="fa fa-tv"></i> ';
}elseif($current_themes2 == $key){
    $icon ='<i class="layui-icon layui-icon-cellphone"></i> ';
}else{
    $icon ='';
}
?>
    <div class="layui-col-xs layui-col-sm4 layui-col-md3">
      <div class="layui-card">
          
        <div class="layui-card-header"> 
            <div style="float:left; cursor:pointer;" title = "<?php echo $key ?>"><?php echo $icon ?><?php echo $theme['info']->name ?></div>
            <div style="float:right;cursor:pointer;" title = "<?php echo $theme['info']->update ?>"><?php echo $theme['info']->version ?></div>
        </div>
        <div class="layui-card-body" >
          <div class="img-list"><img class="screenshot" src="<?php echo $theme['info']->screenshot ?>" /></div>
        </div>
        <div class="layui-card-header"  style = "height: 1px;"></div>
        <div class="layui-card-header">
            <div class="layui-btn-group">
                <button type="button" class="layui-btn layui-btn-sm" onclick = "set_theme('<?php echo $key ?>','<?php echo $theme['info']->name; ?>')">使用</button>
                <button type="button" class="layui-btn layui-btn-sm" onclick = "theme_detail('<?php echo $theme['info']->name; ?>','<?php echo $theme['info']->description; ?>','<?php echo $theme['info']->version; ?>','<?php echo $theme['info']->update; ?>','<?php echo $theme['info']->author; ?>','<?php echo $theme['info']->homepage; ?>','<?php echo $theme['info']->screenshot; ?>','<?php echo $key ?>')">详情</button>
                <button type="button" class="layui-btn layui-btn-sm" onclick = "theme_preview('<?php echo $key ?>','<?php echo $theme['info']->name; ?>')">预览</button>
                <button type="button" class="layui-btn layui-btn-sm" onclick = "theme_config('<?php echo $key ?>','<?php echo $theme['info']->name ?>')" <?php echo $theme['info']->config == '1'? '':'style="display:none;"'; ?>>配置</button>
            </div>
        </div>
      </div>
    </div>
<?php } ?>
    <!-- 主题列表END -->
  </div>
</div> 
<!-- 内容主题区域END -->
</div>

<?php include_once('footer.php'); exit; ?>