function gotop(){
	$("html,body").animate({scrollTop: '0px'}, 600);
}
$(".search").blur(function(data,status){
	var keywords = $(".search").val();
	
	if( keywords == ''){
		$(".cat-title").removeClass("mdui-hidden");
	}
	
});
var h = holmes({
    input: '.search',
    find: '.link-space',
    placeholder: '<h3>未搜索到匹配结果！</h3>',
    mark: false,
	hiddenAttr: true,
	// 找到了就添加visible类，没找到添加mdui-hidden
    class: {
      visible: 'visible',
      hidden: 'mdui-hidden'
    },
    onHidden(el) {
	//   console.log('hidden', el);
    },
    onFound(el) {
	//   console.log('found', el);
	  $(".cat-title").addClass("mdui-hidden");
    },
    onInput(el) {
		$(".cat-title").addClass("mdui-hidden");
    },
    onVisible(el) {
		$(".cat-title").removeClass("mdui-hidden");
    },
    onEmpty(el) {
		$(".cat-title").removeClass("mdui-hidden");
    }
  });
//鼠标移动到链接修改为原始URL


//弹窗
function msg(text){
  // alert('dfd');
  $html = '<div class = "msg">' + text + '</div>';
  $("body").append($html);
  $(".msg").fadeIn();
  $(".msg").fadeOut(3000);
  // $(".msg").remove();
}

function admin_menu() {
  // 加载管理员右键菜单
    //初始化菜单
    $.contextMenu({
      selector: '.link-space',
      callback: function(key, options) {
       link_id = $(this).attr('id');
      link_id = link_id.replace('id_','');
          
   },
      items: {
        "open":{name: "打开",icon:"fa-external-link",callback:function(key,opt){
            var link_id = $(this).attr('id');
            link_id = link_id.replace('id_','');
            var tempwindow=window.open('_blank');
            tempwindow.location='index.php?c=click&id='+link_id+"&u="+u;
          }},
          "edit": {name: "编辑", icon: "edit",callback:function(key,opt){
            var link_id = $(this).attr('id');
            link_id = link_id.replace('id_','');
            var tempwindow=window.open('_blank');
            tempwindow.location='index.php?c=admin&page=edit_link&id='+link_id+"&u="+u;
          }},
          "delete": {name: "删除", icon: "delete",callback:function(){
              var link_id = $(this).attr('id');
              link_id = link_id.replace('id_','');
              mdui.confirm('确认删除？',
              function(){
                  $.post("index.php?c=api&method=del_link"+"&u="+u,{id:link_id},function(data,status){
                    //如果删除成功，则移除元素
                    if(data.code == 0) {
                      $("#id_" + link_id).remove();
                    }
                    else{
                      //删除失败
                      mdui.alert(data.msg);
                    }
                  });
              },
              function(){
                //点击取消按钮，不做操作
                return true;
              },{confirmText:'确定',cancelText:'取消'}
            );
          }},
          "sep1": "---------",
          "qrcode": {name: "二维码", icon:"fa-qrcode",callback:function(data,status){
              var link_title = $(this).attr('link-title');
              var url = $(this).attr('link-url');
              mdui.dialog({
                'title':link_title,
                'cssClass':'show_qrcode',
                'content':'<div id="qr" style="display:none;"></div><div id="qrcode"></div>'
              });
              $('#qr').qrcode({render: "canvas",width: 200,height: 200,text: encodeURI(url)}); //生成二维码
              $('#qrcode').append(convertCanvasToImage(document.getElementsByTagName('canvas')[0])); //转换处理,为了兼容微信长按识别
          }},
          "copy":{name:"复制链接",icon:"copy",callback:function(){
            link_url = $(this).attr('link-url');
            // 复制按钮
            var copy = new clipBoard($(".context-menu-icon-copy"), {
              beforeCopy: function() {
                
              },
              copy: function() {
                return link_url;
                
              },
              afterCopy: function() {
                layer.msg('链接已复制！');
              }
          });
            // 复制按钮END
    
          }}

      }
  });
      // 加载右键菜单END
}


function user_menu() {
  // 加载游客右键菜单
//初始化菜单
$.contextMenu({
  selector: '.link-space',
  callback: function(key, options) {
   link_id = $(this).attr('id');
  link_id = link_id.replace('id_','');
      
},
  items: {
    "open":{name: "打开",icon:"fa-external-link",callback:function(key,opt){
        var link_id = $(this).attr('id');
        link_id = link_id.replace('id_','');
        var tempwindow=window.open('_blank');
        tempwindow.location='index.php?c=click&id='+link_id+"&u="+u;
      }},
      "sep1": "---------",
      "qrcode": {name: "二维码", icon:"fa-qrcode",callback:function(data,status){
          var link_title = $(this).attr('link-title');
          var url = $(this).attr('link-url');
          mdui.dialog({
              'title':link_title,
              'cssClass':'show_qrcode',
              'content':'<div id="qr" style="display:none;"></div><div id="qrcode"></div>'
          });
          $('#qr').qrcode({render: "canvas",width: 200,height: 200,text: encodeURI(url)}); //生成二维码
          $('#qrcode').append(convertCanvasToImage(document.getElementsByTagName('canvas')[0])); //转换处理,为了兼容微信长按识别
      }},
      "copy":{name:"复制链接",icon:"copy",callback:function(){
        link_url = $(this).attr('link-url');
        // 复制按钮
        var copy = new clipBoard($(".context-menu-icon-copy"), {
          beforeCopy: function() {
            
          },
          copy: function() {
            return link_url;
            
          },
          afterCopy: function() {
            layer.msg('链接已复制！');
          }
      });
        // 复制按钮END

      }}

  }
});
    // 加载游客右键菜单END
};

// 主题设置
$("#config").click(function(){
        layer.open({
        type: 2,
        title: '主题配置',
        shadeClose: true, //点击遮罩关闭层
        area : ['550px' , '99%'],
        anim: 5,
        offset: 'rt',
        content: './index.php?c=admin&page=config&u='+u+'&Theme='+t
        });
});

// 添加链接按钮
$("#add").click(function(){
  open_add_link();
});

function open_add_link(){
    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
        layer.open({
        type: 2,
        title: '添加链接',
        shadeClose: true, //点击遮罩关闭层
        area : ['100%' , '100%'],
        content: './index.php?c=admin&page=add_link_tpl_m&u='+u
        });
    }else{
        layer.open({
        type: 2,
        title: '添加链接',
        maxmin: true,
        shadeClose: true, //点击遮罩关闭层
        area : ['800px' , '520px'],
        content: './index.php?c=admin&page=add_link_tpl&u='+u
        });
    }
}
//搜索框失去焦点
function clean_search(){
  $(".search").val('');
  $(".search").blur();
}
//搜索框得到焦点
function on_search(){
  $(".search").focus();
  $(".search").val('');
}

//canvas转Image
function convertCanvasToImage(canvas) {
    var image = new Image();
    image.src = canvas.toDataURL("image/png");
    return image;
}


