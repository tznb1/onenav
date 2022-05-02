<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="author" content="viggo" />
	<title><?php echo $site['Title'];?></title>
	<?php if($site['keywords'] !=''){echo '<meta name="keywords" content="'.$site['keywords'].'"/>'."\n";}?>
	<?php if($site['description'] !=''){echo '<meta name="description" content="'.$site['description'].'"/>'."\n";}?>
    <!--<link rel="stylesheet" href="<?php echo $Theme?>/assets/css/Arimo.css"> //英文字库-->
    <link rel="stylesheet" href="<?php echo $Theme?>/assets/css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo $Theme?>/assets/css/xenon-core.css">
    <link rel="stylesheet" href="<?php echo $Theme?>/assets/css/xenon-components.css">
    <link rel="stylesheet" href="<?php echo $Theme?>/assets/css/xenon-skins.css">
    <link rel="stylesheet" href="<?php echo $Theme?>/assets/css/nav.css">
    <link rel="stylesheet" href="<?php echo $libs?>/Font-awesome/4.7.0/css/font-awesome.css">
    <link rel="stylesheet" href="<?php echo $libs?>/Layui/v2.6.8/css/layui-icon.css">
    <script src="<?php echo $libs?>/jquery/jquery-3.6.0.min.js"></script>
    <?php echo $site['custom_header']; ?>
