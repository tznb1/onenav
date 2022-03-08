<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta http-equiv="Cache-Control" content="no-transform">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
<meta name="applicable-device" content="pc,mobile">
<meta name="MobileOptimized" content="width">
<meta name="HandheldFriendly" content="true">
<title><?php echo getconfig('title');?></title>
<?php $keywords=getconfig("keywords"); if($keywords !=''){echo '<meta name="keywords" content="'.$keywords.'"/>'."\n";}?>
<?php $description=getconfig("description"); if($description !=''){echo '<meta name="description" content="'.$description.'"/>'."\n";}?>
<link rel="stylesheet" href="<?php echo $libs?>/Font-awesome/4.7.0/css/font-awesome.css">
<link rel="stylesheet" href="<?php echo $libs?>/Layui/v2.6.8/css/layui-icon.css">
<link rel="stylesheet" href="<?php echo $Theme?>/static/style<?php if($Style=='2'||$Style=='3'){echo '1';}?>.css?v=<?php echo $version; ?>" />
<?php // $nw=getconfig("navwidth");if($nw!=''){echo'<style type="text/css">.main{padding-left:'.$nw.'px;}header{width:'.$nw.'px;}</style>';}//导航宽度?>
<?php $head=getconfig("head");if($head!=''&& ($Diy==='1' || $userdb['Level']==='999')){echo(htmlspecialchars_decode(base64_decode($head)));} //自定义头部代码?> 
</head>
<body>
<!-- 左侧栏 -->  
<header>
<div class="logo"><a href=""><?php echo getconfig('logo');?></a></div>
<div class="typelist">
<?php if($is_login) {//窄屏侧边栏登陆管理接口?>
	<p class="login">	<a href="./index.php?c=admin&u=<?php echo $u?>" title="后台管理" target="_blank"><i class="fa fa-user-circle-o"></i>后台管理</a></p>
<?php }elseif(getconfig('GoAdmin')  == 'on'  ){?>
    <p class="login"><a href="./index.php?c=<?php if($login =='login'){echo $login;}else{echo $Elogin;}?>&u=<?php echo $u?>" title="登录" target="_blank">
    <i class="fa fa-user-circle-o"></i>管理登陆</a></p>
<?php }//窄屏侧边栏End
foreach ($categorys as $category) {//遍历分类?>
<p><a href="#category-<?php echo $category['id']; ?>"><?php echo geticon($category['Icon']).$category['name'];?></a></p>
<?php } ?>
</div>
</header><!-- 左侧栏End -->  
<!-- 窄屏顶部栏 -->
<div class="header">
<div class="logo"><a href="./?u=<?php echo $u?>"><?php echo getconfig('logo');?></a></div>
<div class="nav-bar">
<i class="fa fa-bars"></i>
</div>
</div><!-- 窄屏顶部栏End -->
<!-- 顶部栏 -->
<nav class="main-top">
<div class="search">
<input type="text" class="search-input" placeholder="输入关键词搜索" />
<button id="baidu">百度搜索</button>
</div>
<!-- 右上角 -->
<div class="main-top-r">
<span class="theme" ><i class="fa fa-magic" title="深色模式"></i></span>
<?php if($is_login){?>
    <span class="bs-addUrl"><i class="fa fa-plus" title="快速添加"></i></span>
    <a href="./index.php?c=admin&u=<?php echo $u?>" title="后台管理" target="_blank"><i class="fa fa-user-circle-o"></i></a>
<?php }elseif(getconfig('GoAdmin')  == 'on'  ){?>
    <a href="./index.php?c=<?php if($login =='login'){echo $login;}else{echo $Elogin;}?>&u=<?php echo $u?>" title="登录" target="_blank"><i class="fa fa-user-circle-o"></i></a>
<?php }?>
</div><!-- 右上角End -->
</nav><!-- 顶部栏End -->
<!--正文内容部分-->
<div class="main"><div class="site-main">
<!-- 遍历分类目录 -->
<?php foreach ( $categorys as $category ) {
$fid = $category['id'];
$links = get_links($fid);
//如果分类是私有的
if( $category['property'] == 1 ) {
$property = '<span><i class="fa fa-low-vision"></i></span>';} else  {$property = '';}
?>

<div class="site-type" id="category-<?php echo $category['id']; ?>"><?php echo geticon($category['Icon']).$category['name']; ?><?php echo $property; ?></div>
    <div class="site-list">
    <!-- 遍历链接 -->
	    <?php foreach ($links as $link) {
        $link['description'] = empty($link['description']) ? '作者很懒，没有填写描述。' : $link['description'];//默认描述
        $linkURL=getconfig('urlz')  == 'on' ? $link['url'] :'./index.php?c=click&id='.$link['id'].'&u='.$u;
        ?>
		<div class="list url-list" id = "id_<?php echo $link['id']; ?>" link-title = "<?php echo $link['title']; ?>" link-url = "<?php echo $link['url']; ?>">
		<a href="<?php echo $linkURL; ?>" target="_blank" title="<?php echo $link['description'];//悬停描述?>">
		<p class="name">
		<img src="<?php if (getconfig('LoadIcon')  == 'on'  ){echo geticourl($IconAPI,$link['url']);}else{echo $libs.'/Other/default.ico';} ?>">
		<?php echo $link['title'];?>
		</p>
		<?php if($Style=='1'||$Style=='2' ){echo '<p class="desc">'."\n".$link['description']."\n</p>";}//描述输出 ?>
		<?php if($link['property'] == 1 ) { ?>
		<span class="private"><i class="fa fa-low-vision"></i></span>
		<?php } ?>
		</a>
		</div>
		<?php } ?>
			<!-- 遍历链接END -->
			<div class="list kongs"></div>
			<div class="list kongs"></div>
			<div class="list kongs"></div>
            <div class="list kongs"></div>
            </div>
        <?php } ?>
