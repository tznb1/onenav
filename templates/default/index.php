<?php 
$night = intval( getconfig($config.'night','0') );
$night = $night == 1 || ( $night == 2 && (date('G') <= 12 || date('G') >= 19 )) ? 'mdui-theme-layout-dark':'';
$background = getconfig($config.'backgroundURL','');
$DescrRowNumber = intval(getconfig($config.'DescrRowNumber','2'));
$WeatherKey = getconfig($config.'WeatherKey','dd2e9ab2728d4b3c91245fe4057cb9ce');
$WeatherPosition =  intval(empty($WeatherKey)?"0":getconfig($config.'WeatherPosition','2'));
$referrer = getconfig($config.'referrer','0');
$protectA = (($referrer == 'link' || $referrer == 'link_icon') && getconfig('urlz') == 'on') ? 'referrerpolicy="same-origin"':'';
$protectIMG = ($referrer == 'link_icon' || $referrer == 'icon' ) ? 'referrerpolicy="same-origin"':'';
if(getconfig($config.'ClickLocation','0') =='0'){
    $CLALL = "</a>";
}else{
    $CLBT = "</a>";
}

if ($DescrRowNumber <= 0 ){
    $DescrRowNumber = 0; $DescrHeight= 0; $Card = 38;
}elseif($DescrRowNumber >= 1 && $DescrRowNumber <= 4 ){
    $DescrHeight= $DescrRowNumber * 24;
    $Card = 72 + $DescrHeight;
}else{
    $DescrRowNumber = 2; $DescrHeight= 48; $Card = 120; // 超出范围则设为2行
}

?>
<!DOCTYPE html>
<html lang="zh-ch">
<head>
<meta charset="utf-8">
<title><?php echo $site['Title'];?></title>
<?php if($site['keywords'] !=''){echo '<meta name="keywords" content="'.$site['keywords'].'"/>'."\n";}?>
<?php if($site['description'] !=''){echo '<meta name="description" content="'.$site['description'].'"/>'."\n";}?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php if($referrer == 'overall' && getconfig('urlz')  == 'on'){echo '<meta name="referrer" content="same-origin">'."\n";}?> 
<link rel='stylesheet' href='<?php echo $libs?>/MDUI/v1.0.1/css/mdui.min.css'>
<link rel='stylesheet' href='<?php echo $libs?>/ContextMenu/2.9.2/jquery.contextMenu.min.css'>
<link rel="stylesheet" href="<?php echo $libs?>/Font-awesome/4.7.0/css/font-awesome.css">
<link rel="stylesheet" href="<?php echo $libs?>/Layui/v2.6.8/css/layui-icon.css">
<link rel="stylesheet" href="<?php echo $Theme?>/static/style<?php echo getconfig($config.'CardNum','0');?>.css?v=<?php echo $version; ?>">
<style>
<?php  $SBC = getconfig($config.'SidebarBackgroundColor',''); if( empty($night)  ) {?>
/*配色*/
.mdui-theme-primary-indigo .mdui-color-theme {background-color: <?php echo getconfig($config.'HeadBackgroundColor','#3f51b5');?>!important;}
.mdui-loaded .mdui-drawer { <?php echo(empty($SBC)?'':'background-color:'.$SBC.'!important;');?>}
.HFC{color: <?php echo getconfig($config.'HeadFontColor','#ffffff');?>!important;}
.CBC{background-color: <?php echo getconfig($config.'CardBackgroundColor','#ffffff');?>!important;} 
.OBC{background-color: <?php echo getconfig($config.'OtherBackgroundColor','#ffffff');?>!important;}
.CFC{color: <?php echo getconfig($config.'CategoryFontColor','#212121');?>!important;}
.TFC{color: <?php echo getconfig($config.'TitleFontColor','#212121');?>!important;}
.DFC{color: <?php echo getconfig($config.'DescrFontColor','#9e9e9e');?>!important;}
<?php } ?>
<?php if( !empty($background) && empty($night) ) {?>
/*背景图*/
body{
    background: url('<?php echo $background;?>');
    background-size:100% 100%;
    background-repeat:no-repeat; 
    background-attachment: fixed;
}
<?php } ?>
/*描述行数*/
.link-line {height:<?php echo $Card;?>px;}
.link-content { 
    height:<?php echo $DescrHeight;?>px;
    -webkit-line-clamp: <?php echo $DescrRowNumber;?>;
}
.mdui-card-primary {padding-top: <?php if($DescrHeight == 0){echo '8px';}else{echo '16px';} ;?>;}
</style>
<?php echo $site['custom_header']; ?>
</head>
<body class = "mdui-drawer-body-left mdui-appbar-with-toolbar mdui-theme-primary-indigo mdui-theme-accent-pink mdui-loaded OBC <?php echo $night;?>" >
	<!--导航工具-->
	<header class = "mdui-appbar mdui-appbar-fixed" >
		<div class="mdui-toolbar mdui-color-theme" >
		<span class="mdui-btn mdui-btn-icon mdui-ripple mdui-ripple-white HFC" mdui-drawer="{target: '#drawer', swipe: true}"><i class="mdui-icon material-icons">menu</i></span>
		  <a href="" class = "mdui-typo-headline HFC" ><span class="mdui-typo-title"><?php echo $site['logo'];?></span></a>
		  <div class="mdui-toolbar-spacer" ></div>
		 
		  <!-- 新版搜索框 -->
		  	<div class="mdui-col-md-2 mdui-col-xs-5">
				<div class="mdui-textfield mdui-textfield-floating-label">
					<input class="mdui-textfield-input search HFC"  placeholder="输入书签关键词搜索" type="text" />
				</div>
			</div>
	
			<?php if($WeatherPosition==1){ echo '<div id="he-plugin-simple"></div>';} ?>
			<a class = "mdui-hidden-xs mdui-btn mdui-btn-icon" id="config"  title = "主题设置" <?php if(!$is_login) {echo 'style="display:none;"';}?>><i   class="mdui-icon material-icons HFC">&#xe40a;</i></a>
			<!-- 新版搜索框END -->
		</div>
	</header>
	<!--导航工具END-->
	<?php if( $is_login && $site['quickAdd'] ) {
	?><!-- 添加按钮 -->
	<div class="right-button mdui-hidden-xs" style="position: fixed;right:10px;bottom:80px;z-index:1000;">
		<div><button title = "快速添加链接" id = "add" class="mdui-fab mdui-color-theme-accent mdui-ripple mdui-fab-mini"><i class="mdui-icon material-icons">add</i></button></div>
	</div>
<?php } ?>
<?php if($site['gotop']) {?>
    <!-- 返回顶部按钮 -->
	<div class="top mdui-shadow-10"><a href="javascript:;" title="返回顶部" onclick="gotop()"><i class="mdui-icon material-icons">arrow_drop_up</i></a></div>
<?php } ?>
	<!--左侧抽屉导航-->
	<div class="mdui-drawer" id="drawer" >
	<ul class="mdui-list" >
	<!-- 左侧登陆或后台 -->   
