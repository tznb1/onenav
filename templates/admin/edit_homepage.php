<?php include_once('header.php'); ?>
<?php include_once('left.php'); ?>
<div class="layui-body">
<!-- 内容主体区域 -->
<style type="text/css">
.layui-layout-admin .layui-body {top: 40px;}
</style>
<div class="layui-tab layui-tab-brief" lay-filter="tab" >
  <ul class="layui-tab-title">
    <li class="layui-this" lay-id="1">站点信息</li>
    <li lay-id="2">功能配置</li>
  </ul>
 <div class="layui-tab-content">
  <div class="layui-tab-item layui-show">
   <div class="layui-row content-body">
    <form class="layui-form layui-form-pane" action="">
        <div class="layui-form-item">
            <label class="layui-form-label">主标题</label>
            <div class="layui-input-block">
                <input type="text" name="title" value = "<?php echo getconfig('title');?>" required  lay-verify="required" autocomplete="off" placeholder="请输入网站标题" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">副标题</label>
            <div class="layui-input-block">
            <input type="text" name="subtitle" value = "<?php echo getconfig('subtitle');?>" autocomplete="off" placeholder="请输入网站副标题,可留空" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">Logo</label>
            <div class="layui-input-block">
            <input type="text" name="logo" value = "<?php echo getconfig('logo');?>" autocomplete="off" placeholder="可以是文字和图片URL地址,需主题支持" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">关键词</label>
            <div class="layui-input-block">
            <input type="text" name="keywords" value = "<?php echo getconfig('keywords');?>" autocomplete="off" placeholder="输入网站关键词，用英文状态的逗号分隔" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">描述</label>
            <div class="layui-input-block">
            <textarea name = "description" placeholder="网站描述，一般不超过200字符" rows = "3" class="layui-textarea"><?php echo getconfig('description');?></textarea>
            </div>
        </div>
<?php if($Diy === '1' || $userdb['Level'] == '999'){?>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">头部代码</label>
            <div class="layui-input-block">
                <textarea name = "head" placeholder="当内置主题样式无法满足您的时候,您可以自定义样式!在head间加载!" rows = "6" class="layui-textarea"><?php echo base64_decode( getconfig('head'))?></textarea>
            </div>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">底部代码</label>
            <div class="layui-input-block">
                <textarea name = "footer" placeholder="例如统计代码,又拍云LOGO等,支持HTML,JS,CSS" rows = "6" class="layui-textarea"><?php echo base64_decode( getconfig('footer'))?></textarea>
            </div>
        </div>
<?php } ?>
        <div class="layui-form-item">
            <button class="layui-btn" lay-submit lay-filter="edit_homepage">保存</button>
            <button class="layui-btn layui-btn-primary" type="reset" >重置</button>
        </div>
    </form>
  </div>
 </div>
  <div class="layui-tab-item">
   <div class="layui-row content-body">
    <form class="layui-form layui-form-pane" action="">
        <div class="layui-form-item">
            <input id="urlz-input" type="hidden" value="<?php echo getconfig('urlz','on');?>">
            <label class="layui-form-label">跳转方式</label>
            <div class="layui-input-inline">
                <select lay-verify="required"  id="urlz" name="urlz" lay-search>
                    <option value="on">直连模式</option>
                    <option value="302" selected="">重定向</option>
                    <option value="Privacy">隐私保护</option>
                    <option value="Transition">过度页面</option>
                </select>
            </div>
            <div class="layui-form-mid layui-word-aux">直连模式无法统计点击数且无法使用备用链接,但它响应最快!</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label" >访客停留</label>
            <div class="layui-input-inline">
                <input type="number" min="0" max="86400" lay-verify="required|number" name="visitorST" value = "<?php echo getconfig('visitorST','5');?>" autocomplete="off" placeholder="访客停留时间，单位秒" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">过渡页面,访客停留时间，单位秒</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label" >管理员停留</label>
            <div class="layui-input-inline">
                <input type="number" min="0" max="86400" lay-verify="required|number" name="adminST" value = "<?php echo getconfig('adminST','0');?>" required  lay-verify="required" autocomplete="off" placeholder="管理员停留时间，单位秒" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">过渡页面,管理员停留时间，单位秒</div>
        </div>
        <div class="layui-form-item">
            <input id="gotop-input" type="hidden" value="<?php echo getconfig('gotop','on');?>">
            <label class="layui-form-label">返回顶部</label>
            <div class="layui-input-inline">
                <select lay-verify="required"  id="gotop" name="gotop" lay-search>
                    <option value="off">关闭</option>
                    <option value="on" >开启</option>
                </select>
            </div>
            <div class="layui-form-mid layui-word-aux">需主题支持</div>
        </div>
        <div class="layui-form-item">
            <input id="quickAdd-input" type="hidden" value="<?php echo getconfig('quickAdd','on');?>">
            <label class="layui-form-label">快速添加</label>
            <div class="layui-input-inline">
                <select lay-verify="required"  id="quickAdd" name="quickAdd" lay-search>
                    <option value="off">关闭</option>
                    <option value="on" >开启</option>
                </select>
            </div>
            <div class="layui-form-mid layui-word-aux">需主题支持</div>
        </div>
        <div class="layui-form-item">
            <input id="GoAdmin-input" type="hidden" value="<?php echo getconfig('GoAdmin','on');?>">
            <label class="layui-form-label">登录入口</label>
            <div class="layui-input-inline">
                <select lay-verify="required"  id="GoAdmin" name="GoAdmin" lay-search>
                    <option value="off">隐藏</option>
                    <option value="on" >显示</option>
                </select>
            </div>
            <div class="layui-form-mid layui-word-aux">需主题支持</div>
        </div>
        <div class="layui-form-item">
            <input id="LoadIcon-input" type="hidden" value="<?php echo getconfig('LoadIcon','on');?>">
            <label class="layui-form-label">URL图标</label>
            <div class="layui-input-inline">
                <select lay-verify="required"  id="LoadIcon" name="LoadIcon" lay-search>
                    <option value="off">关闭</option>
                    <option value="on" >开启</option>
                </select>
            </div>
            <div class="layui-form-mid layui-word-aux">需主题支持</div>
        </div>
        <div class="layui-form-item">
            <button class="layui-btn" lay-submit lay-filter="edit_homepage">保存</button>
        </div>
    </form>
   </div>
  </div>
 </div>
</div>
<!-- 内容主题区域END -->
</div>
<?php include_once('footer.php'); ?>