</div></div><!--正文内容部分END-->
<!--<div class="footer"></div>-->
<footer>
<?php if($ICP != ''){echo '<a href="https://beian.miit.gov.cn" target="_blank">'.$ICP.'</a>';} ?>
<?php $footer=getconfig("footer"); if($footer != ''&& ($Diy==='1' || $userdb['Level']==='999')){echo(htmlspecialchars_decode(base64_decode($footer)));} ?>
<?php if($Ofooter != ''){echo $Ofooter;} //公用底部?>
</footer>
<?php //<!--右下角-->
$gotop=getconfig("gotop");
$quickAdd=getconfig("quickAdd");
$on=$quickAdd=='on'||$gotop =='on';
if($on){echo '<div class="tool-bars">';}
if($on&&$is_login&&$quickAdd=='on'){echo '<p class="bs-addUrl"><i class="fa fa-plus" title="快速添加"></i></p>';}
if($on&&$gotop=='on'){echo '<p class="scroll_top"><i class="fa fa-chevron-up" title="返回顶部"></i></p>';}
if($on){echo '</div>';}
?>
<!--JS-->
<script src = "<?php echo $libs?>/jquery/jquery-3.6.0.min.js"></script>
<script src = "<?php echo $libs?>/jquery/jQueryCookie.js"></script>
<script src = "<?php echo $libs?>/Layer/v3.3.0/layer.js"></script>
<script src = "<?php echo $libs?>/Other/holmes.js"></script>
<script src = "<?php echo $Theme?>/static/embed.js?v=<?php echo $version; ?>"></script>

<?php if(preg_match('/MSIE|Trident/i',$_SERVER['HTTP_USER_AGENT'])){ echo "<script>alert('您的浏览器与本站不兼容,建议您使用谷歌浏览器!或将浏览器设为极速模式')</script>";}?>
<script type="text/javascript">
    var u = '<?php echo $u?>';
	var bodyh = $(document.body).height();
	var htmlh = $(window).height();
	if (bodyh>htmlh) {
		$('footer').addClass('show')
	} else{
		$('footer').removeClass('show')
	}
</script>
</body>
</html>