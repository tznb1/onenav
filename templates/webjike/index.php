<!DOCTYPE html>
<html lang="zh-cn">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1"><title><?php echo getconfig("title");?></title>
<?php $keywords=getconfig("keywords"); if($keywords !=''){echo '<meta name="keywords" content="'.$keywords.'"/>'."\n";}?>
<?php $description=getconfig("description"); if($description !=''){echo '<meta name="description" content="'.$description.'"/>'."\n";}?>
		<title><?php echo getconfig('title');?></title>
	    <?php $keywords=getconfig("keywords"); if($keywords !=''){echo '<meta name="keywords" content="'.$keywords.'"/>'."\n";}?>
	    <?php $description=getconfig("description"); if($description !=''){echo '<meta name="description" content="'.$description.'"/>'."\n";}?>
		<link rel="stylesheet" href="<?php echo $Theme?>/css/zui.min.css">
		<link rel="stylesheet" href="<?php echo $libs?>/Layui/v2.6.8/css/layui.css">
		<link rel="stylesheet" href="<?php echo $Theme?>/css/main-style.css?<?php echo $version; ?>">
		<link rel="stylesheet" href="<?php echo $libs?>/Font-awesome/4.7.0/css/font-awesome.css">
		<?php $head=getconfig("head");if($head!='' && ($Diy==='1' || $userdb['Level']==='999')){echo(htmlspecialchars_decode(base64_decode($head)));} //自定义头部代码?> 
	</head>
	<body data-page-name="office">
		<!--[if lt IE 8]>
    	<div class="alert alert-danger">您正在使用 <strong>过时的</strong> 浏览器. 是时候 <a href="http://browsehappy.com/">更换一个更好的浏览器</a> 来提升用户体验.</div>
    	<![endif]-->
    	<!--头部导航条Start-->
    	<header id="fenzhi-nav">
    		<div class="main">
    			<div class="logo">
    				<a href=""><img src="<?php echo $Theme?>/images/logo.png"><span><?php echo getconfig('logo');?></span></a>
    			</div>
    		</div>
    	</header>
    		<!--头部导航条End-->
    		<!--Content Start-->
    		<div id="content">
    			<div class="left-bar">
    				<!--左侧 Start-->
    				<div class="menu" id="menu">
    					<ul class="scrollcontent">
    						<?php
								//遍历分类目录并显示
								foreach ($categorys as $category) {
							?>
								<li>
									<a href="#row-<?php echo $category['id']; ?>" ><i class="fa fa-fw "><?php echo geticon3($category['Icon']); ?></i><?php echo $category['name']; ?></a>
								</li>
							<?php } ?>
    					</ul>
    				</div>
    	<?php if( $is_login ) { ?>
    			<div class="menu-about"><i class="fa fa-user-circle icon-fw icon-lg mr-2"></i><span><a href="./index.php?c=admin&u=<?php echo $u?>">后台管理</a></span></div>
		<?php }elseif(getconfig('GoAdmin')  == 'on'  ){ ?>
		        <div class="menu-about"><i class="fa fa-user-circle icon-fw icon-lg mr-2"></i><span><a href="./index.php?c=<?php if($login =='login'){echo $login;}else{echo $Elogin;}?>&u=<?php echo $u?>">登录后台</a></span></div>
		<?php } ?>

    			</div>
    			<!--正文 Start-->
    			<div class="duty-main">
    				<div class="container content-box">
    					<section class="sousuo">
    						<div class="search">
    							<div class="search-box">
    								<span class="search-icon" style="background: url('<?php echo $Theme?>/images/search_icon.png')"></span>
    								<input type="text" id="txt" class="search-input" placeholder="请输入关键字，按回车 / Enter 搜索" />
    								<button class="search-btn" id="search-btn"><i class="fa fa-search"></i></button>
    							</div>
	    						<!-- 搜索热词 -->
	    						<div class="box search-hot-text" id="box" style="display: none"><ul></ul></div>
	    						<!-- 搜索引擎 -->
	    						<div class="search-engine">
	    							<div class="search-engine-head">
	    								<strong class="search-engine-tit">选择您的默认搜索引擎：</strong>
	    								<div class="search-engine-tool">搜索热词： <span id="hot-btn"></span></div>
	    							</div>
	    							<ul class="search-engine-list"></ul>
	    						</div>
    						</div>
    					</section>
    					<div class="row duty-custom layui-hide-sm layui-hide-md layui-hide-lg" >
    						<!--自添加链接-->
    						<div class="col-md-12">
    						<strong class="duty-item-name"><i class="layui-icon layui-icon-set"></i>&nbsp;系统管理</strong>
    						</div>
    						<div class="col-md-12">
         						 <ul class="duty-custom-item">
        <?php if( $is_login ) { ?>
        <li><a class="duty-custom-link fa fa-user-circle" href="./index.php?c=admin&u=<?php echo $u?>">&nbsp;后台管理</a></li>
        <li><a class="duty-custom-link layui-icon layui-icon-return" href="./index.php?c=admin&page=logout&u=<?php echo $u?>">&nbsp;退出登录</a></li>
		<?php }elseif(getconfig('GoAdmin')  == 'on'  ){ ?>
		<li><a class="duty-custom-link" href="./index.php?c=<?php if($login =='login'){echo $login;}else{echo $Elogin;}?>&u=<?php echo $u?>">&nbsp;登录后台</a></li>
		<?php } ?>
        						 </ul>
    						</div>
    					</div>
    					<div class="row">
    						<div class="col-md-12">
    							<div class="duty-tool">
    								<div class="duty-tool-notice">
    									<i class="fa fa-volume-up" aria-hidden="true"></i>
    									<span><?php echo $description ?></span>
    								</div>
    								<ul class="duty-tool-switch hidden-xs hidden-sm">
						                <li><span>图标</span><i class="fa fa-toggle-on"></i></li>
						                <li><span>描述</span><i class="fa fa-toggle-on"></i></li>
					                </ul>
    							</div>
    						</div>
    					</div>
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
			            <div class="row duty-item">
			            	<div class="col-md-12">
			            		<div id="row-<?php echo $category['id']; ?>" class="anchor-link"></div>
			            		<strong class="duty-item-name"><?php echo geticon($category['Icon']).$category['name'].$property;?></strong>
			            	</div>
			            	<div class="col-md-12 ">
			            		<div class="row">
			            			<!-- 遍历链接 -->
									<?php
										foreach ($links as $link) {
										//默认描述
										$link['description'] = empty($link['description']) ? '作者很懒，没有填写描述。' : $link['description'];
									?>
									<div class="col-md-3 col-sm-4 col-xs-6">
										<!-- 如果是私有链接，则显示角标 -->
										<?php if($link['property'] == 1 ) { ?>
											<div class="angle"><span> </span></div>
											<?php } ?>
											<!-- 角标END -->
											<?php
												if (getconfig('urlz')  == 'on'  ){
												    ?><a rel="nofollow" href="<?php echo $link['url']; ?>" title="<?php echo $link['description']; ?>" class="duty-card" target="_blank"><?php
												}else{
												    ?><a rel="nofollow" href="./index.php?c=click&id=<?php echo $link['id']; ?>&u=<?php echo $u?>" title="<?php echo $link['description']; ?>" class="duty-card" target="_blank"><?php
												};
											?>
											<div class="duty-card-tit"><img src="<?php if (getconfig('LoadIcon')  == 'on'  ){echo geticourl($IconAPI,$link['url']);}else{echo $libs.'/Other/default.ico';} ?>" data-original="<?php if (getconfig('LoadIcon')  == 'on'  ){echo geticourl($IconAPI,$link['url']);}else{echo $libs.'/Other/default.ico';} ?>" onerror="javascript:this.src='<?php if (getconfig('LoadIcon')  == 'on'  ){echo geticourl($IconAPI,$link['url']);}else{echo $libs.'/Other/default.ico';} ?>" alt="<?php echo $link['title']; ?>" ><?php echo $link['title']; ?></div>
                        					<div class="duty-card-des"><?php echo $link['description']; ?></div>
                        				</a>
									</div>
									<?php } ?>
			            		</div>
			            	</div>
			            </div>
			        <?php } ?>
    				</div>
    			</div>
    			<!--正文 End-->
    			<footer class="footer">
    				<div class="container">
    					<div class="row">
    						<div class="col-md-12">
    							<div class="footer-main">
    								<!--备案信息-->
    								<p>Copyright © <?php echo date('Y');?> All Rights Reserved &nbsp;<?php if($ICP != ''){echo '<img src="'.$Theme.'/images/icp.png" width="16px" height="16px" /><a href="https://beian.miit.gov.cn" target="_blank">'.$ICP.'</a>';} ?>&nbsp;&nbsp;
    								Powered by&nbsp;&nbsp;<a target="_blank" href="https://github.com/helloxz/onenav" title="简约导航/书签管理器" target="_blank" rel="nofollow">OneNav</a>&nbsp;&nbsp;<a href="https://gitee.com/tznb/OneNav" target="_blank" rel="nofollow">落幕魔改版</a>&nbsp;&nbsp;The theme by&nbsp;&nbsp;<a href="https://github.com/xiaodai945/WEBJIKE" target="_blank" rel="nofollow">小呆导航</a>
								    <!--公用底部-->
								    <?php $footer=getconfig("footer"); if($footer != ''&& ($Diy==='1' || $userdb['Level']==='999')){echo(htmlspecialchars_decode(base64_decode($footer)));} ?><br/>
								    <?php if($Ofooter != ''){echo $Ofooter;} //公用底部?>
    								</p>
    							</div>
    						</div>
    					</div>
    				</div>
    			</footer>
    			<div id="top" class="hidden-xs hidden-sm">
    				<ul>
					    <li class="top-item">
					      	<a href="javascript:;" data-tooltip="返 回顶 部" style="display: none" id="go-top"><i class="fa fa-chevron-up"></i></a>
					    </li>
					 </ul>
    			</div>
    		</div>
    		<!--Content End-->
	<script src="<?php echo $libs?>/jquery/jquery-2.2.4.min.js"></script>
	<script src="<?php echo $Theme?>/js/main.min.js?v=3.0.3"></script>
	</body>
</html>