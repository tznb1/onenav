<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?php echo getconfig('title');?></title>
    <meta name="theme-color" content="#f9f9f9">
    <?php $keywords=getconfig("keywords"); if($keywords !=''){echo '<meta name="keywords" content="'.$keywords.'"/>'."\n";}?>
    <?php $description=getconfig("description"); if($description !=''){echo '<meta name="description" content="'.$description.'"/>'."\n";}?>
    <link rel='stylesheet' id='wp-block-library-css' href='<?php echo $Theme?>/wp-includes/css/dist/block-library/style.min-5.6.2.css' type='text/css' media='all'>
    <link rel="stylesheet" href="<?php echo $libs?>/Font-awesome/4.7.0/css/font-awesome.css">
    <link rel='stylesheet' id='iconfont-css' href='<?php echo $Theme?>/wp-content/themes/onenav/css/iconfont-3.03029.1.css' type='text/css' media='all'>
    <link rel='stylesheet' id='iconfontd-css' href='<?php echo $Theme?>/t/font_1620678_7g0p3h6gbl-3.03029..css' type='text/css' media='all'>
    <link rel='stylesheet' id='bootstrap-css' href='<?php echo $Theme?>/wp-content/themes/onenav/css/bootstrap.min-3.03029.1.css' type='text/css' media='all'>
    <link rel='stylesheet' id='lightbox-css' href='<?php echo $Theme?>/wp-content/themes/onenav/css/jquery.fancybox.min-3.03029.1.css' type='text/css' media='all'>
    <link rel='stylesheet' id='style-css' href='<?php echo $Theme?>/wp-content/themes/onenav/css/style-3.03029.1.css' type='text/css' media='all'>
    <script type='text/javascript' src='<?php echo $Theme?>/wp-content/themes/onenav/js/jquery.min-3.03029.1.js' id='jquery-js'></script>
    <?php $head=getconfig("head");if($head!=''&& ($Diy==='1' || $userdb['Level']==='999')){echo(htmlspecialchars_decode(base64_decode($head)));} //自定义头部代码?> 
    <style>
        #footer-tools [data-v-db6ccf64][data-v-41ba7e2c] {
            top: unset !important;
            bottom: 0 !important;
            right: 44px !important
        }

        .io.icon-fw,
        .iconfont.icon-fw {
            width: 1.15em;
        }

        .io.icon-lg,
        .iconfont.icon-lg {
            font-size: 1.5em;
            line-height: .75em;
            vertical-align: -.125em;
        }

        .screenshot-carousel .img_wrapper a {
            display: contents
        }

        .fancybox-slide--iframe .fancybox-content {
            max-width: 1280px;
            margin: 0
        }

        .fancybox-slide--iframe.fancybox-slide {
            padding: 44px 0
        }

        .navbar-nav .menu-item-286 a {
            background: #ff8116;
            border-radius: 50px !important;
            padding: 5px 10px !important;
            margin: 5px 0 !important;
            color: #fff !important;
        }

        .navbar-nav .menu-item-286 a i {
            position: absolute;
            top: 0;
            right: -10px;
            color: #f13522;
        }

        .io-black-mode .navbar-nav .menu-item-286 a {
            background: #ce9412;
        }

        .io-black-mode .navbar-nav .menu-item-286 a i {
            color: #fff;
        }
    </style>
</head>

