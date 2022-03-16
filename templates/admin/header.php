<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title>OneNav Extend - <?php echo $u; ?></title>
  <link rel="stylesheet" href="<?php echo $libs?>/Layui/v2.6.8/css/layui.css">
  <link rel='stylesheet' href='./templates/admin/static/style.css?v=<?php echo $version.time(); ?>'>
</head>
<body>
<div class="layui-layout layui-layout-admin">
  <div class="layui-header">
    <div class="layui-logo layui-hide-xs"><a href="./index.php?c=admin&u=<?php echo $u;?>"  title = "原落幕魔改版改名啦,这是我的新名字" style="color:#009688;"><h3>OneNav Extend</h3></a></div>
    <!-- 头部区域（可配合layui 已有的水平导航） -->
    <ul class="layui-nav layui-layout-left">
      <li class="layui-nav-item layui-hide-xs"><a href="./?u=<?php echo $u; ?>"><i class="layui-icon layui-icon-home"></i> 首页</a></li>
      <li class="layui-nav-item layui-hide-xs"><a href="./index.php?c=admin&page=category_list&u=<?php echo $u; ?>"><i class="layui-icon layui-icon-list"></i> 分类</a></li>
      <!--<li class="layui-nav-item layui-hide-xs"><a href="./index.php?c=admin&page=add_category&u=<?php echo $u; ?>"><i class="layui-icon layui-icon-add-circle-fine"></i> 添加分类</a></li>-->
      <li class="layui-nav-item layui-hide-xs"><a href="./index.php?c=admin&page=link_list&u=<?php echo $u; ?>"><i class="layui-icon layui-icon-link"></i> 链接</a></li>
      <!--<li class="layui-nav-item layui-hide-xs"><a href="./index.php?c=admin&page=add_link&u=<?php echo $u; ?>"><i class="layui-icon layui-icon-add-circle-fine"></i> 添加链接</a></li>-->
      <li class="layui-nav-item layui-hide-sm"><a href="./?u=<?php echo $u; ?>"><i class="layui-icon layui-icon-home"></i> 首页</a></li>
      <li class="layui-nav-item layui-hide-sm">
        <a href="javascript:;"><i class="layui-icon layui-icon-set layui-hide-sm" ></i> 菜单</a>
        <dl class="layui-nav-child">
          <?php if($udb->get("user","Level",["User"=>$u]) == 999){
            echo'<dd><a href="./index.php?c=admin&page=root&u='.$u.'"><i class="layui-icon layui-icon-website"></i> 网站管理</a></dd>';
            }?>
          <dd><a href="./index.php?c=admin&page=edit_user&u=<?php echo $u?>"><i class="layui-icon layui-icon-auz"></i> 账号设置</a></dd>
          <dd><a href="./index.php?c=admin&page=edit_homepage&u=<?php echo $u?>"><i class="layui-icon layui-icon-theme"></i> 主页设置</a></dd>
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
        <a href="javascript:;">
          <img src="./templates/admin/static/touxiang.jpg" class="layui-nav-img">
          <?php echo $u; ?>
        </a>
        <dl class="layui-nav-child">
          <dd><a href="./index.php?c=admin&page=logout&u=<?php echo $u?>"><i class="layui-icon layui-icon-return"></i> 退出登录</a></dd>
          <dd><a href="https://gitee.com/tznb/OneNav" target="_blank"><i class="layui-icon layui-icon-star"></i> 查看gitee</a></dd>
          <dd><a href="https://doc.xiaoz.me/books/onenav-extend" target="_blank"><i class="layui-icon layui-icon-note"></i> 帮助文档</a></dd>
        </dl>
      </li>
      <!--<li class="layui-nav-item" lay-header-event="menuRight" lay-unselect>-->
      <!--  <a href="javascript:;">-->
      <!--    <i class="layui-icon layui-icon-more-vertical"></i>-->
      <!--  </a>-->
      <!--</li>-->
    </ul>
  </div>