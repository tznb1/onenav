<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title><?php echo $site['Title'];?></title>
  <?php if($site['keywords'] !=''){echo '<meta name="keywords" content="'.$site['keywords'].'"/>'."\n";}?>
  <?php if($site['description'] !=''){echo '<meta name="description" content="'.$site['description'].'"/>'."\n";}?>
  <link rel="stylesheet" href="<?php echo $libs?>/Layui/v2.6.8/css/layui.css">
  <link rel="stylesheet" href="<?php echo $libs?>/Font-awesome/4.7.0/css/font-awesome.css">
  <link rel="stylesheet" href="<?php echo $Theme?>/index.css?t=<?php echo time()?>">
  
  <?php echo $site['custom_header']; ?>
</head>
<body>
<div class="layui-layout layui-layout-admin">
  <div class="layui-header">
    <div class="layui-logo layui-hide-xs" style ="box-shadow: 0 0 0 rgba(0,0,0,0);"><a href=""  title = "爱导航" style="color:#009688;"><h3><?php echo $site['logo'];?></h3></a></div>
    <!-- 头部区域（可配合layui 已有的水平导航） -->
    <ul class="layui-nav layui-layout-left">
      <!-- 移动端显示 -->

      
      <li class="layui-nav-item layui-hide-xs"><a href="">nav 1</a></li>
      <li class="layui-nav-item layui-hide-xs"><a href="">nav 2</a></li>
      <li class="layui-nav-item layui-hide-xs"><a href="">nav 3</a></li>
      <li class="layui-nav-item">
        <a href="javascript:;">nav groups</a>
        <dl class="layui-nav-child">
          <dd><a href="">menu 11</a></dd>
          <dd><a href="">menu 22</a></dd>
          <dd><a href="">menu 33</a></dd>
        </dl>
      </li>
    </ul>
    <ul class="layui-nav layui-layout-right">
      <li class="layui-nav-item layui-hide layui-show-md-inline-block">
        <a href="javascript:;">
          <img src="./templates/admin/static/touxiang.jpg" class="layui-nav-img">
          tester
        </a>
        <dl class="layui-nav-child">
          <dd><a href="">后台管理</a></dd>
          <dd><a href="">主题设置</a></dd>
          <dd><a href="">退出登录</a></dd>
        </dl>
      </li>
    </ul>
  </div>
  
  <div class="layui-side layui-bg-black">
    <div class="layui-side-scroll">
      <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
      <ul class="layui-nav layui-nav-tree" lay-filter="test">
<?php
			//遍历分类目录并显示
			foreach ($category_parent as $category) {
			    echo "<li class=\"layui-nav-item layui-nav-itemed\">\n";
			    echo '<a class="" href="#category-'.$category['id'].'">'.geticon($category['Icon']).'&nbsp;'.$category['name']."</a>\n";
			    $i = 0;
			    
			    foreach (get_category_sub( $category['id'] ) AS $category_sub){
			        if($i == 0){ echo "<dl class=\"layui-nav-child\">\n";}
			        $i++;
			        echo '                    <dd><a href="#category-'.$category_sub['id'].'"> &nbsp;&nbsp; '.geticon($category_sub['Icon']).'&nbsp;'.$category_sub['name']."</a></dd>\n";
			    }
			    if($i != 0){
			            echo "</dl>\n";
			        }
			    echo "              </li>\n";
			}
?>
       
        
      </ul>
    </div>
  </div>
  
  <div class="layui-body">
  <!-- 内容主体区域 -->
  <div class="layui-bg-gray" style="padding: 20px;">
    <!--<div class="layui-collapse" >-->
