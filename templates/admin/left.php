<!-- 左侧导航区域 -->
<div class="layui-side layui-bg-black">
    <div class="layui-side-scroll">
      <ul class="layui-nav layui-nav-tree" >
        <li class="layui-nav-item layui-nav-itemed" >
          <a class="" href="javascript:;"><i class="layui-icon layui-icon-note"></i> 管理中心</a>
          <dl class="layui-nav-child">
            <!--<dd><a href="./index.php?c=admin&page=logout&u=<?php echo $u?>"><i class="layui-icon layui-icon-return"></i> 退出登录</a></dd>-->
            <dd <?php if($page == 'edit_user'){ echo 'class="layui-this"';} ?>><a href="./index.php?c=admin&page=edit_user&u=<?php echo $u?>"><i class="layui-icon layui-icon-auz"></i> 账号设置</a></dd>
            <dd <?php if($page == 'edit_homepage'){ echo 'class="layui-this"';} ?>><a href="./index.php?c=admin&page=edit_homepage&u=<?php echo $u?>#tab=1"><i class="layui-icon layui-icon-fonts-code"></i> 站点设置</a></dd>
            <dd <?php if($page == 'Theme'){ echo 'class="layui-this"';} ?>><a href="./index.php?c=admin&page=Theme&u=<?php echo $u?>"><i class="layui-icon layui-icon-theme"></i> 主题模板</a></dd>
<?php 
if($udb->get("user","Level",["User"=>$u]) == 999){
    $dd = ($page == 'root' ? 'class="layui-this"':' ');
    echo'            <dd '. $dd   .'><a href="./index.php?c=admin&page=root&u='.$u.'"><i class="layui-icon layui-icon-website"></i> 网站管理</a></dd>'."\n";
}
if($udb->get("config","Value",["Name"=>'apply']) == 1 ){
    $dd = ($page == 'apply/apply-admin' ? 'class="layui-this"':' ');
    echo '            <dd '. $dd   .'><a href="./index.php?c=admin&page=apply/apply-admin&u='.$u.'"><i class="layui-icon layui-icon-release"></i> 收录管理</a></dd>'."\n";
}
?>
          </dl>
        </li>
      </ul>
      <ul class="layui-nav layui-nav-tree" >
        <li class="layui-nav-item layui-nav-itemed" >
          <a class="" href="javascript:;"><i class="layui-icon layui-icon-note"></i> 分类管理</a>
          <dl class="layui-nav-child">
            <dd <?php if($page == 'tags'){ echo 'class="layui-this"';} ?>><a href="./index.php?c=admin&page=tags&u=<?php echo $u; ?>"><i class="layui-icon layui-icon-share"></i> 标签管理</a></dd>
            <dd <?php if($page == 'category_list'){ echo 'class="layui-this"';} ?>><a href="./index.php?c=admin&page=category_list&u=<?php echo $u; ?>"><i class="layui-icon layui-icon-list"></i> 分类列表</a></dd>
            <dd <?php if($page == 'add_category'){ echo 'class="layui-this"';} ?>><a href="./index.php?c=admin&page=add_category&u=<?php echo $u; ?>"><i class="layui-icon layui-icon-add-circle-fine"></i> 添加分类</a></dd>
          </dl>
        </li>
      </ul>
      <ul class="layui-nav layui-nav-tree" >
        <li class="layui-nav-item layui-nav-itemed" >
          <a class="" href="javascript:;"><i class="layui-icon layui-icon-note"></i> 链接管理</a>
          <dl class="layui-nav-child">
            <dd <?php if($page == 'link_list'){ echo 'class="layui-this"';} ?>><a href="./index.php?c=admin&page=link_list&u=<?php echo $u; ?>"><i class="layui-icon layui-icon-link"></i> 我的链接</a></dd>
            <dd <?php if($page == 'add_link'){ echo 'class="layui-this"';} ?>><a href="./index.php?c=admin&page=add_link&u=<?php echo $u; ?>"><i class="layui-icon layui-icon-add-circle-fine"></i> 添加链接</a></dd>
            <dd <?php if($page == 'imp_link'){ echo 'class="layui-this"';} ?>><a href="./index.php?c=admin&page=imp_link&u=<?php echo $u; ?>"><i class="layui-icon layui-icon-upload-drag"></i> 书签管理</a></dd>
            <dd <?php if($page == 'add_quick_sm'){ echo 'class="layui-this"';} ?>><a href="./index.php?c=admin&page=add_quick_sm&u=<?php echo $u; ?>"><i class="layui-icon layui-icon-rate-solid"></i> 一键添加</a></dd>
          </dl>
        </li>
      </ul>
    </div>
  </div>
  