</head>
<body class="page-body">
    <!-- skin-white -->
    <div class="page-container">
        <div class="sidebar-menu toggle-others fixed" >
            <div class="sidebar-menu-inner">
                <header class="logo-env">
                    <!-- logo -->
                    <div class="logo">
                        <a href="" class="logo-expanded">
                            <h1 style = "color:#0099FF;"><?php echo $site['logo'];?></h1>
                        </a>
                        <a href="" class="logo-collapsed">
                            <img src="<?php echo $Theme?>/assets/images/logo-collapsed@2x.png" width="40" alt="" />
                        </a>
                    </div>
                    <div class="mobile-menu-toggle visible-xs">
                    <a href="#" data-toggle="mobile-menu"><i class="fa-bars"></i></a>
                    </div>
                </header>
                
                <ul id="main-menu" class="main-menu">
    <!-- 左侧登陆和后台 -->   
	<?php
	if($is_login) {
	?>
	
	<li><a href="./index.php?c=admin&u=<?php echo $u?>" class="smooth" onclick="window.open('./index.php?c=admin&u=<?php echo $u?>','_self')"><i class="fa fa-user-circle"></i><span class="title">后台管理</span></a></li>
	<?php }elseif ($site['GoAdmin']  ) {  ?>
	<li><a href="" class="smooth" onclick="window.open('./index.php?c=<?php if($login =='login'){echo $login;}else{echo $Elogin;}?>&u=<?php echo $u?>','_self')"><i class="fa fa-user-circle"></i><span class="title">登录</span></a></li>
	<?php } ?>    
	<!-- 左侧登陆和后台End -->   
     <?php
     //遍历分类目录并显示
     foreach ($categorys as $category) {
     ?>
     <li><a href="#category-<?php echo $category['id']; ?>" class="smooth"><?php echo geticon($category['Icon']); ?><span class="title"><?php echo $category['name'];?></span></a></li>
     <?php } ?>
     </ul>
     </div>
     </div>
     <div class="main-content">
            <nav class="navbar user-info-navbar" role="navigation">
            </nav>
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
            <h4 class="text-gray" id = "category-<?php echo $fid ?>"><?php echo geticon($category['Icon']).'&nbsp;'.$category['name'].'&nbsp;'.$property;?></h4>
            <!-- 遍历链接 -->
            <div class="row">
                <?php
                    foreach ($links as $link) {
                        $linkURL=$site['urlz']  == 'on' ? $link['url'] :'./index.php?c=click&id='.$link['id'].'&u='.$u;
                        //判断是否是私有项目
                        if( $link['property'] == 1 ) {
                            $privacy_class = 'property';
                        }
                        else {
                            $privacy_class = '';
                        }
                ?>
                <div class="col-sm-3">
                    <div class="<?php echo $privacy_class; ?> xe-widget xe-conversations box2 label-info" onclick="window.open('<?php echo $linkURL?>', '_blank')" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="<?php echo $link['url']; ?>">
                        <div class="xe-comment-entry">
                            <span class="label label-info" data-toggle="tooltip" data-placement="left" title="" data-original-title="Hello I am a Tooltip"></span>
                            <div class="xe-comment">
                                <a href="#" class="xe-user-name overflowClip_1">
                                <img src="<?php echo geticourl($IconAPI,$link); ?>" alt="HUAN" width="16" height="16" />
                                    <strong><?php echo $link['title']; ?></strong>
                                </a>
                                <p class="overflowClip_2"><?php echo $link['description']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
            <div class="placeholder" style = "height:2em;"></div> 
            <?php } ?>
            <br />
            <footer class="main-footer sticky footer-type-1">
                <div class="footer-inner">
                    <?php if($ICP != ''){echo '<a href="https://beian.miit.gov.cn" target="_blank">'.$ICP.'</a>';} ?>
                    <?php echo $site['custom_footer']; ?>
                    <?php echo $Ofooter; ?>
                </div>
                <?php if($site['gotop'] ){echo '<div class="go-up"><a href="#" rel="go-top"><i class="fa-angle-up"></i></a></div>';}?>
              </div>
            </footer>
        </div>
    </div>
    <!-- 锚点平滑移动 -->
    <script type="text/javascript">
    $(document).ready(function() {
         //img lazy loaded
         const observer = lozad();
         observer.observe();

        $(document).on('click', '.has-sub', function(){
            var _this = $(this)
            if(!$(this).hasClass('expanded')) {
               setTimeout(function(){
                    _this.find('ul').attr("style","")
               }, 300);
              
            } else {
                $('.has-sub ul').each(function(id,ele){
                    var _that = $(this)
                    if(_this.find('ul')[0] != ele) {
                        setTimeout(function(){
                            _that.attr("style","")
                        }, 300);
                    }
                })
            }
        })
        $('.user-info-menu .hidden-sm').click(function(){
            if($('.sidebar-menu').hasClass('collapsed')) {
                $('.has-sub.expanded > ul').attr("style","")
            } else {
                $('.has-sub.expanded > ul').show()
            }
        })
        $("#main-menu li ul li").click(function() {
            $(this).siblings('li').removeClass('active'); // 删除其他兄弟元素的样式
            $(this).addClass('active'); // 添加当前元素的样式
        });
        $("a.smooth").click(function(ev) {
            ev.preventDefault();

            public_vars.$mainMenu.add(public_vars.$sidebarProfile).toggleClass('mobile-is-visible');
            ps_destroy();
            $("html, body").animate({
                scrollTop: $($(this).attr("href")).offset().top - 30
            }, {
                duration: 500,
                easing: "swing"
            });
        });
        return false;
    });
    var href = "";
    var pos = 0;
    $("a.smooth").click(function(e) {
        $("#main-menu li").each(function() {
            $(this).removeClass("active");
        });
        $(this).parent("li").addClass("active");
        e.preventDefault();
        href = $(this).attr("href");
        pos = $(href).position().top - 30;
    });
    </script>
    <!-- Bottom Scripts -->
    <script src="<?php echo $Theme?>/assets/js/bootstrap.min.js"></script>
    <script src="<?php echo $Theme?>/assets/js/TweenMax.min.js"></script>
    <script src="<?php echo $Theme?>/assets/js/resizeable.js"></script>
    <script src="<?php echo $Theme?>/assets/js/joinable.js"></script>
    <script src="<?php echo $Theme?>/assets/js/xenon-api.js"></script>
    <script src="<?php echo $Theme?>/assets/js/xenon-toggles.js"></script>
    <!-- JavaScripts initializations and stuff -->
    <script src="<?php echo $Theme?>/assets/js/xenon-custom.js"></script>
    <script src="<?php echo $Theme?>/assets/js/lozad.js"></script>
</body>
</html>