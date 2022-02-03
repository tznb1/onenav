<div class="layui-side layui-bg-black">
    <div class="layui-side-scroll">
      <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
      <ul class="layui-nav layui-nav-tree" >
        <li class="layui-nav-item layui-nav-itemed" >
          <a class="" href="javascript:;">管理中心</a>
          <dl class="layui-nav-child">
            <dd><a href="./index.php?u=<?php echo $u?>">返回前台</a></dd>
            <dd><a href="./index.php?c=admin&page=logout&u=<?php echo $u?>">退出登录</a></dd>
            <dd><a href="./index.php?c=admin&page=edit_user&u=<?php echo $u?>">账号设置</a></dd>
            <dd><a href="./index.php?c=admin&page=edit_homepage&u=<?php echo $u?>">主页设置</a></dd>
            <?php if($udb->get("user","Level",["User"=>$u]) == 999){
            echo'<dd><a href="./index.php?c=admin&page=root&u='.$u.'">网站管理</a></dd>';
            }?>
            
          </dl>
        </li>
      </ul>
      <ul class="layui-nav layui-nav-tree" >
        <li class="layui-nav-item layui-nav-itemed" >
          <a class="" href="javascript:;">分类管理</a>
          <dl class="layui-nav-child">
            <dd><a href="./index.php?c=admin&page=category_list&u=<?php global $u; echo $u; ?>">分类列表</a></dd>
            <dd><a href="./index.php?c=admin&page=add_category&u=<?php global $u; echo $u; ?>">添加分类</a></dd>
          </dl>
        </li>
      </ul>
      <ul class="layui-nav layui-nav-tree" >
        <li class="layui-nav-item layui-nav-itemed" >
          <a class="" href="javascript:;">链接管理</a>
          <dl class="layui-nav-child">
            <dd><a href="./index.php?c=admin&page=link_list&u=<?php global $u; echo $u; ?>">我的链接</a></dd>
            <dd><a href="./index.php?c=admin&page=add_link&u=<?php global $u; echo $u; ?>">添加链接</a></dd>
            <dd><a href="./index.php?c=admin&page=imp_link&u=<?php global $u; echo $u; ?>">书签导入</a></dd>
            <dd><a href="./index.php?c=admin&page=add_quick_sm&u=<?php global $u; echo $u; ?>">一键添加</a></dd>
          </dl>
        </li>
      </ul>
    </div>
  </div>