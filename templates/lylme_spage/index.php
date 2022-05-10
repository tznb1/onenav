<!DOCTYPE html>
<html lang="zh-CN" element::-webkit-scrollbar {display:none}>
	<head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1"/>
		<meta charset="utf-8">
		<title><?php echo $site['Title'];?></title>
    	<?php if($site['keywords'] !=''){echo '<meta name="keywords" content="'.$site['keywords'].'"/>'."\n";}?>
    	<?php if($site['description'] !=''){echo '<meta name="description" content="'.$site['description'].'"/>'."\n";}?>
		<meta name="author" content="LyLme">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-touch-fullscreen" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="full-screen" content="yes">
		<meta name="browsermode" content="application">
		<meta name="x5-fullscreen" content="true">
		<meta name="x5-page-mode" content="app">
		<meta name="lsvn" content="MS4xLjM=">
		<script src="<?php echo $Theme?>/js/jquery.min.js" type="application/javascript"></script>
		<link rel="stylesheet" href="<?php echo $libs?>/Font-awesome/4.7.0/css/font-awesome.css">
		<link rel="stylesheet" href="<?php echo $Theme?>/css/bootstrap.min.css" type="text/css">
		<link rel="stylesheet" href="<?php echo $Theme?>/css/style.css" type="text/css">
		<link rel="stylesheet" href="<?php echo $Theme?>/css/font.css" type="text/css">
		<link rel="stylesheet" href="<?php echo $Theme?>/css/tag.css" type="text/css">
		<style>.change-type .showListType{width:<?php echo getconfig($config.'changewidth','130');?>px;}</style>
		<?php echo $site['custom_header']; ?>
	</head>
    <body onload="FocusOnInput()">
        <div class="banner-video">
            <img src="<?php echo getconfig($config.'backgroundURL','https://api.isoyu.com/bing_images.php'); ?>">
			<div class="bottom-cover" style="background-image: linear-gradient(rgba(255, 255, 255, 0) 0%, rgb(244 248 251 / 0.6) 50%, rgb(244 248 251) 100%);">
			</div>
		</div>
<div class="box"  style="height:90%;">
    <div class="change-type"  style="height:80%;">
        <div style="overflow-y:auto;" class="type-left" :class="showType == true ? 'showListType':''">
            <ul>
<li  data-lylme="search"><a>搜索</a><span></span></li>
<?php
//遍历分类目录并显示
foreach ($categorys as $category) {
    echo '<li data-lylme="group_'. $category["id"] . '"><a>'. $category["name"] . '</a><span></span></li>'."\n";
} 
?>



            </ul>
        </div>
        
    </div>
</div>	
<script>
    $(function(){
        $('.type-right').click(function(){
            $('.type-left').toggleClass('showListType')
        });
        $('.type-left ul li').click(function(){
            $(this).addClass('active').siblings('li').removeClass('active');
            $('.type-left').toggleClass('showListType');
            var lylme_tag = '#'+$(this).attr("data-lylme");
            $('html,body').animate({scrollTop:$(lylme_tag).offset().top},500);
            
        })
    })