<body class="io-grey-mode">
    <div id="loading">
        <style>
            .loader {
                width: 250px;
                height: 50px;
                line-height: 50px;
                text-align: center;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                font-family: helvetica, arial, sans-serif;
                text-transform: uppercase;
                font-weight: 900;
                color: #f1404b;
                letter-spacing: 0.2em
            }

            .loader::before,
            .loader::after {
                content: "";
                display: block;
                width: 15px;
                height: 15px;
                background: #f1404b;
                position: absolute;
                animation: load .7s infinite alternate ease-in-out
            }

            .loader::before {
                top: 0
            }

            .loader::after {
                bottom: 0
            }

            @keyframes load {
                0% {
                    left: 0;
                    height: 30px;
                    width: 15px
                }

                50% {
                    height: 8px;
                    width: 40px
                }

                100% {
                    left: 235px;
                    height: 30px;
                    width: 15px
                }
            }
        </style>
        <div class="loader"><?php echo getconfig('logo');?></div>
    </div>
    <div class="page-container">
        <div id="sidebar" class="sticky sidebar-nav fade mini-sidebar" style="width: 60px;">
            <div class="modal-dialog h-100  sidebar-nav-inner">
                <!--<div class="sidebar-logo border-bottom border-color">-->
                    <!-- logo -->
                <!--    <div class="logo overflow-hidden">-->
                <!--        <a href="" class="logo-expanded">-->
                <!--            <img src="<?php echo $Theme?>/img/ywdh-logo-1.png" height="40" class="logo-light" alt="网址导航">-->
                <!--            <img src="<?php echo $Theme?>/img/ywdh-logo.png" height="40" class="logo-dark d-none" alt="网址导航">-->
                <!--        </a>-->
                <!--        <a href="" class="logo-collapsed">-->
                <!--            <img src="<?php echo $Theme?>/img/ywdh-logo-bark-ico.png" height="40" class="logo-light" alt="网址导航">-->
                <!--            <img src="<?php echo $Theme?>/img/ywdh-logo-ico.png" height="40" class="logo-dark d-none" alt="网址导航">-->
                <!--        </a>-->
                <!--    </div>-->
                    <!-- logo end -->
                <!--</div>-->
                <div class="sidebar-menu flex-fill">
                    <div class="sidebar-scroll">
                        <div class="sidebar-menu-inner">
                            <ul>
                                <?php
                                    //遍历分类目录并显示
                                    foreach ($categorys as $category) {
                                ?>
                                    <li class="sidebar-item">
                                        <a href="#category-<?php echo $category['id']; ?>" class="smooth"><?php echo geticon3($category['Icon']); ?>
                                            <?php echo $category['name']; ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <!--左栏底部-->
                <div class="border-top py-2 border-color">
                    <div class="flex-bottom">
                        <ul>
                            <?php if($is_login) { ?>
                            <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-237 sidebar-item">
                                <a href="./index.php?c=admin&u=<?php echo $u?>" target="_blank"><i class="fa fa-user icon-fw icon-lg mr-2"></i><span>后台管理</span></a>
                            </li>
                            <?php }elseif (getconfig('GoAdmin')  == 'on'  ) {  ?>
                            <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-237 sidebar-item">
                                <a href="./index.php?c=<?php if($login =='login'){echo $login;}else{echo $Elogin;}?>&u=<?php echo $u?>" target="_blank"><i class="fa fa-user icon-fw icon-lg mr-2"></i><span>登陆</span></a>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <!--左栏底部End-->
        </div>
    </div>
        <div class="main-content flex-fill">
            <div class="big-header-banner">
                <div id="header" class="page-header sticky">
                    <div class="navbar navbar-expand-md">
                        <div class="container-fluid p-0">
                            <!--<a href="" class="navbar-brand d-md-none" title="网址导航">-->
                            <!--    <img src="<?php echo $Theme?>/img/ywdh-logo-bark-ico.png" class="logo-light" alt="网址导航">-->
                            <!--    <img src="<?php echo $Theme?>/img/ywdh-logo-ico.png" class="logo-dark d-none" alt="网址导航">-->
                            <!--</a>-->
                            <div class="collapse navbar-collapse order-2 order-md-1">
                                <div class="header-mini-btn">
                                    <label>
                                        <input id="mini-button" type="checkbox">
                                        <svg viewbox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                                            <path class="line--1" d="M0 40h62c18 0 18-20-17 5L31 55"></path>
                                            <path class="line--2" d="M0 50h80"></path>
                                            <path class="line--3" d="M0 60h62c18 0 18 20-17-5L31 45"></path>
                                        </svg>
                                    </label>
                                </div>
                                <ul class="navbar-nav site-menu" style="margin-right: 16px;">

                                    
                                    <?php if($is_login) { ?>
                                    <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-281"><a href="./index.php?c=admin&u=<?php echo $u?>"><i class="fa fa-user-circle-o icon-fw icon-lg mr-2"></i><span>管理</span></a></li>
                                    <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-281"><a href="./index.php?c=admin&page=logout&u=<?php echo $u?>"><i class="fa fa-sign-out fa-lg mr-2"></i><span>退出</span></a></li>
                                    <?php }elseif (getconfig('GoAdmin')  == 'on'  ) {  ?>
                                        <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-281"><a href="./index.php?c=<?php if($login =='login'){echo $login;}else{echo $Elogin;}?>&u=<?php echo $u?>"><i class="fa fa-user-circle-o icon-fw icon-lg mr-2"></i><span>登陆</span></a></li>
                                    <?php } ?>
                                </ul>
                                <div class="rounded-circle weather">
                                    <div id="he-plugin-simple" style="display: contents;"></div>
                                    <script>WIDGET = {
                                            CONFIG: {
                                                "modules": "12034",
                                                "background": 5,
                                                "tmpColor": "E4C600",
                                                "tmpSize": 14,
                                                "cityColor": "E4C600",
                                                "citySize": 14,
                                                "aqiColor": "#E4C600",
                                                "aqiSize": 14,
                                                "weatherIconSize": 24,
                                                "alertIconSize": 18,
                                                "padding": "10px 10px 10px 10px",
                                                "shadow": "1",
                                                "language": "auto",
                                                "borderRadius": 5,
                                                "fixed": "false",
                                                "vertical": "middle",
                                                "horizontal": "left",
                                                "key": "e05c4ce44b7e43c6a9303e68cc42a48c"
                                            }
                                        }
                                    </script>
                                    <script src="https://widget.qweather.net/simple/static/js/he-simple-common.js?v=2.0"></script>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="placeholder" style="height:74px"></div>
            </div>
            <div class="header-big  post-top css-color mb-4" style="background-image: linear-gradient(45deg, #8618db 0%, #d711ff 50%, #460fdd 100%);">
                <div class="s-search">
                    <div id="search" class="s-search mx-auto">
                        <div id="search-list-menu" class="hide-type-list">
                            <div class="s-type text-center">
                                <div class="s-type-list big">
                                    <div class="anchor" style="position: absolute; left: 50%; opacity: 0;"></div>
                                    <label for="type-baidu" class="" data-id="group-a"><span>常用</span></label>
                                    <label for="type-baidu1" data-id="group-b"><span>搜索</span></label>
                                    <label for="type-br" data-id="group-c"><span>工具</span></label>
                                    <label for="type-zhihu" data-id="group-d"><span>社区</span></label>
                                    <label for="type-taobao1" data-id="group-e"><span>生活</span></label>
                                    <label for="type-zhaopin" data-id="group-f"><span>求职</span></label>
                                </div>
                            </div>
                        </div>
                        <form action="https://nav.iowen.cn?s=" method="get" target="_blank" class="super-search-fm">
                            <input type="text" id="search-text" class="form-control smart-tips search-key" zhannei="" placeholder="输入关键字搜索" style="outline:0" autocomplete="off">
                            <button type="submit"><i class="iconfont icon-search"></i></button>
                        </form>
                        <div id="search-list" class="hide-type-list">
                            <div class="search-group group-a ">
                                <ul class="search-type">
                                    <li>
                                        <input checked="checked" hidden="" type="radio" name="type" id="type-baidu" value="https://www.baidu.com/s?wd=" data-placeholder="百度一下">
                                        <label for="type-baidu"><span class="text-muted">百度</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type" id="type-google" value="https://www.google.com/search?q=" data-placeholder="谷歌两下">
                                        <label for="type-google"><span class="text-muted">谷歌</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type" id="type-zhannei" value="https://www.iplaysoft.com/?s=" data-placeholder="软件搜索">
                                        <label for="type-zhannei"><span class="text-muted">软件</span></label>
                                    </li>
                                    <li><input hidden="" type="radio" name="type" id="type-taobao" value="https://s.taobao.com/search?q=" data-placeholder="淘宝">
                                        <label for="type-taobao"><span class="text-muted">淘宝</span></label>
                                    </li>
                                    <li><input hidden="" type="radio" name="type" id="type-bing" value="https://cn.bing.com/search?q=" data-placeholder="微软Bing搜索">
                                        <label for="type-bing"><span class="text-muted">必应</span></label>
                                    </li>
                                </ul>
                            </div>
                            <div class="search-group group-b ">
                                <ul class="search-type">
                                    <li><input hidden="" type="radio" name="type" id="type-baidu1" value="https://www.baidu.com/s?wd=" data-placeholder="百度一下">
                                        <label for="type-baidu1"><span class="text-muted">百度</span></label>
                                    </li>
                                    <li><input hidden="" type="radio" name="type" id="type-google1" value="https://www.google.com/search?q=" data-placeholder="谷歌两下">
                                        <label for="type-google1"><span class="text-muted">谷歌</span></label>
                                    </li>
                                    <li><input hidden="" type="radio" name="type" id="type-360" value="https://www.so.com/s?q=" data-placeholder="360好搜">
                                        <label for="type-360"><span class="text-muted">360</span></label>
                                    </li>
                                    <li><input hidden="" type="radio" name="type" id="type-sogo" value="https://www.sogou.com/web?query=" data-placeholder="搜狗搜索">
                                        <label for="type-sogo"><span class="text-muted">搜狗</span></label>
                                    </li>
                                    <li><input hidden="" type="radio" name="type" id="type-bing1" value="https://cn.bing.com/search?q=" data-placeholder="微软Bing搜索">
                                        <label for="type-bing1"><span class="text-muted">必应</span></label>
                                    </li>
                                    <li><input hidden="" type="radio" name="type" id="type-sm" value="https://yz.m.sm.cn/s?q=" data-placeholder="UC移动端搜索">
                                        <label for="type-sm"><span class="text-muted">神马</span></label>
                                    </li>
                                </ul>
                            </div>
                            <div class="search-group group-c ">
                                <ul class="search-type">
                                    <li>
                                        <input hidden="" type="radio" name="type" id="type-br" value="https://rank.chinaz.com/all/" data-placeholder="请输入网址(不带https://)">
                                        <label for="type-br"><span class="text-muted">权重查询</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type" id="type-links" value="https://link.chinaz.com/" data-placeholder="请输入网址(不带https://)">
                                        <label for="type-links"><span class="text-muted">友链检测</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type" id="type-icp" value="https://icp.aizhan.com/" data-placeholder="请输入网址(不带https://)">
                                        <label for="type-icp"><span class="text-muted">备案查询</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type" id="type-ping" value="https://ping.chinaz.com/" data-placeholder="请输入网址(不带https://)">
                                        <label for="type-ping"><span class="text-muted">PING检测</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type" id="type-404" value="https://tool.chinaz.com/Links/?DAddress=" data-placeholder="请输入网址(不带https://)">
                                        <label for="type-404"><span class="text-muted">死链检测</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type" id="type-ciku" value="https://www.ciku5.com/s?wd=" data-placeholder="请输入关键词">
                                        <label for="type-ciku"><span class="text-muted">关键词挖掘</span></label>
                                    </li>
                                </ul>
                            </div>
                            <div class="search-group group-d ">
                                <ul class="search-type">
                                    <li><input hidden="" type="radio" name="type" id="type-zhihu" value="https://www.zhihu.com/search?type=content&q=" data-placeholder="知乎">
                                        <label for="type-zhihu"><span class="text-muted">知乎</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type" id="type-wechat" value="https://weixin.sogou.com/weixin?type=2&query=" data-placeholder="微信">
                                        <label for="type-wechat"><span class="text-muted">微信</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type" id="type-weibo" value="https://s.weibo.com/weibo/" data-placeholder="微博">
                                        <label for="type-weibo"><span class="text-muted">微博</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type" id="type-douban" value="https://www.douban.com/search?q=" data-placeholder="豆瓣">
                                        <label for="type-douban"><span class="text-muted">豆瓣</span></label>
                                    </li>
                                    <li><input hidden="" type="radio" name="type" id="type-why" value="https://ask.seowhy.com/search/?q=" data-placeholder="SEO问答社区">
                                        <label for="type-why"><span class="text-muted">搜外问答</span></label>
                                    </li>
                                </ul>
                            </div>
                            <div class="search-group group-e ">
                                <ul class="search-type">
                                    <li>
                                        <input hidden="" type="radio" name="type" id="type-taobao1" value="https://s.taobao.com/search?q=" data-placeholder="淘宝">
                                        <label for="type-taobao1"><span class="text-muted">淘宝</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type" id="type-jd" value="https://search.jd.com/Search?keyword=" data-placeholder="京东">
                                        <label for="type-jd"><span class="text-muted">京东</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type" id="type-xiachufang" value="https://www.xiachufang.com/search/?keyword=" data-placeholder="下厨房">
                                        <label for="type-xiachufang"><span class="text-muted">下厨房</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type" id="type-xiangha" value="https://www.xiangha.com/so/?q=caipu&s=" data-placeholder="香哈菜谱">
                                        <label for="type-xiangha"><span class="text-muted">香哈菜谱</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type" id="type-12306" value="https://www.12306.cn/?" data-placeholder="12306">
                                        <label for="type-12306"><span class="text-muted">12306</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type" id="type-kd100" value="https://www.kuaidi100.com/?" data-placeholder="快递100">
                                        <label for="type-kd100"><span class="text-muted">快递100</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type" id="type-qunar" value="https://www.qunar.com/?" data-placeholder="去哪儿">
                                        <label for="type-qunar"><span class="text-muted">去哪儿</span></label>
                                    </li>
                                </ul>
                            </div>
                            <div class="search-group group-f ">
                                <ul class="search-type">
                                    <li>
                                        <input hidden="" type="radio" name="type" id="type-zhaopin" value="https://sou.zhaopin.com/jobs/searchresult.ashx?kw=" data-placeholder="智联招聘">
                                        <label for="type-zhaopin"><span class="text-muted">智联招聘</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type" id="type-51job" value="https://search.51job.com/?" data-placeholder="前程无忧">
                                        <label for="type-51job"><span class="text-muted">前程无忧</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type" id="type-lagou" value="https://www.lagou.com/jobs/list_" data-placeholder="拉钩网">
                                        <label for="type-lagou"><span class="text-muted">拉钩网</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type" id="type-liepin" value="https://www.liepin.com/zhaopin/?key=" data-placeholder="猎聘网">
                                        <label for="type-liepin"><span class="text-muted">猎聘网</span></label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card search-smart-tips" style="display: none">
                            <ul></ul>
                        </div>
                    </div>
                </div>
            </div>
            <div id="content" class="content-site customize-site" style="padding-left: 10px;">
                <?php
                    foreach ( $categorys as $category ) {
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
                <div id="content" class="content-site customize-site" style="padding-left: 10px;">
                    <div id="category-<?php echo $category['id']; ?>" class="d-flex flex-fill ">
                        <h4 class="text-gray" id = "category-<?php echo $fid ?>"><?php echo geticon($category['Icon']).'&nbsp;'.$category['name'].'&nbsp;'.$property;?></h4>
                    </div>
                </div>
                <div class="row ">
              <?php
                foreach ($links as $link) {
                    //遍历链接
                    $link['description'] = empty($link['description']) ? '作者很懒，没有填写描述。' : $link['description'];
                    //判断是否是私有
                    if( $link['property'] == 1 ) {
                        $privacy_class = 'property';
                    }
                    else {
                        $privacy_class = '';
                    }
                ?>
                      <div class="url-card col-6  col-sm-6 col-md-4 col-xl-5a col-xxl-6a">
                        <div class="url-body default">
                            <a href="./index.php?c=click&id=<?php echo $link['id']; ?>&u=<?php echo $u?>" target="_blank" data-id="689" data-url="./index.php?c=click&id=<?php echo $link['id']; ?>&u=<?php echo $u?>"
                                class="card no-c  mb-4 site-689" data-toggle="tooltip" data-placement="bottom" title="<?php echo $link['title']; ?>">
                                <div class="card-body">
                                    <div class="url-content d-flex align-items-center">
                                        <div class="url-img rounded-circle mr-2 d-flex align-items-center justify-content-center">
                                            <img class="lazy" src="<?php if (getconfig('LoadIcon')  == 'on'  ){echo geticourl($IconAPI,$link['url']);}else{echo $libs.'/Other/default.ico';} ?>" data-src="<?php if (getconfig('LoadIcon')  == 'on'  ){echo geticourl($IconAPI,$link['url']);}else{echo $libs.'/Other/default.ico';} ?>" onerror="javascript:this.src='<?php if (getconfig('LoadIcon')  == 'on'  ){echo geticourl($IconAPI,$link['url']);}else{echo $libs.'/Other/default.ico';} ?>'" alt="<?php echo $link['title']; ?>">
                                        </div>
                                        <div class="url-info flex-fill">
                                            <div class="text-sm overflowClip_1">
                                                <strong><?php echo $link['title']; ?></strong>
                                            </div>
                                            <p class="overflowClip_1 m-0 text-muted text-xs"><?php echo $link['description']; ?></p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <a href="<?php echo $link['url']; ?>" class="togo text-center text-muted is-views" data-id="689" data-toggle="tooltip" data-placement="right" title="直达" rel="nofollow">
                                <i class="iconfont icon-goto"></i>
                            </a>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <?php } ?>
            <footer class="main-footer footer-type-1 text-xs">
                <div id="footer-tools" class="d-flex flex-column">
                    <?php if(getconfig("gotop") =='on') {?>
                    <!-- 返回顶部按钮 -->
                    <a href="javascript:" id="go-to-up" class="btn rounded-circle go-up m-1" rel="go-top"><i class="iconfont icon-to-up"></i></a>
	                <!-- 返回顶部END -->
                	<?php } ?>
                    <a href="javascript:" data-toggle="modal" data-target="#search-modal" class="btn rounded-circle m-1" rel="search" one-link-mark="yes">
                        <i class="iconfont icon-search"></i>
                    </a>
                    <a href="javascript:" onclick="window.location.href='javascript:switchNightMode()'" class="btn rounded-circle switch-dark-mode m-1" id="yejian" data-toggle="tooltip" data-placement="left" title="夜间模式">
                        <i class="mode-ico iconfont icon-light"></i>
                    </a>
                </div>
                <div class="footer-inner">
                    <div class="footer-text">
                        Copyright © 2021 网址导航 &nbsp;&nbsp;Powered by <a target="_blank" href="https://github.com/helloxz/onenav" title="简约导航/书签管理器" target="_blank" rel="nofollow">OneNav</a>&nbsp;&nbsp;The theme by<a href="https://github.com/liutongxu/liutongxu.github.io" target="_blank" rel="nofollow">LiuTongxu</a>&nbsp;<a href="https://gitee.com/tznb/OneNav" target="_blank" rel="nofollow">落幕魔改版</a>
                    </div>
                    <?php if($ICP != ''){echo '<a href="https://beian.miit.gov.cn" target="_blank">'.$ICP.'</a>';} ?>
                    <?php $footer=getconfig("footer"); if($footer != ''&& ($Diy==='1' || $userdb['Level']==='999')){echo(htmlspecialchars_decode(base64_decode($footer)));} ?>
                    <?php if($Ofooter != ''){echo $Ofooter;} //公用底部?>
                </div>
            </footer>
            </div>
        </div><!-- main-content end -->
    </div><!-- page-container end -->
    
<!--右下角搜索图标-->
    <div class="modal fade search-modal" id="search-modal">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div id="search" class="s-search mx-auto my-4">
                        <div id="search-list" class="hide-type-list">
                            <div class="s-type">
                                <span></span>
                                <div class="s-type-list">
                                    <label for="m_type-baidu" data-id="group-a">常用</label>
                                    <label for="m_type-baidu1" data-id="group-b">搜索</label>
                                    <label for="m_type-br" data-id="group-c">工具</label>
                                    <label for="m_type-zhihu" data-id="group-d">社区</label>
                                    <label for="m_type-taobao1" data-id="group-e">生活</label>
                                    <label for="m_type-zhaopin" data-id="group-f">求职</label>
                                </div>
                            </div>
                            <div class="search-group group-a"><span class="type-text text-muted">常用</span>
                                <ul class="search-type">
                                    <li>
                                        <input checked="checked" hidden="" type="radio" name="type2" id="m_type-baidu" value="https://www.baidu.com/s?wd=" data-placeholder="百度一下">
                                        <label for="m_type-baidu"><span class="text-muted">百度</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type2" id="m_type-google" value="https://www.google.com/search?q=" data-placeholder="谷歌两下">
                                        <label for="m_type-google"><span class="text-muted">Google</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type2" id="m_type-zhannei" value="https://www.iplaysoft.com/?s=" data-placeholder="软件搜索">
                                        <label for="m_type-zhannei"><span class="text-muted">软件</span></label>
                                    </li>
                                    <li><input hidden="" type="radio" name="type2" id="m_type-taobao" value="https://s.taobao.com/search?q=" data-placeholder="淘宝">
                                        <label for="m_type-taobao"><span class="text-muted">淘宝</span></label>
                                    </li>
                                    <li><input hidden="" type="radio" name="type2" id="m_type-bing" value="https://cn.bing.com/search?q=" data-placeholder="微软Bing搜索">
                                        <label for="m_type-bing"><span class="text-muted">Bing</span></label>
                                    </li>
                                </ul>
                            </div>
                            <div class="search-group group-b"><span class="type-text text-muted">搜索</span>
                                <ul class="search-type">
                                    <li>
                                        <input hidden="" type="radio" name="type2" id="m_type-baidu1" value="https://www.baidu.com/s?wd=" data-placeholder="百度一下">
                                        <label for="m_type-baidu1"><span class="text-muted">百度</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type2" id="m_type-google1" value="https://www.google.com/search?q=" data-placeholder="谷歌两下">
                                        <label for="m_type-google1"><span class="text-muted">Google</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type2" id="m_type-360" value="https://www.so.com/s?q=" data-placeholder="360好搜">
                                        <label for="m_type-360"><span class="text-muted">360</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type2" id="m_type-sogo" value="https://www.sogou.com/web?query=" data-placeholder="搜狗搜索">
                                        <label for="m_type-sogo"><span class="text-muted">搜狗</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type2" id="m_type-bing1" value="https://cn.bing.com/search?q=" data-placeholder="微软Bing搜索">
                                        <label for="m_type-bing1"><span class="text-muted">Bing</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type2" id="m_type-sm" value="https://yz.m.sm.cn/s?q=" data-placeholder="UC移动端搜索">
                                        <label for="m_type-sm"><span class="text-muted">神马</span></label>
                                    </li>
                                </ul>
                            </div>
                            <div class="search-group group-c"><span class="type-text text-muted">工具</span>
                                <ul class="search-type">
                                    <li>
                                        <input hidden="" type="radio" name="type2" id="m_type-br" value="https://rank.chinaz.com/all/" data-placeholder="请输入网址(不带https://)">
                                        <label for="m_type-br"><span class="text-muted">权重查询</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type2" id="m_type-links" value="https://link.chinaz.com/" data-placeholder="请输入网址(不带https://)">
                                        <label for="m_type-links"><span class="text-muted">友链检测</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type2" id="m_type-icp" value="https://icp.aizhan.com/" data-placeholder="请输入网址(不带https://)">
                                        <label for="m_type-icp"><span class="text-muted">备案查询</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type2" id="m_type-ping" value="https://ping.chinaz.com/" data-placeholder="请输入网址(不带https://)">
                                        <label for="m_type-ping"><span class="text-muted">PING检测</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type2" id="m_type-404" value="https://tool.chinaz.com/Links/?DAddress=" data-placeholder="请输入网址(不带https://)">
                                        <label for="m_type-404"><span class="text-muted">死链检测</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type2" id="m_type-ciku" value="https://www.ciku5.com/s?wd=" data-placeholder="请输入关键词">
                                        <label for="m_type-ciku"><span class="text-muted">关键词挖掘</span></label>
                                    </li>
                                </ul>
                            </div>
                            <div class="search-group group-d"><span class="type-text text-muted">社区</span>
                                <ul class="search-type">
                                    <li>
                                        <input hidden="" type="radio" name="type2" id="m_type-zhihu" value="https://www.zhihu.com/search?type=content&q=" data-placeholder="知乎">
                                        <label for="m_type-zhihu"><span class="text-muted">知乎</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type2" id="m_type-wechat" value="https://weixin.sogou.com/weixin?type=2&query=" data-placeholder="微信">
                                        <label for="m_type-wechat"><span class="text-muted">微信</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type2" id="m_type-weibo" value="https://s.weibo.com/weibo/" data-placeholder="微博">
                                        <label for="m_type-weibo"><span class="text-muted">微博</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type2" id="m_type-douban" value="https://www.douban.com/search?q=" data-placeholder="豆瓣">
                                        <label for="m_type-douban"><span class="text-muted">豆瓣</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type2" id="m_type-why" value="https://ask.seowhy.com/search/?q=" data-placeholder="SEO问答社区">
                                        <label for="m_type-why"><span class="text-muted">搜外问答</span></label>
                                    </li>
                                </ul>
                            </div>
                            <div class="search-group group-e"><span class="type-text text-muted">生活</span>
                                <ul class="search-type">
                                    <li>
                                        <input hidden="" type="radio" name="type2" id="m_type-taobao1" value="https://s.taobao.com/search?q=" data-placeholder="淘宝">
                                        <label for="m_type-taobao1"><span class="text-muted">淘宝</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type2" id="m_type-jd" value="https://search.jd.com/Search?keyword=" data-placeholder="京东">
                                        <label for="m_type-jd"><span class="text-muted">京东</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type2" id="m_type-xiachufang" value="https://www.xiachufang.com/search/?keyword=" data-placeholder="下厨房">
                                        <label for="m_type-xiachufang"><span class="text-muted">下厨房</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type2" id="m_type-xiangha" value="https://www.xiangha.com/so/?q=caipu&s=" data-placeholder="香哈菜谱">
                                        <label for="m_type-xiangha"><span class="text-muted">香哈菜谱</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type2" id="m_type-12306" value="https://www.12306.cn/?" data-placeholder="12306">
                                        <label for="m_type-12306"><span class="text-muted">12306</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type2" id="m_type-kd100" value="https://www.kuaidi100.com/?" data-placeholder="快递100">
                                        <label for="m_type-kd100"><span class="text-muted">快递100</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type2" id="m_type-qunar" value="https://www.qunar.com/?" data-placeholder="去哪儿">
                                        <label for="m_type-qunar"><span class="text-muted">去哪儿</span></label>
                                    </li>
                                </ul>
                            </div>
                            <div class="search-group group-f"><span class="type-text text-muted">求职</span>
                                <ul class="search-type">
                                    <li>
                                        <input hidden="" type="radio" name="type2" id="m_type-zhaopin" value="https://sou.zhaopin.com/jobs/searchresult.ashx?kw=" data-placeholder="智联招聘">
                                        <label for="m_type-zhaopin"><span class="text-muted">智联招聘</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type2" id="m_type-51job" value="https://search.51job.com/?" data-placeholder="前程无忧">
                                        <label for="m_type-51job"><span class="text-muted">前程无忧</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type2" id="m_type-lagou" value="https://www.lagou.com/jobs/list_" data-placeholder="拉钩网">
                                        <label for="m_type-lagou"><span class="text-muted">拉钩网</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type2" id="m_type-liepin" value="https://www.liepin.com/zhaopin/?key=" data-placeholder="猎聘网">
                                        <label for="m_type-liepin"><span class="text-muted">猎聘网</span></label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <form action="https://nav.iowen.cn?s=" method="get" target="_blank" class="super-search-fm">
                            <input type="text" id="m_search-text" class="form-control smart-tips search-key" zhannei="" autocomplete="off" placeholder="输入关键字搜索" style="outline:0">
                            <button type="submit"><i class="iconfont icon-search"></i></button>
                        </form>
                        <div class="card search-smart-tips" style="display: none">
                            <ul></ul>
                        </div>
                    </div>
                </div>
                <div style="position: absolute;bottom: -40px;width: 100%;text-align: center;">
                    <a href="javascript:" data-dismiss="modal">
                        <i class="iconfont icon-close-circle icon-2x" style="color: #fff;"></i>
                    </a>
                </div>
            </div>
        </div>
    </div><!--右下角搜索图标End-->
<script type='text/javascript' src='<?php echo $Theme?>/ajax/libs/jqueryui/1.12.1/jquery-ui.min-3.03029.1.js' id='jquery-ui-js'></script>
<script type='text/javascript' src='<?php echo $Theme?>/jqueryui-touch-punch/0.2.2/jquery.ui.touch-punch.min-3.0302.js' id='jqueryui-touch-js'></script>
<script type='text/javascript' src='<?php echo $Theme?>/wp-includes/js/clipboard.min-5.6.2.js' id='clipboard-js'></script>
<script type='text/javascript' id='popper-js-extra'>
/* <![CDATA[ */
var theme = {"ajaxurl":"https:\/\/nav.iowen.cn\/wp-admin\/admin-ajax.php","addico":"https:\/\/nav.iowen.cn\/wp-content\/themes\/onenav\/images\/add.png","order":"asc","formpostion":"top","defaultclass":"io-grey-mode","isCustomize":"1","icourl":"https:\/\/api.iowen.cn\/favicon\/","icopng":".png","urlformat":"1","customizemax":"10","newWindow":"0","lazyload":"1","minNav":"1","loading":"1","hotWords":"baidu","classColumns":" col-sm-6 col-md-4 col-xl-5a col-xxl-6a ","apikey":"TWpBeU1UVTNOekk1TWpVMEIvZ1M2bFVIQllUMmxsV1dZelkxQTVPVzB3UW04eldGQmxhM3BNWW14bVNtWk4="};
/* ]]> */
</script>
<script type='text/javascript' src='<?php echo $Theme?>/wp-content/themes/onenav/js/popper.min-3.03029.1.js' id='popper-js'></script>
<script type='text/javascript' src='<?php echo $Theme?>/wp-content/themes/onenav/js/bootstrap.min-3.03029.1.js' id='bootstrap-js'></script>
<script type='text/javascript' src='<?php echo $Theme?>/wp-content/themes/onenav/js/theia-sticky-sidebar-3.03029.1.js' id='sidebar-js'></script>
<script type='text/javascript' src='<?php echo $Theme?>/wp-content/themes/onenav/js/lazyload.min-3.03029.1.js' id='lazyload-js'></script>
<script type='text/javascript' src='<?php echo $Theme?>/wp-content/themes/onenav/js/jquery.fancybox.min-3.03029.1.js' id='lightbox-js-js'></script>
<script type='text/javascript' src='<?php echo $Theme?>/wp-content/themes/onenav/js/app-3.03029.1.js' id='appjs-js'></script>
<script type="text/javascript">
    $(document).ready(function(){
        var siteWelcome = $('#loading');
        siteWelcome.addClass('close');
        setTimeout(function() {
                siteWelcome.remove();
        }, 600);
    });
    </script>
<script type="text/javascript">
$(document).ready(function(){
        setTimeout(function () {
            if ($('a.smooth[href="' + window.location.hash + '"]')[0]) {
                $('a.smooth[href="' + window.location.hash + '"]').click();
            }
            else if (window.location.hash != '') {
                $("html, body").animate({
                    scrollTop: $(window.location.hash).offset().top - 90
                }, {
                    duration: 500,
                    easing: "swing"
                });
            }
        }, 300);
    $(document).on('click','a.smooth',function(ev) {
        ev.preventDefault();
        if($('#sidebar').hasClass('show') && !$(this).hasClass('change-href')){
            $('#sidebar').modal('toggle');
        }
        if($(this).attr("href").substr(0, 1) == "#"){
            $("html, body").animate({
                scrollTop: $($(this).attr("href")).offset().top - 90
            }, {
                duration: 500,
                easing: "swing"
            });
        }
        if($(this).hasClass('go-search-btn')){
            $('#search-text').focus();
        }
        if(!$(this).hasClass('change-href')){
            var menu =  $("a"+$(this).attr("href"));
            menu.click();
            toTarget(menu.parent().parent(),true,true);
        }
    });
    $(document).on('click','a.tab-noajax',function(ev) {
        var url = $(this).data('link');
        if(url)
            $(this).parents('.d-flex.flex-fill.flex-tab').children('.btn-move.tab-move').show().attr('href', url);
        else
            $(this).parents('.d-flex.flex-fill.flex-tab').children('.btn-move.tab-move').hide();
    });
});
</script>
<script>
    //夜间模式
    (function(){
        //如果没有Cookie,就根据时间设置一个!如果有就根据记录的模式来
    if(document.cookie.replace(/(?:(?:^|.*;\s*)night\s*\=\s*([^;]*).*$)|^.*$/, "$1") === ''){
        if(new Date().getHours() > 22 || new Date().getHours() < 6){
            document.body.classList.remove('io-grey-mode');
            document.body.classList.add('io-black-mode');
            document.cookie = "night=1;path=/";
            console.log('设为:夜间模式');
        }else{
            document.body.classList.remove('night');
            document.cookie = "night=0;path=/";
            console.log('设为:白天模式');
        }
    }else{
        var night = document.cookie.replace(/(?:(?:^|.*;\s*)night\s*\=\s*([^;]*).*$)|^.*$/, "$1") || '0'; //正则取night值
        if(night == '0'){
            //document.body.classList.remove('night');
            document.body.classList.remove('io-black-mode');
            document.body.classList.add('io-grey-mode');
            console.log('恢复:白天模式');
        }else if(night == '1'){
            document.body.classList.remove('io-grey-mode');
            document.body.classList.add('io-black-mode');
            console.log('恢复:夜间模式');
        }
    }
})();
//夜间模式切换
function switchNightMode(){
    var night = document.cookie.replace(/(?:(?:^|.*;\s*)night\s*\=\s*([^;]*).*$)|^.*$/, "$1") || '0';
    if(night == '0'){
        document.body.classList.remove('io-grey-mode');
        document.body.classList.add('io-black-mode');
        document.cookie = "night=1;path=/"
        console.log('切换:夜间模式');
        $(".switch-dark-mode").attr("data-original-title","日间模式");
        $(".mode-ico").removeClass("icon-night");
        $(".mode-ico").addClass("icon-light");
    }else{
        document.body.classList.remove('io-black-mode');
        document.body.classList.add('io-grey-mode');
        document.cookie = "night=0;path=/"
        console.log('切换:日间模式');
        $(".switch-dark-mode").attr("data-original-title","夜间模式");
        $(".mode-ico").removeClass("icon-light");
        $(".mode-ico").addClass("icon-night");
    }
}
function open_add_link(){
    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
        layer.open({
        type: 2,
        title: '添加链接',
        shadeClose: true, //点击遮罩关闭层
        area : ['100%' , '100%'],
        content: './index.php?c=admin&page=add_link_tpl_m&u='+u
        });
    }else{
        layer.open({
        type: 2,
        title: '添加链接',
        maxmin: true,
        shadeClose: true, //点击遮罩关闭层
        area : ['800px' , '520px'],
        content: './index.php?c=admin&page=add_link_tpl&u='+u
        });
    }
}
</script>
</body>
</html>