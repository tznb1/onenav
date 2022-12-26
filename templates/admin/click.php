<!DOCTYPE html>
<html lang="zh-cn" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8" />
	<title><?php echo $link['title']; ?> - OneNav</title>
	<meta name="keywords" content="<?php echo $link['title']; ?>" />
	<meta name="description" content="<?php echo $link['description']; ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php if(getconfig('urlz')  == 'Privacy'){echo '<meta name="referrer" content="same-origin">'."\n";}?> 
	<link rel="stylesheet" href="<?php echo $libs?>/Other/bootstrap.min.css" type="" media=""/>
	<style>
		.prevent-overflow{
			width:260px;
			overflow: hidden;/*超出部分隐藏*/
			white-space: nowrap;/*不换行*/
			text-overflow:ellipsis;/*超出部分文字以...显示dsds*/
		}
	</style>
	<?php  
		//不存在多个链接的情况，如果用户已经登录，则1s后跳转，不然等5s
		if( empty($link['url_standby']) ) {
			
			if ($is_login) {
				header("Refresh:".$adminST.";url=".$link['url']);
			}
			else{
				header("Refresh:".$visitorST.";url=".$link['url']);
			}
		}
	?>
</head>
<body>
	<div class="container" style = "margin-top:2em;">
		<div class="row">
			<div class="col-sm-8 offset-sm-2">
				<!-- 新建一个表格 -->
				<h2>链接信息：</h2>
				<table class="table">
					<tbody>
					<tr class="table-info">
						<td>标题</td>
						<td><?php echo $link['title']; ?></td>
					</tr>
					<tr class="table-info">
						<td>描述</td>
						<td><?php echo $link['description']; ?></td>
					</tr>
					<tr class="table-info">
						<td>链接</td>
						<td>
							<div class = "prevent-overflow">
								<a href="<?php echo $link['url']; ?>" rel = "nofollow" title = "<?php echo $link['title']; ?>"><?php echo $link['url']; ?></a>
							</div>
						</td>
					</tr>
					<?php  if( !empty($link['url_standby']) ) {  
					?><tr class="table-info">
						<td>备用链接</td>
						<td>
							<div class = "prevent-overflow">
								<a href="<?php echo $link['url_standby']; ?>" rel = "nofollow" title = "<?php echo $link['title']; ?>"><?php echo $link['url_standby']; ?></a>
							</div>
						</td>
					</tr>
					<?php }?>
					</tbody>
				</table>
				<!-- 如果备用链接是空的，则显示加载中... -->
				<?php if( empty($link['url_standby']) ) { ?>
					<!-- 加载中 -->
					<div class="spinner-border"></div> 
					 即将打开，请稍等...
					<!-- 加载中END -->
				<?php }else{ ?>
				<!-- 备用链接不为空 -->
				<!-- 备用链接提示框 -->
				<div class="alert alert-primary">
					<strong>存在备用链接，请手动点击您要打开的链接！</strong>
				</div>
				<!-- 提示框END -->
				<?php } ?>
				<!-- 表格END -->
				<div class="xcdn-content">
					<?php echo $msg; ?>
				</div>
				<hr>
				<div class="xcdn-footer"><?php if($ICP != ''){echo '<a href="https://beian.miit.gov.cn" target="_blank">'.$ICP.'</a>';} ?>&copy;2022 Powered by <a href="https://gitee.com/tznb/OneNav"  rel = "nofollow" target = "_blank">落幕</a>
				<?php $footer=getconfig("footer"); if($footer != ''&& ($Diy==='1' || $userdb['Level']=='999')){echo(htmlspecialchars_decode(base64_decode($footer)));} ?>
				<?php if($Ofooter != ''){echo $Ofooter;} //公用底部?></div>
			</div>
		</div>
	</div>
</body>
</html>