<?php 
foreach ($category_parent as $category) {
    $fid = $category['id'];
    $links = get_links($fid);
    //渲染父分类>父分类下的链接>二级分类>二级分类下的链接
    ?>
        <!--<div class="layui-colla-item">-->
        <!--    <h2 class="layui-colla-title"><?php echo $category['name']; ?></h2>-->
        <!--    <div class="layui-colla-content layui-show">-->
        <!--        <p>分类下的内容</p>-->
        <!--    </div>-->
        <!--</div>-->
        <!-- 渲染父分类-->
        <!--<fieldset class="layui-elem-field layui-field-title" id = "category-<?php echo $category['id']; ?>">-->
        <!--    <legend><?php echo geticon($category['Icon']).'&nbsp;'.$category['name'].$property;?></legend>-->
        <!--</fieldset>-->
        <div id = "category-<?php echo $category['id']; ?>" class = "mdui-col-xs-12 mdui-typo-title cat-title CFC">
			<?php echo geticon($category['Icon']).'&nbsp;'.$category['name'].$property;?>
				<span class = "mdui-typo-caption DFC"><?php echo $category['description']; ?></span>
			</div>
        <!--渲染父分类下的链接-->
    <div class="layui-bg-gray" style="padding: 20px;">
    <div class="layui-row layui-col-space15">
    <?php foreach ($links as $link) {
		$link['description'] = empty($link['description']) ? '作者很懒，没有填写描述。' : $link['description'];
    ?>
    
    <!--卡片面板-->
    
    <div class="layui-col-md2">
      <a href="<?php echo geturl($link); ?>" target="_blank">
      <div class="layui-card">
        <div class="layui-card-header" ><img src="<?php echo geticourl($IconAPI,$link); ?>" alt="HUAN" width="16px" height="16px">&nbsp;<?php echo $link['title']; ?></div>
        <div class="layui-card-body" style="height: 43px;overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;">
          <?php echo $link['description']; ?>
        </div>
      </div>
      </a>
    </div>
    <!--卡片面板结束-->
    <?php } ?>
    </div>
    </div>
    
        <!--渲染父分类下的二级分类-->
        <?php foreach (get_category_sub( $category['id'] ) AS $category_sub){
            $fid2 = $category_sub['id'];
            $links2 = get_links($fid2);
            ?>
            <div class="layui-collapse" lay-filter="test" id = "category-<?php echo $category_sub['id']; ?>">
                <div class="layui-colla-item">
                <h2 class="layui-colla-title"><?php echo geticon($category_sub['Icon']).'&nbsp;'.$category['name'] .' -> '.$category_sub['name']; ?></h2>
                <div class="layui-colla-content layui-show">
                    <!--渲染二级分类下的链接-->
                <div class="layui-bg-gray" style="padding: 2px;">
                <div class="layui-row layui-col-space15">
                <?php foreach ($links2 as $link) {
		$link['description'] = empty($link['description']) ? '作者很懒，没有填写描述。' : $link['description'];
    ?>
            <!--卡片面板-->
    <div class="layui-col-md2">
      <a href="<?php echo geturl($link); ?>" target="_blank">
      <div class="layui-card">
        <div class="layui-card-header" ><img src="<?php echo geticourl($IconAPI,$link); ?>" alt="HUAN" width="16px" height="16px">&nbsp;<?php echo $link['title']; ?></div>
        <div class="layui-card-body" style="height: 43px;overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;">
          <?php echo $link['description']; ?>
        </div>
      </div>
      </a>
    </div>
    <!--卡片面板结束-->
    <?php } ?>
        </div>
    </div>
                </div>
                </div>
            </div>
            
            
                
                
    
    
        <?php } ?>
        
        
        
         
        
<?php } ?>


    

    

    <!--</div>-->
  <!-- 正文 End -->
  <div>占位</div>
  <div class="layui-footer">
    <!-- 底部固定区域 -->
    © 2022 Powered by <a target = "_blank" href="https://gitee.com/tznb/OneNav" title = "简约导航/书签管理器" rel = "nofollow">OneNav Extend</a>.The author is 落幕
  </div>
</div>
<script src = "<?php echo $libs?>/jquery/jquery-3.6.0.min.js"></script>
<script src = "<?php echo $libs?>/Layui/v2.6.8/layui.js"></script>
<script>
//JS 
layui.use(['dropdown','element', 'layer', 'util'], function(){
  var element = layui.element
  var layer = layui.layer
  var util = layui.util
  var $ = layui.$;
  
 
  
});

</script>
</body>
</html>