<?php
	if($is_login) {
?>
    <a href="./index.php?c=admin&u=<?php echo $u?>"><li class="mdui-list-item mdui-ripple"><div class="mdui-list-item-content category-name CFC"><i class="fa fa-user-circle"></i>后台管理</div></li></a>
<?php }elseif ($site['GoAdmin']  ) {  ?>
	<a href="./index.php?c=<?php if($login =='login'){echo $login;}else{echo $Elogin;}?>&u=<?php echo $u?>">
	    <li class="mdui-list-item mdui-ripple">
	     <div class="mdui-list-item-content category-name CFC"><i class="fa fa-user-circle"></i>登录</div>
	    </li>
	</a>
<?php } ?>
	<!-- 左侧登陆或后台End -->
	  	<?php
			//遍历分类目录并显示
			foreach ($category_parent as $category) {
			//var_dump($category);
			
		?>
		<div class="mdui-collapse" mdui-collapse>
              <div class="mdui-collapse-item">
        <div class="mdui-collapse-item-header CFC">
		<a href="#category-<?php echo $category['id']; ?>">
			<li class="mdui-list-item mdui-ripple">
				<div class="mdui-list-item-content category-name CFC">
				    <?php echo geticon($category['Icon']).$category['name']; ?></div>
				    <?php echo !empty($category['count'])?'<i class="mdui-collapse-item-arrow mdui-icon material-icons">keyboard_arrow_down</i>':""; ?> 
			</li>
		</a>
		</div>
		<!-- 遍历二级分类-->
          <div class="mdui-collapse-item-body">
         <ul>
         <?php foreach (get_category_sub( $category['id'] ) AS $category_sub){

         ?>
            <a href="#category-<?php echo $category_sub['id']; ?>">
                <li class="mdui-list-item mdui-ripple" style="margin-left:-4.3em;">
                    <div class="mdui-list-item-content category_sub CFC">
                        <i>
                        <?php echo geticon($category_sub['Icon']).' '.$category_sub['name']; ?>
                        </i>
                    </div>
                </li>
            </a>
         <?php } ?>
        </ul>
        </div>
		<!--遍历二级分类END-->
		</div>
        </div>
	    
		<?php } ?>
	</ul>
	</div>
	<!--左侧抽屉导航END-->
	<!--正文内容部分-->
	<div class="mdui-container">
	    <?php if($WeatherPosition==2){ echo '<div style="position:fixed;z-index:1000;right:0px;width:160px;padding-right:0px;"><div id="he-plugin-simple"></div></div>'."\n";} ?>
		<div class="mdui-row">
			<!-- 遍历分类目录 -->
            <?php foreach ( $categorys as $category ) {
                $fid = $category['id'];
                $links = get_links($fid);
                //如果分类是私有的
                if( $category['property'] == 1 ) {
                    $property = '&nbsp;<i class="fa fa-lock" style = "color:#5FB878"></i>';
                }
                else {
                    $property = '';
                }
            ?>
			<div id = "category-<?php echo $category['id']; ?>" class = "mdui-col-xs-12 mdui-typo-title cat-title CFC">
			<?php echo geticon($category['Icon']).'&nbsp;'.$category['name'].$property;?>
				<span class = "mdui-typo-caption DFC"><?php echo $category['description']; ?></span>
			</div>
			<!-- 遍历链接 -->
			<?php
				foreach ($links as $link) {
					//默认描述
					$link['description'] = empty($link['description']) ? '作者很懒，没有填写描述。' : $link['description'];
				//var_dump($link);
			?>
			<div class="mdui-col-lg-3 mdui-col-md-4 mdui-col-xs-12 link-space"  id = "id_<?php echo $link['id']; ?>" link-title = "<?php echo $link['title']; ?>" link-url = "<?php echo $link['url']; ?>">
			    <span style = "display:none;"><?php echo $link['url']; ?></span>
				<!--定义一个卡片-->
				<div class="mdui-card link-line mdui-hoverable CBC">
						<!-- 如果是私有链接，则显示角标 -->
						<?php if($link['property'] == 1 ) { ?>
						<div class="angle">
							<span> </span>
						</div>
						<?php } ?>
						<!-- 角标END -->
						<a class="TFC" href="<?php echo geturl($link); ?>" target="_blank" <?php echo $protectA; ?> title = "<?php echo $link['description']; ?>">
							<div class="mdui-card-primary" >
									<div class="mdui-card-primary-title link-title">
										<img src="<?php echo geticourl($IconAPI,$link); ?>" alt="HUAN" width="16px" height="16px" <?php echo $protectIMG; ?>>
										<span class="link_title"><?php echo $link['title']; ?></span> 
									</div> 
							</div>
						<?php echo $CLBT; ?>
						<!-- 卡片的内容end -->
					<div class="mdui-card-content mdui-text-color-black-disabled DFC" style="padding-top:0px;"><span class="link-content"><?php echo $link['description']; ?></span></div><?php echo $CLALL; ?>
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
	<footer >
    <?php if($ICP != ''){echo '<a class="DFC" href="https://beian.miit.gov.cn" target="_blank">'.$ICP.'</a>';} ?>
    <?php echo $site['custom_footer']; ?>
    <?php echo $Ofooter; ?>
	</footer>
	 
	<!-- footerend -->
<script src = "<?php echo $libs?>/jquery/jquery-3.6.0.min.js"></script>
<script src = "<?php echo $libs?>/Layer/v3.3.0/layer.js"></script> 
<script src = "<?php echo $libs?>/ContextMenu/2.9.2/jquery.contextMenu.min.js"></script>
<script src = "<?php echo $libs?>/Other/ClipBoard.min.js"></script>
<script src = "<?php echo $libs?>/MDUI/v1.0.1/js/mdui.min.js"></script>
<script src = "<?php echo $libs?>/Other/holmes.js"></script>
<script src = "<?php echo $Theme?>/static/jquery.qrcode.min.js"></script>
<script src = "<?php echo $Theme?>/static/embed.js?v=<?php echo $version.time(); ?>"></script>
<?php 
// 如果Key不为空,则加载天气插件!
if ($WeatherPosition != 0){
    $WeatherFontColor = getconfig($config.'WeatherFontColor','1');  
    if ($WeatherFontColor == 1){
        $WeatherFontColor = getconfig($config.'HeadFontColor','#ffffff');
    }elseif($WeatherFontColor == 2){
        $WeatherFontColor = getconfig($config.'TitleFontColor','#212121');
    }
    ?>
<!--天气插件-->
<script>
WIDGET = {
  "CONFIG": {
    "modules": "01234", //实况温度、城市、天气状况、预警
    "background": "<?php echo getconfig($config.'WeatherBackground','1');?>", //背景颜色
    "tmpColor": "<?php echo $WeatherFontColor ?>", //温度文字颜色
    "tmpSize": "16",
    "cityColor": "<?php echo $WeatherFontColor ?>", //城市名文字颜色
    "citySize": "16",
    "aqiColor": "<?php echo $WeatherFontColor ?>", //空气质量文字颜色
    "aqiSize": "16", 
    "weatherIconSize": "24", //天气图标尺寸
    "alertIconSize": "18", //预警图标尺寸
    "padding": "5px 1px 5px 1px", //边距
    "borderRadius": "5", //圆角
    "key": "<?php echo $WeatherKey;?>"
  }
}
</script>
<script src="https://widget.qweather.net/simple/static/js/he-simple-common.js?v=2.0"></script>
<!--天气插件End-->
<?php
}
?>
<script>
var u = '<?php echo $u?>';
var t = '<?php echo str_replace("./templates/", "", $Theme);?>';
<?php echo $onenav['right_menu']."\n"; ?>
</script>
</body>
</html>