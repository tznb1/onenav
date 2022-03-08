<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php echo '<title>'.getconfig('title').'</title>';?>
    <meta name='robots' content='max-image-preview:large'>
    <?php $keywords=getconfig("keywords"); if($keywords !=''){echo '<meta name="keywords" content="'.$keywords.'"/>'."\n";}?>
    <?php $description=getconfig("description"); if($description !=''){echo '<meta name="description" content="'.$description.'"/>'."\n";}?>
	<link rel="stylesheet" href="<?php echo $Theme?>/static/style.css?v=1.165">	
	<link rel="stylesheet" href="<?php echo $libs?>/Font-awesome/4.7.0/css/font-awesome.css">
	<link rel="stylesheet" href="<?php echo $libs?>/Layui/v2.6.8/css/layui-icon.css">
    <script src="<?php echo $libs?>/jquery/jquery-3.6.0.min.js"></script>
    <?php $head=getconfig("head");if($head!=''&& ($Diy==='1' || $userdb['Level']==='999')){echo(htmlspecialchars_decode(base64_decode($head)));} //自定义头部代码?> 
</head>
<body>
<!--  头部 -->
<div class="container logo-search">
  <div class="col search row-search-mobile">
    <form action="index.php">
      <input class="placeholder" placeholder="百度搜索" name="s" autocomplete="off">
    </form>
  </div>
  <div class="row">
    <div class="col logo">
      <h1></h1>
    </div>
    <div class="col search search-desktop last">
      <div class="search-input" >
      <form action="https://www.baidu.com/" target="_blank">
        <input class="placeholder" id="s" name="s" placeholder="百度搜索"  autocomplete="off" style="height: 44px;">
      </form>
      
      </div>
    </div>
  </div>
</div>
<!-- 导航栏 -->
<div class="container navigation">
	<div class="row">
		<div class="col nav">
			<ul id="index-nav">
				<li><a href="" data-id="index" title="首页" class="current">首页</a></li>
				<?php
            	if($is_login) {
            	?>
            	<li><a href="./index.php?c=admin&u=<?php echo $u?>"   title="后台管理">后台管理</a></li>
	            <?php }elseif (getconfig('GoAdmin')  == 'on'  ) {  ?>
	            <li><a href="./index.php?c=<?php if($login =='login'){echo $login;}else{echo $Elogin;}?>&u=<?php echo $u?>" title="登录">登录</a></li>
            	<?php } ?>
				<li><a href="//c.runoob.com/" target="_blank"  title="不止于工具">菜鸟工具</a></li>
				<li><a href="//www.runoob.com/w3cnote/" target="_blank" data-id="note" title="菜鸟笔记">菜鸟笔记</a></li>
				<li><a href="//www.runoob.com/w3cnav" target="_blank" data-id="note" title="网址导航">网址导航</a></li>
			</ul>
		</div>
	</div>
</div>

<style>
.home-left-column{
	width: 16%;
}
@media screen and (max-width: 768px) {
    .home-left-column {
        display: none;
    }
}
</style>
<!--  内容  -->
<div class="container main">
<div class="row">
	<div class="col home-left-column" id="main-left-cloumn">
	<div class="tab"  id="cate0"><i class="fa fa-reorder"></i> 全部教程</div>
	<div class="sidebar-box gallery-list">
	<?php foreach ($categorys as $category) {
    echo '<div class="design" id="'.$category['id'].'"><div class="navto-nav">'.geticon($category['Icon']).'</i> &nbsp; '.$category['name'].'</div></div>';
    //左侧栏
    }?>
 
					
		</div>
	</div>
	<div class="col middle-column-home">
	    
<?php foreach ( $categorys as $category ) { //桌面端
        $fid = $category['id'];
        $links = get_links($fid);
        $property = $category['property'] == 1 ? '<i class="fa fa-lock" style = "color:#5FB878"></i>':'';
        $name = $category['name'];
        ?>
        <div class="codelist codelist-desktop <?php echo $fid?>" >
        <h2><i <?php echo geticon2($category['Icon'])?>></i><?php echo $name.'&nbsp; '.$property;?></h2>
        <!-- 遍历链接 -->
        <?php
        foreach ($links as $link) {
        $linkURL=getconfig('urlz')  == 'on' ? $link['url'] :'./index.php?c=click&id='.$link['id'].'&u='.$u;
        $description = empty($link['description']) ? '作者很懒，没有填写描述。' : $link['description'];
        $img= getconfig('LoadIcon')  == 'on' ? geticourl($IconAPI,$link['url']):$Theme.'/static/default.ico';
        ?>
        <a class="item-top item-1" href="<?php echo $linkURL?>"><h4><?php echo $link['title']?></h4>
        <img class="codeicon codeicon-36" height="36" width="36" src="<?php echo $img?>">
        <strong><?php echo $description?></strong></a>
        <?php } 
        ?></div>
        <?php } //桌面端End
        ?>
       
<?php foreach ( $categorys as $category ) { //移动端
        $fid = $category['id'];
        $links = get_links($fid);
        $property = $category['property'] == 1 ? '<i class="fa fa-lock" style = "color:#5FB878"></i>':'';
        $name = $category['name'];
        ?>
        <div class="codelist codelist-mobile" >
        <h2><i <?php echo geticon2($category['Icon'])?>></i><?php echo $name.'&nbsp; '.$property;?></h2>
        <!-- 遍历链接 -->
        <?php
        foreach ($links as $link) {
        $linkURL=getconfig('urlz')  == 'on' ? $link['url'] :'./index.php?c=click&id='.$link['id'].'&u='.$u;
        ?>
        <a class="item-top item-1" href="<?php echo $linkURL?>"><h4><?php echo $link['title']?></h4></a>
        <?php } 
        ?></div>
        <?php } //移动端End
        ?>
</div>
</div>
</div>
<!-- 底部 -->
<div id="footer">
   <div class="w-1000 copyright">警告:此模板来自于菜鸟教程,仅供个人学习、研究之用，请勿用于商业用途”。请在下载后24小时内删除
     Copyright &copy; 2013-2022    <strong><a href="//www.runoob.com/" target="_blank">菜鸟教程</a></strong>&nbsp;
    <strong><a href="//www.runoob.com/" target="_blank">runoob.com</a></strong> All Rights Reserved.
        <?php  if($ICP != ''){echo '<a href="https://beian.miit.gov.cn" target="_blank">'.$ICP.'</a>';} ?>
    <?php $footer=getconfig("footer"); if($footer != ''&& ($Diy==='1' || $userdb['Level']==='999')){echo(htmlspecialchars_decode(base64_decode($footer)));} ?>
    <?php if($Ofooter != ''){echo $Ofooter;} //公用底部?>
   </div>
  </div>
<?php if(getconfig("gotop") =='on') {?>
    <!-- 返回顶部按钮 -->
    <div class="fixed-btn">
    <a class="go-top" href="javascript:void(0)" title="返回顶部"> <i class="fa fa-angle-up"></i></a>
    </div>
	<!-- 返回顶部END -->
<?php } ?>
<div style="display:none;"></div>
<script src="<?php echo $Theme?>/static/main.min.js?v=1.131"></script>
</body>
</html>