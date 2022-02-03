</div>
<script src = '<?php echo $libs?>/jquery/jquery-3.6.0.min.js'></script>
<script src = '<?php echo $libs?>/Layui/v2.5.4/layui.js'></script>
<script src = "./templates/admin/static/embed.js?v=<?php echo $version; ?>"></script>
<?php if($md5){echo("<script src = '".$libs."/jquery/jquery.md5.js'></script>");}//只在账号设置页面加载此js?>
</body>
</html>