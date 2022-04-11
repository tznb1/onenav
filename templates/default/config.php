<?php
$Theme='Theme-'.getconfig('Theme').'-';
if(!$is_login){exit ("<h3>您未登录!</h3>");}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    Writeconfig($Theme.'HeadBackgroundColor',$_POST['HeadBackgroundColor']);
    Writeconfig($Theme.'CardBackgroundColor',$_POST['CardBackgroundColor']);
    Writeconfig($Theme.'OtherBackgroundColor',$_POST['OtherBackgroundColor']);
    Writeconfig($Theme.'HeadFontColor',$_POST['HeadFontColor']);
    Writeconfig($Theme.'CategoryFontColor',$_POST['CategoryFontColor']);
    Writeconfig($Theme.'TitleFontColor',$_POST['TitleFontColor']);
    Writeconfig($Theme.'DescrFontColor',$_POST['DescrFontColor']);
    if($_GET['local'] == 'PresetColor'){
        //预设配色修改
        msg(0,"修改成功"); 
    }
    Writeconfig($Theme.'CardNum',intval($_POST['CardNum']));
    Writeconfig($Theme.'backgroundURL',$_POST['backgroundURL']);
    Writeconfig($Theme.'DescrRowNumber',intval($_POST['DescrRowNumber']));
    Writeconfig($Theme.'night',intval($_POST['night']));

    msg(0,"修改成功");
}

?>
 
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title>主题DIY</title>
  <link rel='stylesheet' href='<?php echo $libs?>/Layui/v2.6.8/css/layui.css'>
  <style>    
    .layui-form-item {
        margin-bottom: 10px;
        height: 38px;
    }
  </style>
</head>
<body>
<div>
<!-- 内容主体区域 -->
<div class="layui-row" style = "margin-top:18px;">
	<div class="layui-container">
    <div class="layui-col-lg6 layui-col-md-offset3">
    <form class="layui-form">

    <div class="layui-form-item">
        <label class="layui-form-label">头部背景色</label>
        <div class="layui-input-inline" >
        <input type="text" value="<?php echo getconfig($Theme.'HeadBackgroundColor');?>" placeholder="请选择颜色" class="layui-input" id="HeadBackgroundColor-input" name="HeadBackgroundColor">
        </div>
        <div class="layui-inline" style="left: -11px;">
        <div id="HeadBackgroundColor"></div>
        <label class="layui-word-aux">夜间模式下无效</label>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">卡片背景色</label>
        <div class="layui-input-inline" >
        <input type="text" value="<?php echo getconfig($Theme.'CardBackgroundColor');?>" placeholder="请选择颜色" class="layui-input" id="CardBackgroundColor-input" name="CardBackgroundColor">
        </div>
        <div class="layui-inline" style="left: -11px;">
        <div id="CardBackgroundColor"></div>
        <label class="layui-word-aux">夜间模式下无效</label>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">其他背景色</label>
        <div class="layui-input-inline" >
        <input type="text" value="<?php echo getconfig($Theme.'OtherBackgroundColor');?>" placeholder="请选择颜色" class="layui-input" id="OtherBackgroundColor-input" name="OtherBackgroundColor">
        </div>
        <div class="layui-inline" style="left: -11px;">
        <div id="OtherBackgroundColor"></div>
        <label class="layui-word-aux">夜间模式下无效</label>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">头部字体色</label>
        <div class="layui-input-inline" >
        <input type="text" value="<?php echo getconfig($Theme.'HeadFontColor');?>" placeholder="请选择颜色" class="layui-input" id="HeadFontColor-input" name="HeadFontColor">
        </div>
        <div class="layui-inline" style="left: -11px;">
        <div id="HeadFontColor"></div>
        <label class="layui-word-aux">夜间模式下无效</label>
        </div>
    </div>
    
    <div class="layui-form-item">
        <label class="layui-form-label">分类字体色</label>
        <div class="layui-input-inline" >
        <input type="text" value="<?php echo getconfig($Theme.'CategoryFontColor');?>" placeholder="请选择颜色" class="layui-input" id="CategoryFontColor-input" name="CategoryFontColor">
        </div>
        <div class="layui-inline" style="left: -11px;">
        <div id="CategoryFontColor"></div>
        <label class="layui-word-aux">夜间模式下无效</label>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">标题字体色</label>
        <div class="layui-input-inline" >
        <input type="text" value="<?php echo getconfig($Theme.'TitleFontColor');?>" placeholder="请选择颜色" class="layui-input" id="TitleFontColor-input" name="TitleFontColor">
        </div>
        <div class="layui-inline" style="left: -11px;">
        <div id="TitleFontColor"></div>
        <label class="layui-word-aux">夜间模式下无效</label>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">描述字体色</label>
        <div class="layui-input-inline" >
        <input type="text" value="<?php echo getconfig($Theme.'DescrFontColor');?>" placeholder="请选择颜色" class="layui-input" id="DescrFontColor-input" name="DescrFontColor">
        </div>
        <div class="layui-inline" style="left: -11px;">
        <div id="DescrFontColor"></div>
        <label class="layui-word-aux">夜间模式下无效</label>
        </div>
    </div>

  <div class="layui-form-item">
    <input id="PresetColor-input" type="hidden" value="<?php echo getconfig($Theme.'PresetColor','0');?>">
    <label class="layui-form-label">预设配色</label>
    <div class="layui-input-inline">
      <select  id="PresetColor" name="PresetColor" lay-filter="PresetColor" lay-search>
          <option></option>
        <option value="0">默认配色</option>
        <option value="1">黑底白字</option>
        <option value="2">黑底金字</option>
        <option value="3">淡绿护眼</option>
        <option value="4">深绿护眼</option>
        <option value="5">粉底黑字</option>
      </select>
    </div>
    <div class="layui-form-mid layui-word-aux">征集配色方案啊...</div>
  </div>
  
  <div class="layui-form-item">
    <input id="CardNum-input" type="hidden" value="<?php echo getconfig($Theme.'CardNum','0');?>">
    <label class="layui-form-label">卡片数量</label>
    <div class="layui-input-inline">
      <select lay-verify="required"  id="CardNum" name="CardNum" lay-search>
        <option value="1">最多10张</option>
        <option value="2">最多9张</option>
        <option value="0">最多4张</option>
      </select>
    </div>
    <div class="layui-form-mid layui-word-aux">横向显示的卡片数量</div>
  </div>
  <div class="layui-form-item">
    <input id="DescrRowNumber-input" type="hidden" value="<?php echo getconfig($Theme.'DescrRowNumber','2');?>">
    <label class="layui-form-label">描述行数</label>
    <div class="layui-input-inline">
      <select lay-verify="required"  id="DescrRowNumber" name="DescrRowNumber" lay-search>
        <option value="0">0 (隐藏)</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
      </select>
    </div>
    <div class="layui-form-mid layui-word-aux">链接描述显示的行数</div>
  </div>
  
  <div class="layui-form-item">
    <input id="night-input" type="hidden" value="<?php echo getconfig($Theme.'night','0');?>">
    <label class="layui-form-label">夜间模式</label>
    <div class="layui-input-inline">
      <select lay-verify="required"  id="night" name="night" lay-search>
        <option value="0">关闭</option>
        <option value="1">开启</option>
        <option value="2">自动</option>
      </select>
    </div>
    <div class="layui-form-mid layui-word-aux">自动: 19:00-07:00 开启夜间模式</div>
  </div>
  
  <div class="layui-form-item">
    <label class="layui-form-label">背景图URL</label>
    <div class="layui-input-block">
      <input type="url" id = "backgroundURL" name="backgroundURL" value = "<?php echo getconfig($Theme.'backgroundURL','');?>" placeholder="存在时其他背景色无效,请输入图片URL" autocomplete="off" class="layui-input">
    </div>
  </div>

  <div class="layui-form-item">
    <div class="layui-input-block">
      <button class="layui-btn" lay-submit lay-filter="edit_homepage">保存</button>
    </div>
  </div>