</script>
		<!--topbar开始-->
		<nav class="navbar navbar-expand-lg navbar-light fixed-top" style="position: absolute; z-index: 10000;">
		<button class="navbar-toggler collapsed" style="border: none; outline: none;"type="button" data-toggle="collapse" data-target="#navbarsExample05" aria-controls="navbarsExample05" aria-expanded="false" aria-label="Toggle navigation">
		    <svg class="icon" width="200" height="200"><use xlink:href="#icon-menus"></use></svg>
		    <span><svg class="bi bi-x" 	fill="currentColor" id="x"><use xlink:href="#icon-closes"></use></svg><span>
		</button>
		<div class="type-right" >
           <svg  t="1651476001599" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="6329" width="200" height="200"><path d="M512 491.52l286.72-286.72-522.24 174.08L512 491.52zM137.216 337.92L866.304 96.256c16.384-6.144 34.816 4.096 40.96 20.48 2.048 6.144 2.048 14.336 0 20.48L665.6 866.304c-6.144 16.384-24.576 26.624-40.96 20.48-8.192-2.048-14.336-8.192-18.432-16.384L450.56 552.96 133.12 399.36c-16.384-8.192-22.528-26.624-14.336-43.008 2.048-8.192 10.24-14.336 18.432-18.432z" fill="#304ECE" p-id="6330"></path></svg>
        </div>
			<div class="collapse navbar-collapse" id="navbarsExample05">
				<ul class="navbar-nav mr-auto">
				    <li class="nav-item"><a class="nav-link" href="" target="_blant">首页</a></li>
				    <?php if($is_login) { ?>
                            <li class="nav-item"><a class="nav-link" href="./index.php?c=admin&u=<?php echo $u?>" target="_blank"><span>后台管理</span></a>
                            </li>
                            <?php }elseif ($site['GoAdmin']  ) {  ?>
                            <li class="nav-item"><a class="nav-link" href="./index.php?c=<?php if($login =='login'){echo $login;}else{echo $Elogin;}?>&u=<?php echo $u?>" target="_blank"><span>登陆</span></a>
                            </li>
                     <?php } ?>
				    <!--天气开始-->
				    <div id="he-plugin-simple"></div>
                    <script src="https://widget.qweather.net/simple/static/js/he-simple-common.js?v=2.0"></script>
                    <!--天气结束-->
				</ul>
				<div id="main">  
                    <div id="show_date"></div>  
                    <div id="show_time"></div>
                </div>	
		</div>
		</nav>
		<!--topbar结束-->
		<div class="container" style="margin-top:10vh; position: relative; z-index: 100;">
		    <h2 class="title">上网，从这里开始！</h2>
		    <!--一言开始-->
		    <script src="https://v1.hitokoto.cn/?encode=js&select=%23hitokoto" defer></script>
		    <p id="hitokoto" class="content"></p>
			<!--一言结束-->
			<!--搜索开始-->
			<div id="search" class="s-search">
				<div id="search-list" class="hide-type-list">
					<div class="search-group group-a s-current" style=" margin-top: 50px;">
						<ul class="search-type">
							<li>
								<input hidden=""  checked="" type="radio" name="type" id="type-baidu" value="https://www.baidu.com/s?word="data-placeholder="百度一下，你就知道">
								<label for="type-baidu" style="font-weight:600">
								<svg class="icon" aria-hidden="true"><use xlink:href="#icon-icon_baidulogo"></use></svg>
									<span style="color:#0c498c">
										百度一下
									</span>
								</label>
							</li>
								<li>
								<input hidden=""  checked="" type="radio" name="type" id="type-sogou" value="https://www.sogou.com/web?query="data-placeholder="上网从搜狗开始">
								<label for="type-sogou" style="font-weight:600">
								<svg class="icon" aria-hidden="true"><use xlink:href="#icon-sougou"></use></svg>
									<span style="color:#696a6d">
										搜狗搜索
									</span>
								</label>
							</li>
								<li>
								<input hidden=""  checked="" type="radio" name="type" id="type-bing" value="https://cn.bing.com/search?q="data-placeholder="微软必应搜索">
								<label for="type-bing" style="font-weight:600">
								<svg class="icon" aria-hidden="true"><use xlink:href="#icon-bing"></use></svg>
									<span style="color:#696a6d">
										Bing必应
									</span>
								</label>
							</li>
								<li>
								<input hidden=""  checked="" type="radio" name="type" id="type-zhihu" value="https://www.zhihu.com/search?q="data-placeholder="有问题，上知乎">
								<label for="type-zhihu" style="font-weight:600">
								<svg class="icon" aria-hidden="true"><use xlink:href="#icon-zhihu"></use></svg>
									<span style="color:#0084fe">
										知乎搜索
									</span>
								</label>
							</li>
								<li>
								<input hidden=""  checked="" type="radio" name="type" id="type-bilibili" value="https://search.bilibili.com/all?keyword="data-placeholder="(゜-゜)つロ 干杯~">
								<label for="type-bilibili" style="font-weight:600">
								<svg class="icon" aria-hidden="true"><use xlink:href="#icon-bili"></use></svg>
									<span style="color:#00aeec">
										哔哩哔哩
									</span>
								</label>
							</li>
								<li>
								<input hidden=""  checked="" type="radio" name="type" id="type-google" value="https://www.google.com.hk/search?hl=zh-CN&q="data-placeholder="值得信任的搜索引擎">
								<label for="type-google" style="font-weight:600">
								<svg class="icon" aria-hidden="true"><use xlink:href="#icon-google00"></use></svg>
									<span style="color:#3B83FA">
										谷歌搜索
									</span>
								</label>
							</li>
								<li>
								<input hidden=""  checked="" type="radio" name="type" id="type-csdn" value="https://so.csdn.net/so/search?q="data-placeholder="CSDN搜索">
								<label for="type-csdn" style="font-weight:600">
								<svg class="icon" aria-hidden="true"><use xlink:href="#icon-csdn"></use></svg>
									<span style="color:#fc5531">
										CSDN搜索
									</span>
								</label>
							</li>
								<li>
								<input hidden=""  checked="" type="radio" name="type" id="type-fanyi" value="https://translate.google.cn/?hl=zh-CN&sl=auto&tl=zh-CN&text="data-placeholder="输入翻译内容（自动检测语言）">
								<label for="type-fanyi" style="font-weight:600">
								<svg class="icon" aria-hidden="true"><use xlink:href="#icon-fanyi"></use></svg>
									<span style="color:#0084fe">
										在线翻译
									</span>
								</label>
							</li>
													  
						</ul>
					</div>
				</div>
				<form action="https://www.baidu.com/s?wd=" method="get" target="_blank" id="super-search-fm">
					<input type="text" id="search-text" placeholder="百度一下，你就知道" style="outline:0" autocomplete="off">
					<button class="submit" type="submit">
						<svg style="width: 22px; height: 22px; margin: 0 20px 0 20px; color: #fff;" class="icon" aria-hidden="true">
							<use xlink:href="#icon-sousuo"></use>
						</svg>
						<span>
					</button>
					<ul id="word" style="display: none;"></ul>
				</form>
				<div class="set-check hidden-xs">
					<input type="checkbox" id="set-search-blank" class="bubble-3" autocomplete="off">
				</div>
			</div>
			<!--搜索结束-->
<?php
include "list.php";
include "footer.php";
?>