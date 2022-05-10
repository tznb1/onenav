<?php
    foreach ( $categorys as $category ) {
        $fid = $category['id'];
        $links = get_links($fid);
        //如果分类是私有的
        if( $category['property'] == 1 ) {
            $property = '<i class="fa fa-lock" style = "color:#5FB878"></i>';
        }
        else {
            $property = '';
        }
?>

<ul class="mylist row">
    <li id="group_<?php echo geticon($category['id']) ?>" class="title"><span><?php echo geticon($category['Icon']).'&nbsp;'.$category['name'].'&nbsp;'.$property;?></span></li>
    
                <?php
foreach ($links as $link) {
    //遍历链接
    $link['description'] = empty($link['description']) ? '作者很懒，没有填写描述。' : $link['description'];
    //判断是否是私有
    if( $link['property'] == 1 ) {
        $privacy_class = 'property';
    }
    else {
        $privacy_class = '';
    }
?>
                <li class="col-3 col-sm-3 col-md-3 col-lg-1">
                    <?php 
						if ($site['urlz']  == 'on'  ){
						    ?><a rel="nofollow" href="<?php echo $link['url']; ?>" title="<?php echo $link['description']; ?>" target="_blank"><?php
						}else{
						    ?><a rel="nofollow" href="./index.php?c=click&id=<?php echo $link['id']; ?>&u=<?php echo $u?>" title="<?php echo $link['description']; ?>" target="_blank"><?php
						};
						?>
        <img src="<?php echo geticourl($IconAPI,$link); ?>" alt="<?php echo $link['title']; ?>" />
        <span><?php echo $link['title']; ?></span>
    </a>
</li>
<?php } ?>
    </ul>
<?php } ?>