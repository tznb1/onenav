<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title>OneNav后台管理</title>
  <link rel='stylesheet' href='<?php echo $libs?>/Layui/v2.5.4/css/layui.css'>
  <link rel='stylesheet' href='./templates/admin/static/style.css?v=<?php echo $version; ?>'>
</head>
<body class="layui-layout-body">
<div class="layui-layout layui-layout-admin">
  <div class="layui-header">
    <div class="layui-logo"><a href="./index.php?c=admin&u=<?php echo $u;?>" style="color:#009688;"><h2>OneNav后台管理</h2></a></div>
    <!-- 头部区域（可配合layui已有的水平导航） -->
    <ul class="layui-nav layui-layout-left">
      <li class="layui-nav-item"><a href="./?u=<?php global $u; echo $u; ?>"><i class="layui-icon layui-icon-home"></i> 前台首页</a></li>
      <li class="layui-nav-item"><a href="./index.php?c=admin&page=category_list&u=<?php global $u; echo $u; ?>"><i class="layui-icon layui-icon-list"></i> 分类列表</a></li>
      <li class="layui-nav-item"><a href="./index.php?c=admin&page=add_category&u=<?php global $u; echo $u; ?>"><i class="layui-icon layui-icon-add-circle-fine"></i> 添加分类</a></li>
      <li class="layui-nav-item"><a href="./index.php?c=admin&page=link_list&u=<?php global $u; echo $u; ?>"><i class="layui-icon layui-icon-link"></i> 我的链接</a></li>
      <li class="layui-nav-item"><a href="./index.php?c=admin&page=add_link&u=<?php global $u; echo $u; ?>"><i class="layui-icon layui-icon-add-circle-fine"></i> 添加链接</a></li>
    </ul>
  </div>