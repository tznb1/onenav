<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<?php echo '<title>'.getconfig('title').'</title>';?>
<?php $keywords=getconfig("keywords"); if($keywords !=''){echo '<meta name="keywords" content="'.$keywords.'"/>'."\n";}?>
<?php $description=getconfig("description"); if($description !=''){echo '<meta name="description" content="'.$description.'"/>'."\n";}?>
<link rel="stylesheet" href="<?php echo $Theme?>/css/zui.min.css">
<link rel="stylesheet" href="<?php echo $Theme?>/css/style.css">
<link rel="stylesheet" href="<?php echo $libs?>/Font-awesome/4.7.0/css/font-awesome.css">
<link rel="stylesheet" href="<?php echo $libs?>/Layui/v2.6.8/css/layui-icon.css">
<?php // $nw=getconfig("navwidth");if($nw!=''){echo'<style type="text/css">.left-bar{width:'.$nw.'px;}</style>';}//导航宽度?>
<?php $head=getconfig("head");if($head!=''&& ($Diy==='1' || $userdb['Level']==='999')){echo(htmlspecialchars_decode(base64_decode($head)));} //自定义头部代码?> 
</head>
<body id="nav_body">
<!-- Header Nav -->
<header>
<div class="main">
    <h1 class="logo">
    <a href="./?u=<?php echo $u?>">
    <!--<img src="<?php echo $Theme?>/img/favicon.ico">-->
    <span><?php echo getconfig('title');?></span>
    </a>
    </h1>
    <!-- Top Nav -->
    <nav class="nav">
    <ul>
<?php if($is_login) {
echo '     <li><a href="./index.php?c=admin&u='.$u.'" >后台管理</a></li>'."\n";
echo '     <li><a href="./index.php?c=admin&page=logout&u='.$u.'">退出登陆</a></li>';}
elseif(getconfig('GoAdmin')  == 'on'  ){
     
        ?><li><a href="./index.php?c=<?php if($login =='login'){echo $login;}else{echo $Elogin;}?>&u=<?php echo $u?>" >登录</a></li>
    <?php }
    ?>
    
    </ul>
    </nav>
    <!-- Mobile -->
    <button class="nav-btn visible-xs visible-sm"><span class="icon-line top"></span><span class="icon-line middle"></span><span class="icon-line bottom"></span>
    </button>
</div>
</header>
<div id="content">
    <!-- Left Bar -->
    <div class="left-bar">
    <!--<div class="header"><h2>我的导航站</h2></div>-->
    <div class="menu" id="menu">
    <ul class="scrollcontent">
    <!-- Left Nav -->
    <?php foreach ($categorys as $category) {
        echo '<li ><a '.geticon2($category['Icon']).'style="padding-left:20px;" href="#category-'.$category['id'].'">&ensp; '.$category['name'].'</a></li>'."\n                ";
        }
    ?>
<!-- Left Nav End -->
            </ul>
        </div>
    </div>
    <!-- Content -->
    <div class="main">
        <div class="container content-box">
            <!-- Search -->
            <section class="sousuo">
            <div class="search">
                <div class="search-box">
                    <button class="search-engine-name" id="search-engine-name">Baidu</button>
                    <input type="text" id="txt" class="search-input" placeholder="Hello World !"/>
                    <button class="search-btn" id="search-btn"><i class="fa fa-search"></i></button>
                </div>
                <!-- Engine  -->
                <div class="search-engine">
                    <div class="search-engine-head">
                        <strong class="search-engine-tit">选择搜索引擎：</strong>
                    </div>
                    <ul class="search-engine-list">
                    </ul>
                </div>
            </div>
            </section>
            <!-- Links -->
        <?php foreach ( $categorys as $category ) {
                $fid = $category['id'];
                $links = get_links($fid);
                //如果分类是私有的带上锁的图标
                $property = $category['property'] == 1 ? '<i class="fa fa-lock" style = "color:#5FB878"></i>':'';
                $name = $category['name'];
                ?>
    <section class="item card-box">
            <div class="container-fluid">
            <div class="row">
            <div class="item-tit" display: block>
            <strong><?php echo geticon($category['Icon'])?><a class="comment-body" name="<?php echo 'category-'.$fid;?>"></a><?php echo $name;?>&ensp;<?php echo $property;?></strong> 
            </div>
            <div class="clearfix two-list-box">
            <!-- 遍历链接 -->
            <?php
                foreach ($links as $link) {
                $linkURL=getconfig('urlz')  == 'on' ? $link['url'] :'./index.php?c=click&id='.$link['id'].'&u='.$u;
                $description = empty($link['description']) ? '作者很懒，没有填写描述。' : $link['description'];
                $img= getconfig('LoadIcon')  == 'on' ? geticourl($IconAPI,$link['url']):$libs.'/Other/default.ico';
                ?><div class="col-md-3 col-sm-4 col-xs-6 ">
                <a href="<?php echo $linkURL?>" class="card-link" target="_blank">
                <img src="<?php echo $img ?>" align="center" width="16px" height="16px">
                <span class="card-tit" ><?php echo $link['title']?></span>
                <div class="card-desc"><?php echo $description?></div>
                </a>
                </div>
                <?php } 
                ?></div></div></div></section>
                <?php } 
            ?><!-- Footer -->
            <footer class="footer">
            <div class="container">
                <div class="rwo">
                    <div class="col-md-12">
                        <p>
                        Copyright © 2019 KRUNK DESIGN
                        <?php if($ICP != ''){echo '<a href="https://beian.miit.gov.cn" target="_blank">'.$ICP.'</a>';} ?>
                        <?php $footer=getconfig("footer"); if($footer != ''&& ($Diy==='1' || $userdb['Level']==='999')){echo(htmlspecialchars_decode(base64_decode($footer)));} ?>
                        <?php if($Ofooter != ''){echo $Ofooter;} //公用底部?>
                        </p>
                    </div>
                </div>
            </div>
            </footer>
        </div>
        <!-- Content -->
    </div>
    <div id="get-top" title="回到顶部">
        up
    </div>
    <!-- jQuery (ZUI Require jQuery) -->
    <script src="<?php echo $libs?>/jquery/jquery-3.6.0.min.js"></script>
    <script src="<?php echo $Theme?>/js/main.js"></script>
</div>
</body>
</html>
