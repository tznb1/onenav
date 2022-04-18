<?php if(basename($_SERVER['PHP_SELF']) == basename(__FILE__)) header("Location:/"); ?>
<script src="<?php echo $Theme?>/js/bootstrap.min.js" type="application/javascript"></script>
<script src="<?php echo $Theme?>/js/script.js"></script>
<script src="<?php echo $Theme?>/js/svg.js"></script>
<div style="display:none;" class="back-to" id="toolBackTop"> 
<a title="返回顶部" onclick="window.scrollTo(0,0);return false;" href="#top" class="back-top"></a> 
</div> 
<div class="mt-5 mb-3 footer text-muted text-center"> 
    <!--备案信息-->
    Copyright © <?php echo date('Y');?> All Rights Reserved <a target="_blank" href="" title="<?php echo $site['title'];?>"><?php echo $site['title'];?></a> &nbsp;&nbsp;<?php if($ICP != ''){echo '<img src="'.$Theme.'/img/icp.png" width="16px" height="16px" /><a href="https://beian.miit.gov.cn" target="_blank">'.$ICP.'</a>';} ?>
    <!--版权信息-->
    <p>Powered by&nbsp;&nbsp;<a target="_blank" href="https://github.com/helloxz/onenav" title="简约导航/书签管理器" target="_blank" rel="nofollow">OneNav</a>&nbsp;&nbsp;<a href="https://gitee.com/tznb/OneNav" target="_blank" rel="nofollow">落幕魔改版</a>&nbsp;&nbsp;The theme by&nbsp;&nbsp;<a href="https://gitee.com/LyLme/lylme_spage" target="_blank" rel="nofollow">lylme_spage</a></p>
    <?php echo $site['custom_footer']; ?>
    <?php echo $Ofooter; ?>
</div>  
</html>