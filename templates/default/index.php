<?php if($Style =='xiaoz'){require('./templates/default/xiaoze.php');exit;}?>
<!DOCTYPE html>
<html lang="zh-ch" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<title><?php echo getconfig("title");?></title>
<?php $keywords=getconfig("keywords"); if($keywords !=''){echo '<meta name="keywords" content="'.$keywords.'"/>'."\n";}?>
<?php $description=getconfig("description"); if($description !=''){echo '<meta name="description" content="'.$description.'"/>'."\n";}?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel='stylesheet' href='<?php echo $libs?>/MDUI/v1.0.1/css/mdui.min.css'>
<link rel='stylesheet' href='<?php echo $libs?>/ContextMenu/2.9.2/jquery.contextMenu.min.css'>
<link rel="stylesheet" href="<?php echo $libs?>/Font-awesome/4.7.0/css/font-awesome.css">
<link rel="stylesheet" href="<?php echo $libs?>/Layui/v2.6.8/css/layui-icon.css">
<link rel="stylesheet" href="<?php echo $Theme?>/static/style<?php echo $Style;?>.css?v=<?php echo $version; ?>">
<?php // $nw=getconfig("navwidth");if($nw!=''){echo'<style type="text/css">.mdui-drawer-body-left{padding-left:'.$nw.'px;}.mdui-drawer-diy{width:'.$nw.'px;}</style>';}//导航宽度?>
<?php $head=getconfig("head");if($head!='' && ($Diy==='1' || $userdb['Level']==='999')){echo(htmlspecialchars_decode(base64_decode($head)));} //自定义头部代码?> 
</head>
<body class = "mdui-drawer-body-left mdui-appbar-with-toolbar mdui-theme-primary-indigo mdui-theme-accent-pink mdui-loaded" >
	<!--导航工具-->
	<header class = "mdui-appbar mdui-appbar-fixed">
		<div class="mdui-toolbar mdui-color-theme">
		<span class="mdui-btn mdui-btn-icon mdui-ripple mdui-ripple-white" mdui-drawer="{target: '#drawer', swipe: true}"><i class="mdui-icon material-icons">menu</i></span>
		  <a href="" class = "mdui-typo-headline" ><span class="mdui-typo-title"><?php echo getconfig("logo");?></span></a>
		  <div class="mdui-toolbar-spacer"></div>
		  <!-- 新版搜索框 -->
		  	<div class="mdui-col-md-4 mdui-col-xs-6">
				<div class="mdui-textfield mdui-textfield-floating-label">
					<input class="mdui-textfield-input search" style = "color:#FFFFFF;" placeholder="输入书签关键词进行搜索" type="text" />
				</div>
			</div>
			<!-- 新版搜索框END -->
		</div>
	</header>
	<!--导航工具END-->
	<?php if($is_login&&getconfig("quickAdd") =='on') {
	?><!-- 添加按钮 -->
	<div class="right-button mdui-hidden-xs" style="position: fixed;right:10px;bottom:80px;z-index:99;">
		<div>
		<button title = "快速添加链接" id = "add" class="mdui-fab mdui-color-theme-accent mdui-ripple mdui-fab-mini"><i class="mdui-icon material-icons">add</i></button>
		</div>
	</div>
	<!-- 添加按钮END --><?php } ?>
	<?php if(getconfig("gotop") =='on') {?>
<!-- 返回顶部按钮 -->
	<div id="top"></div>
	<div class="top mdui-shadow-10">
	<a href="javascript:;" title="返回顶部" onclick="gotop()"><i class="mdui-icon material-icons">arrow_drop_up</i></a>
	</div>
	<!-- 返回顶部END -->
	<?php } ?>
	<!--左侧抽屉导航-->
	<!-- 默认抽屉栏在左侧 -->
	<div class="mdui-drawer mdui-drawer-diy" id="drawer" >
	  <ul class="mdui-list" >
	<!-- 左侧登陆和后台 -->   
	<?php
	if($is_login) {
	?>
    <a href="./index.php?c=admin&u=<?php echo $u?>">
	<li class="mdui-list-item mdui-ripple">
	<div class="mdui-list-item-content category-name"><i class="fa fa-user-circle"></i> 后台管理</div>
	</li>
	</a>
	<?php }elseif (getconfig('GoAdmin')  == 'on'  ) {  ?>
	<a href="./index.php?c=<?php if($login =='login'){echo $login;}else{echo $Elogin;}?>&u=<?php echo $u?>">
	<li class="mdui-list-item mdui-ripple">
	<div class="mdui-list-item-content category-name"><i class="fa fa-user-circle"></i> 登录</div>
	</li>
	</a>
	<?php } ?>    
	<!-- 左侧登陆和后台End -->         
	  	<?php
			//遍历分类目录并显示
			foreach ($categorys as $category) {
			//var_dump($category);
		?>
		<a href="#category-<?php echo $category['id']; ?>">
			<li class="mdui-list-item mdui-ripple">
				<div class="mdui-list-item-content category-name">
				    <?php echo geticon($category['Icon']).$category['name']; ?></div>
			</li>
		</a>
		<?php } ?>
	  </ul>
	</div>
	<!--左侧抽屉导航END-->
	<!--正文内容部分-->
	<div class="mdui-container">
		<div class="mdui-row">
			<!-- 遍历分类目录 -->
            <?php foreach ( $categorys as $category ) {
                $fid = $category['id'];
                $links = get_links($fid);
                //如果分类是私有的
                if( $category['property'] == 1 ) {
                    $property = '<i class="fa fa-lock" style = "color:#5FB878"></i>';
                }
                else {
                    $property = '';
                }
            ?>
			<div id = "category-<?php echo $category['id']; ?>" class = "mdui-col-xs-12 mdui-typo-title cat-title">
			<?php echo geticon($category['Icon']).$category['name'].$property;?>
				<span class = "mdui-typo-caption"><?php echo $category['description']; ?></span>
			</div>
			<!-- 遍历链接 -->
			<?php
				foreach ($links as $link) {
					//默认描述
					$link['description'] = empty($link['description']) ? '作者很懒，没有填写描述。' : $link['description'];
				//var_dump($link);
			?>
			<div class="mdui-col-lg-3 mdui-col-md-4 mdui-col-xs-12 link-space"  id = "id_<?php echo $link['id']; ?>" link-title = "<?php echo $link['title']; ?>" link-url = "<?php echo $link['url']; ?>">
				<!--定义一个卡片-->
				<div class="mdui-card link-line mdui-hoverable">
						<!-- 如果是私有链接，则显示角标 -->
						<?php if($link['property'] == 1 ) { ?>
						<div class="angle">
							<span> </span>
						</div>
						<?php } ?>
						<!-- 角标END -->
						<?php 
						if (getconfig('urlz')  == 'on'  ){
						    ?><a href="<?php echo $link['url']; ?>" target="_blank" title = "<?php echo $link['description']; ?>"><?php
						}else{
						    ?><a href="./index.php?c=click&id=<?php echo $link['id'].'&u='.$u; ?>" target="_blank" title = "<?php echo $link['description']; ?>"><?php
						};
						?>
							<div class="mdui-card-primary" style = "padding-top:16px;">
									<div class="mdui-card-primary-title link-title">
										<img src="<?php if (getconfig('LoadIcon')  == 'on'  ){echo geticourl($IconAPI,$link['url']);}else{echo $libs.'/Other/default.ico';} ?>" alt="HUAN" width="16px" height="16px">
										<span class="link_title"><?php echo $link['title']; ?></span> 
									</div>
							</div>
						</a>
						<!-- 卡片的内容end -->
					<div class="mdui-card-content mdui-text-color-black-disabled" style="padding-top:0px;"><span class="link-content"><?php echo $link['description']; ?></span></div>
				</div>
				<!--卡片END-->
			</div>
			<?php } ?>
			<!-- 遍历链接END -->
			<?php } ?>
		</div>
		<!-- row end -->
	</div>
	<div class="mdui-divider" style = "margin-top:2em;"></div>
	<!--正文内容部分END-->
	<!-- footer部分 --> 
	<footer>
    <?php if($ICP != ''){echo '<a href="https://beian.miit.gov.cn" target="_blank">'.$ICP.'</a>';} ?>
    <?php $footer=getconfig("footer"); if($footer != ''&& ($Diy==='1' || $userdb['Level']==='999')){echo(htmlspecialchars_decode(base64_decode($footer)));} ?>
    <?php if($Ofooter != ''){echo $Ofooter;} //公用底部?>
	</footer>
	<!-- footerend -->
<script src = "<?php echo $libs?>/jquery/jquery-3.6.0.min.js"></script>
<script src = "<?php echo $libs?>/Layer/v3.3.0/layer.js"></script> 
<script src = "<?php echo $libs?>/ContextMenu/2.9.2/jquery.contextMenu.min.js"></script>
<script src = "<?php echo $libs?>/Other/ClipBoard.min.js"></script>
<script src = '<?php echo $libs?>/MDUI/v1.0.1/js/mdui.min.js'></script>
<script src = "<?php echo $libs?>/Other/holmes.js"></script>
<script src = "<?php echo $Theme?>/static/embed.js?v=<?php echo $version; ?>"></script>
<?php if(preg_match('/MSIE|Trident/i',$_SERVER['HTTP_USER_AGENT'])){ echo "<script>alert('您的浏览器与本站不兼容,建议您使用谷歌浏览器!或将浏览器设为极速模式')</script>";}?>
<script>
var u = '<?php echo $u?>';
<?php echo $onenav['right_menu']; ?>
</script>
<?php echo $onenav['extend']; ?>
</body>
</html>