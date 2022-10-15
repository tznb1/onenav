<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title>OneNav Extend - <?php echo $u; ?></title>
  <link rel="stylesheet" href="<?php echo $libs?>/Layui/v2.6.8/css/layui.css">
  <link rel="stylesheet" href="<?php echo $libs?>/Font-awesome/4.7.0/css/font-awesome.css">
  <link rel='stylesheet' href='./templates/admin/static/style.css?v=<?php echo $version; ?>'>
</head>
<body>
<div class="layui-layout layui-layout-admin">
  <!-- 头部区域 -->
  <div class="layui-header">
    <div class="layui-logo layui-hide-xs" style ="box-shadow: 0 0 0 rgba(0,0,0,0);"><a href="./index.php?c=admin&u=<?php echo $u;?>&cache=no"  title = "原落幕魔改版改名啦,这是我的新名字" style="color:#009688;"><h3>OneNav Extend</h3></a></div>
    <ul class="layui-nav layui-layout-left">
      <li class="layui-nav-item layui-hide-xs"><a href="./?u=<?php echo $u; ?>"><i class="layui-icon layui-icon-home"></i> 首页</a></li>
      <li class="layui-nav-item layui-hide-xs"><a href="./index.php?c=admin&page=category_list&u=<?php echo $u; ?>"><i class="layui-icon layui-icon-list"></i> 分类</a></li>
      <!--<li class="layui-nav-item layui-hide-xs"><a href="./index.php?c=admin&page=add_category&u=<?php echo $u; ?>"><i class="layui-icon layui-icon-add-circle-fine"></i> 添加分类</a></li>-->
      <li class="layui-nav-item layui-hide-xs"><a href="./index.php?c=admin&page=link_list&u=<?php echo $u; ?>"><i class="layui-icon layui-icon-link"></i> 链接</a></li>
      <!--<li class="layui-nav-item layui-hide-xs"><a href="./index.php?c=admin&page=add_link&u=<?php echo $u; ?>"><i class="layui-icon layui-icon-add-circle-fine"></i> 添加链接</a></li>-->
<?php if($udb->get("user","Level",["User"=>$u]) == 999  ){ //&& !is_subscribe(true)
            echo'      <li class="layui-nav-item layui-hide-xs"><a href="./index.php?c=admin&page=root&u='.$u.'#root=3"><i class="layui-icon layui-icon-diamond"></i> 高级功能</a></li>'."\n";
            
            }?>
      <li class="layui-nav-item layui-hide-sm"><a href="./?u=<?php echo $u; ?>"><i class="layui-icon layui-icon-home"></i> 首页</a></li>
      <li class="layui-nav-item layui-hide-sm">
        <a href="javascript:;"><i class="layui-icon layui-icon-set layui-hide-sm" ></i> 菜单</a>
        <dl class="layui-nav-child">
<?php if($udb->get("user","Level",["User"=>$u]) == 999){
            echo'          <dd><a href="./index.php?c=admin&page=root&u='.$u.'"><i class="layui-icon layui-icon-website"></i> 网站管理</a></dd>'."\n";
            }?>
          <dd><a href="./index.php?c=admin&page=edit_user&u=<?php echo $u?>"><i class="layui-icon layui-icon-auz"></i> 账号设置</a></dd>
          <dd><a href="./index.php?c=admin&page=edit_homepage&u=<?php echo $u?>#tab=1"><i class="layui-icon layui-icon-fonts-code"></i> 站点设置</a></dd>
          <dd><a href="./index.php?c=admin&page=Theme&u=<?php echo $u?>"><i class="layui-icon layui-icon-theme"></i> 主题模板</a></dd>
<?php if($udb->get("config","Value",["Name"=>'apply']) == 1 ){
            echo '          <dd><a href="./index.php?c=admin&page=apply/apply-admin&u=<?php echo $u?>"><i class="layui-icon layui-icon-release"></i> 收录管理</a></dd>'."\n";
            }?>
          <dd><a href="./index.php?c=admin&page=tags&u=<?php echo $u; ?>"><i class="layui-icon layui-icon-share"></i> 标签管理</a></dd>
          <dd><a href="./index.php?c=admin&page=link_list&u=<?php echo $u; ?>"><i class="layui-icon layui-icon-link"></i> 我的链接</a></dd>
          <dd><a href="./index.php?c=admin&page=add_link&u=<?php echo $u; ?>"><i class="layui-icon layui-icon-add-circle-fine"></i> 添加链接</a></dd>
          <dd><a href="./index.php?c=admin&page=category_list&u=<?php echo $u; ?>"><i class="layui-icon layui-icon-list"></i> 分类列表</a></dd>
          <dd><a href="./index.php?c=admin&page=add_category&u=<?php echo $u; ?>"><i class="layui-icon layui-icon-add-circle-fine"></i> 添加分类</a></dd>
          <dd><a href="./index.php?c=admin&page=logout&u=<?php echo $u?>"><i class="layui-icon layui-icon-return"></i> 退出登录</a></dd>
        </dl>
      </li>
    </ul>
    <ul class="layui-nav layui-layout-right">
      <li class="layui-nav-item layui-hide layui-show-md-inline-block">
        <a href="javascript:;"><img src="./templates/admin/static/touxiang.jpg" class="layui-nav-img"><?php echo $u; ?></a>
        <dl class="layui-nav-child">
          <dd><a href="./index.php?c=admin&page=logout&u=<?php echo $u?>"><i class="layui-icon layui-icon-return"></i> 退出登录</a></dd>
          <dd><a href="https://gitee.com/tznb/OneNav" target="_blank"><i class="layui-icon layui-icon-star"></i> 查看gitee</a></dd>
          <dd><a href="https://gitee.com/tznb/OneNav/wikis/" target="_blank"><i class="layui-icon layui-icon-note"></i> 帮助文档</a></dd>
        </dl>
      </li>
    </ul>
  </div>
  