</form>

    </div>
<!-- 内容主题区域END -->
</div>
<script src = '<?php echo $libs?>/jquery/jquery-3.6.0.min.js'></script>
<script src = '<?php echo $libs?>/Layui/v2.6.8/layui.js'></script>

<script>
var u = '<?php echo $u?>';
$('#CardNum').val(document.getElementById('CardNum-input').value); //初始卡片数量
$('#night').val(document.getElementById('night-input').value); //初始卡片数量
$('#DescrRowNumber').val(document.getElementById('DescrRowNumber-input').value); //描述行数

layui.use(['form','colorpicker'], function(){
    var form = layui.form;
    var colorpicker = layui.colorpicker;

form.on('select(PresetColor)', function(data){
    console.log(data.value);
    var  post='';
    if(data.value == '0'){
        // 默认配色
        post = {'HeadBackgroundColor':'#3f51b5','CardBackgroundColor':'#ffffff','OtherBackgroundColor':'#ffffff','HeadFontColor':'#ffffff','CategoryFontColor':'#212121','TitleFontColor':'#212121','DescrFontColor':'#9e9e9e'};
    }else if(data.value == '1'){
        // 黑底白字
        post = {'HeadBackgroundColor':'#000000','CardBackgroundColor':'#414141','OtherBackgroundColor':'#232323','HeadFontColor':'#f8f8f8','CategoryFontColor':'#f8f8f8','TitleFontColor':'#f8f8f8','DescrFontColor':'#d5d5d5'};
    }else if(data.value == '2'){
        // 黑底金色
        post = {'HeadBackgroundColor':'#000000','CardBackgroundColor':'#414141','OtherBackgroundColor':'#232323','HeadFontColor':'#f5eb8c','CategoryFontColor':'#f5eb8c','TitleFontColor':'#f5eb8c','DescrFontColor':'#f4eca9'};
    }else if(data.value == '3'){
        // 浅绿护眼
        post = {'HeadBackgroundColor':'#bbe6bf','CardBackgroundColor':'#E3EDCD','OtherBackgroundColor':'#C7EDCC','HeadFontColor':'#6f680c','CategoryFontColor':'#6f680c','TitleFontColor':'#6f680c','DescrFontColor':'#747038'};
    }else if(data.value == '4'){
        // 深绿护眼
        post = {'HeadBackgroundColor':'#6ba628','CardBackgroundColor':'#abc87a','OtherBackgroundColor':'#76a650','HeadFontColor':'#373535','CategoryFontColor':'#000000','TitleFontColor':'#000000','DescrFontColor':'#3b3b36'};
    }else if(data.value == '5'){
        // 粉色
    post = {'HeadBackgroundColor':'#efa9dd','CardBackgroundColor':'#e89bd4','OtherBackgroundColor':'#e4a6d3','HeadFontColor':'#6a6868','CategoryFontColor':'#6a6868','TitleFontColor':'#6a6868','DescrFontColor':'#5e5e56'};
    }

if (post !== ''){
    $.post('./index.php?fn=config&local=PresetColor&u='+u,post,function(data,status){
      if(data.code == 0) {
          window.location.reload()
      }else{
          layer.msg(data.msg, {icon: 5});
      }
    });
}else{
    console.log("post = {"+
        "'HeadBackgroundColor':'"+
        document.getElementById('HeadBackgroundColor-input').value +
        "','CardBackgroundColor':'"+
        document.getElementById('CardBackgroundColor-input').value +
        "','OtherBackgroundColor':'"+
        document.getElementById('OtherBackgroundColor-input').value +
        "','HeadFontColor':'"+
        document.getElementById('HeadFontColor-input').value +
        "','CategoryFontColor':'"+
        document.getElementById('CategoryFontColor-input').value +
        "','TitleFontColor':'"+
        document.getElementById('TitleFontColor-input').value +
        "','DescrFontColor':'"+
        document.getElementById('DescrFontColor-input').value +"'};");
}
});
// 预设颜色
colors = ['#ffc107','#ffeb3b','#cddc39','#ff9800','#3f51b5','#2196f3','#03a9f4','#ff5722','#f44336','#607d8b','#9e9e9e','#795548','#00bcd4','#673ab7','#9c27b0','#4caf50','#8bc34a','#e91e63','#009688','#000000','#ea81ce','#ffffff']

//头部背景色
  colorpicker.render({
    elem: '#HeadBackgroundColor'
    ,predefine: true
    ,color: '<?php echo getconfig($Theme.'HeadBackgroundColor');?>'
    ,colors: colors //自定义预定义颜色项
    ,change: function(color){
      $('#HeadBackgroundColor-input').val(color);
    }
  });
//卡片背景色
  colorpicker.render({
    elem: '#CardBackgroundColor'
    ,predefine: true
    ,colors: colors
    ,color: '<?php echo getconfig($Theme.'CardBackgroundColor');?>'
    ,change: function(color){
      $('#CardBackgroundColor-input').val(color);
    }
  });
//其他背景色
  colorpicker.render({
    elem: '#OtherBackgroundColor'
    ,predefine: true
    ,colors: colors
    ,color: '<?php echo getconfig($Theme.'OtherBackgroundColor');?>'
    ,change: function(color){
      $('#OtherBackgroundColor-input').val(color);
    }
  }); 
//头部字体色
  colorpicker.render({
    elem: '#HeadFontColor'
    ,predefine: true
    ,colors: colors
    ,color: '<?php echo getconfig($Theme.'HeadFontColor');?>'
    ,change: function(color){
      $('#HeadFontColor-input').val(color);
    }
  });   
//分类字体色
  colorpicker.render({
    elem: '#CategoryFontColor'
    ,predefine: true
    ,colors: colors
    ,color: '<?php echo getconfig($Theme.'CategoryFontColor');?>'
    ,change: function(color){
      $('#CategoryFontColor-input').val(color);
    }
  }); 
//标题字体色
  colorpicker.render({
    elem: '#TitleFontColor'
    ,predefine: true
    ,colors: colors
    ,color: '<?php echo getconfig($Theme.'TitleFontColor');?>'
    ,change: function(color){
      $('#TitleFontColor-input').val(color);
    }
  }); 
//描述字体色
  colorpicker.render({
    elem: '#DescrFontColor'
    ,predefine: true
    ,colors: colors
    ,color: '<?php echo getconfig($Theme.'DescrFontColor');?>'
    ,change: function(color){
      $('#DescrFontColor-input').val(color);
    }
  });   
//保存设置
form.on('submit(edit_homepage)', function(data){
    $.post('./index.php?fn=config&u='+u,data.field,function(data,status){
      //如果添加成功
      if(data.code == 0) {
        parent.location.reload(); //刷新页面
      }
      else{
        layer.msg(data.msg, {icon: 5});
      }
    });
    console.log(data.field);
    return false; 
});
});
</script>
</body>
</html>