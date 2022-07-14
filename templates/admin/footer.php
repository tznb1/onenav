  <!--<div class="layui-footer">-->
  <!-- 底部固定区域 -->
  <!--  底部固定区域-->
  <!--</div>-->
  </div>
<script>
var u = '<?php echo $u?>';
</script>
<script src = "<?php echo $libs?>/jquery/jquery-3.6.0.min.js"></script>
<script src = "<?php echo $libs?>/Layui/v2.6.8/layui.js"></script>
<script src = "./templates/admin/static/embed.js?v=<?php echo $version; ?>"></script>
<script src = "./templates/admin/static/public.js?t=<?php echo $version; ?>"></script>
<?php if($md5){echo("<script src = '".$libs."/jquery/jquery.md5.js'></script>");}//只在账号设置页面加载此js?>
</body>
</html>