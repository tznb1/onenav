<?php include_once('header.php'); ?>
<?php include_once('left.php'); ?>
<div class="layui-body">
<!-- 内容主体区域 -->
<div class="layui-row content-body layui-show layui-form layui-form-pane">
<div class="layui-col-lg12">
<div class="layui-form-item">
    <div class="layui-inline">
      <label class="layui-form-label">站点标题</label>
        <div class="layui-input-inline">
        <input type="text" name="title" value = '<?php echo getconfig('title');?>' placeholder="OneNav书签" autocomplete="off" class="layui-input">
      </div> 
    </div>
    <div class="layui-inline">
      <label class="layui-form-label">文字Logo</label>
        <div class="layui-input-inline">
        <input type="text" name="logo"  value = '<?php echo getconfig('logo');?>' placeholder="我的书签" autocomplete="off" class="layui-input">
      </div> 
    </div>
    <div class="layui-inline">
      <label class="layui-form-label">站点关键词</label>
        <div class="layui-input-inline">
        <input type="text" name="keywords"  value = '<?php echo getconfig('keywords');?>' placeholder="OneNav,OneNav导航,OneNav书签,开源导航,开源书签,简洁导航,云链接,个人导航,个人书签" autocomplete="off" class="layui-input">
      </div> 
    </div>
  <div class="layui-inline" >
    <label class="layui-form-label">站点描述</label>
    <div class="layui-input-inline" Style = "">
      <input type="text" name="description"  value = '<?php echo getconfig('description');?>' placeholder="OneNav是一款使用PHP + SQLite3开发的简约导航/书签管理器，免费开源。" autocomplete="off" class="layui-input">
    </div>
  </div>
</div>  
<div class="layui-form-item">  
  <div class="layui-inline">
    <label class="layui-form-label">主题风格1</label>
    <div class="layui-input-inline">
      <input id="theme" type="hidden" value="<?php echo getconfig('Theme')."|".getconfig('Style');?>">
      <select id="TEMPLATE" name="TEMPLATE" lay-filter="aihao" >
        <option value="default|0" selected="">默认主题</option>
        <option value="default|1">默认主题(样式1)</option>
        <option value="default|2">默认主题(样式2)</option>
        <option value="default|xiaoz">默认主题(原版)</option>
        <option value="baisu|1">百素主题(显示描述)</option>
        <option value="baisu|0">百素主题(隐藏描述)</option>
        <option value="baisu|2">百素极速(显示描述)</option>
        <option value="baisu|3">百素极速(隐藏描述)</option>
        <option value="baisu-two|0">百素two</option>
        <option value="liutongxu|0">刘桐序</option>
        <option value="webstack|0">WebStack</option>
        <option value="SimpleWeb|0">SimpleWeb</option>
        <option value="lylme_spage|0">六零导航页</option>
        <option value="webjike|0">小呆导航</option>
        <option value="fz|0">疯子</option>
        <option value="runoob|0">菜鸟教程</option>
      </select>
    </div><div class="layui-form-mid layui-word-aux">PC端上显示的主题</div>
  </div>
<div class="layui-form-item">  
  <div class="layui-inline">
    <label class="layui-form-label">主题风格2</label>
    <div class="layui-input-inline">
      <input id="theme2" type="hidden" value="<?php echo getconfig('Theme2','default')."|".getconfig('Style2','0');?>">
      <select id="TEMPLATE2" name="TEMPLATE2" lay-filter="aihao" >
        <option value="default|0" selected="">默认主题</option>
        <option value="default|1">默认主题(样式1)</option>
        <option value="default|2">默认主题(样式2)</option>
        <option value="default|xiaoz">默认主题(原版)</option>
        <option value="baisu|1">百素主题(显示描述)</option>
        <option value="baisu|0">百素主题(隐藏描述)</option>
        <option value="baisu|2">百素极速(显示描述)</option>
        <option value="baisu|3">百素极速(隐藏描述)</option>
        <option value="baisu-two|0">百素two</option>
        <option value="liutongxu|0">刘桐序</option>
        <option value="webstack|0">WebStack</option>
        <option value="SimpleWeb|0">SimpleWeb</option>
        <option value="lylme_spage|0">六零导航页</option>
        <option value="webjike|0">小呆导航</option>
        <option value="fz|0">疯子</option>
        <option value="runoob|0">菜鸟教程</option>
      </select>
    </div><div class="layui-form-mid layui-word-aux">移动端上显示的主题,有些主题不兼容移动端,所以支持一下单独设置</div>
  </div>  
  <!--<div class="layui-inline">-->
  <!--  <label class="layui-form-label">导航宽度</label>-->
  <!--  <div class="layui-input-inline">-->
  <!--     <input type="text" name="navwidth"  value = '<?php echo getconfig('navwidth');?>' placeholder="默认:240,建议:150-240" autocomplete="off" class="layui-input">-->
  <!--  </div>-->
  <!--  <div class="layui-form-mid layui-word-aux">修改会导致手机端布局异常!</div>-->
  <!--</div>-->
 <div class="layui-form-item">
    <label class="layui-form-label">首页功能</label>
    <div class="layui-input-block">
      <input type="checkbox" name="urlz" lay-text="1|0" title="直连模式" <?php if (getconfig('urlz') =='on'){echo 'checked=""';}?>>
      <input type="checkbox" name="gotop" title="返回顶部" <?php if (getconfig('gotop') =='on'){echo 'checked=""';}?>>
      <input type="checkbox" name="quickAdd" title="快速添加" <?php if (getconfig('quickAdd') =='on'){echo 'checked=""';}?>>
      <input type="checkbox" name="GoAdmin" title="后台入口" <?php if (getconfig('GoAdmin') =='on'){echo 'checked=""';}?>>
      <input type="checkbox" name="LoadIcon" title="URL图标" <?php if (getconfig('LoadIcon') =='on'){echo 'checked=""';}?>>
      <input type="checkbox" name="DefaultDB" title="默认首页" <?php if ($_COOKIE['DefaultDB'] ==$u){echo 'checked=""';}?>>
    </div>
  </div>
<?php 
 if($Diy === '1' || $userdb['Level'] === '999'){
     ?>
  <div class="layui-form-item layui-form-text">
    <label class="layui-form-label">头部代码</label>
    <div class="layui-input-block"> 
       <textarea name="head" class="layui-textarea"  placeholder="当内置主题样式无法满足您的时候,您可以自定义样式!在head间加载!" ><?php echo base64_decode( getconfig('head'))?></textarea>
    </div>
  </div>
  <div class="layui-form-item layui-form-text">
    <label class="layui-form-label">底部代码</label>
    <div class="layui-input-block"> 
       <textarea name="footer" class="layui-textarea"  placeholder="例如统计代码,又拍云LOGO等,支持HTML,JS,CSS" ><?php echo base64_decode( getconfig('footer'))?></textarea>
    </div>
  </div>
 <?php }
?>

  
  <div class="layui-form-item">
    <div class="layui-input-block">
      <button class="layui-btn" lay-submit lay-filter="edit_homepage">保存</button>
      <button type="reset" class="layui-btn layui-btn-primary">重置</button>
    </div>
  </div>
</div>
</div>
</div>
<!-- 内容主题区域END -->
</div>
  
<?php include_once('footer.php'); ?>