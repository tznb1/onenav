/*!
 * Theme Name:One Nav
 * Theme URI:https://www.iotheme.cn/
 * Author:iowen
 * Author URI:https://www.iowen.cn/
 */
(function($){ 
    $(document).ready(function(){
        // 侧栏菜单初始状态设置
        if(theme.minNav != '1')trigger_resizable(true);
        // 搜索模块
        intoSearch();
        // 粘性页脚
        stickFooter();
        // 网址块提示 
        if(isPC()){ $('[data-toggle="tooltip"]').tooltip({trigger: 'hover'}); }else{ $('.qr-img[data-toggle="tooltip"]').tooltip({trigger: 'hover'}); }
        // 初始化tab滑块
        intoSlider();
        // 初始化theiaStickySidebar
        $('.sidebar').theiaStickySidebar({
            additionalMarginTop: 90,
            additionalMarginBottom: 20
        });
    });
    $(".panel-body.single img").each(function(i) {
        if (!this.parentNode.href) {
            if(theme.lazyload)
                $(this).wrap("<a href='" + $(this).data('src') + "' data-fancybox='fancybox' data-caption='" + this.alt + "'></a>")
            else
                $(this).wrap("<a href='" + this.src + "' data-fancybox='fancybox' data-caption='" + this.alt + "'></a>")
        }
    })
    // Enable/Disable Resizable Event
    var wid = 0;
    $(window).resize(function() {
        clearTimeout(wid);
        wid = setTimeout(go_resize, 200); 
    });
    function go_resize() {
        stickFooter(); 
        //if(theme.minNav != '1'){
            trigger_resizable(false);
        //}
    }
    $(document).on('click', "a[target!='_blank']", function() {
        if( theme.loading=='1' && $(this).attr('href') && $(this).attr('href').indexOf("#") != 0 && $(this).attr('href').indexOf("java") != 0 && !$(this).data('fancybox')  && !$(this).data('commentid') && !$(this).hasClass('nofx') ){
            var load = $('<div id="load-loading"></div>');
            $("body").prepend(load);
            load.animate({opacity:'1'},200,'swing').delay(2000).hide(300,function(){ load.remove() });
        }
    });
    
    //返回顶部
    $(window).scroll(function () {
        if ($(this).scrollTop() >= 50) {
            $('#go-to-up').fadeIn(200);
            $('.big-header-banner').addClass('header-bg');
        } else {
            $('#go-to-up').fadeOut(200);
            $('.big-header-banner').removeClass('header-bg');
        }
    });
    $('.go-up').click(function () {
        $('body,html').animate({
            scrollTop: 0
        }, 500);
    return false;
    }); 

 
    //滑块菜单
    $('.slider_menu').children("ul").children("li").not(".anchor").hover(function() {
        $(this).addClass("hover"),
        //$('li.anchor').css({
        //    transform: "scale(1.05)",
        //}),
        toTarget($(this).parent(),true,true) 
    }, function() {
        //$('li.anchor').css({
        //    transform: "scale(1)",
        //}),
        $(this).removeClass("hover") 
    });
    $('.slider_menu').mouseleave(function(e) {
        var menu = $(this).children("ul");
        window.setTimeout(function() { 
            toTarget(menu,true,true) 
        }, 50)
    }) ;  
    function intoSlider() {
        $(".slider_menu[sliderTab]").each(function() {
            if(!$(this).hasClass('into')){
                var menu = $(this).children("ul");
                menu.prepend('<li class="anchor" style="position:absolute;width:0;height:28px"></li>');
                var target = menu.find('.active').parent();
                if(0 < target.length){
                    menu.children(".anchor").css({
                        left: target.position().left + target.scrollLeft() + "px",
                        width: target.outerWidth() + "px",
                        height: target.height() + "px",
                        opacity: "1"
                    })
                }
                $(this).addClass('into');
            }
        })
    }
    //粘性页脚
    function stickFooter() {
        $('.main-footer').attr('style', '');
        if($('.main-footer').hasClass('text-xs'))
        {
            var win_height                 = jQuery(window).height(),
                footer_height             = $('.main-footer').outerHeight(true),
                main_content_height         = $('.main-footer').position().top + footer_height ;
            if(win_height > main_content_height - parseInt($('.main-footer').css('marginTop'), 10))
            {
                $('.main-footer').css({
                    marginTop: win_height - main_content_height  
                });
            }
        }
    }
 

    $('#sidebar-switch').on('click',function(){
        $('#sidebar').removeClass('mini-sidebar');
        $('.sidebar-nav .change-href').attr('href','javascript:;');

    }); 
 
    // Trigger Resizable Function
    var isMin = false,
        isMobileMin = false;
    function trigger_resizable( isNoAnim ) {
        if( (theme.minNav == '1' && !isMin && 767.98<$(window).width() )||(!isMin && 767.98<$(window).width() && $(window).width()<1024) ){
            //$('#mini-button').removeAttr('checked');
            $('#mini-button').prop('checked', false);
            trigger_lsm_mini(isNoAnim);
            isMin = true;
            if(isMobileMin){
                $('#sidebar').addClass('mini-sidebar');
                $('.sidebar-nav .change-href').each(function(){$(this).attr('href',$(this).data('change'))});
                isMobileMin = false;
            }
        }
        else if( ( theme.minNav != '1')&&((isMin && $(window).width()>=1024) || ( isMobileMin && !isMin && $(window).width()>=1024 ) ) ){
            $('#mini-button').prop('checked', true);
            trigger_lsm_mini(isNoAnim);
            isMin = false;
            if(isMobileMin){
                isMobileMin = false;
            }
        }
        else if($(window).width() < 767.98 && $('#sidebar').hasClass('mini-sidebar')){
            $('#sidebar').removeClass('mini-sidebar');
            $('.sidebar-nav .change-href').attr('href','javascript:;');
            isMobileMin = true;
            isMin = false;
        }
    }
    // sidebar-menu-inner收缩展开
    $('.sidebar-menu-inner a').on('click',function(){//.sidebar-menu-inner a //.has-sub a  

        //console.log('--->>>'+$(this).find('span').text());
        if (!$('.sidebar-nav').hasClass('mini-sidebar')) {//菜单栏没有最小化   
            $(this).parent("li").siblings("li.sidebar-item").children('ul').slideUp(200);
            if ($(this).next().css('display') == "none") { //展开
                //展开未展开
                // $('.sidebar-item').children('ul').slideUp(300);
                $(this).next('ul').slideDown(200);
                $(this).parent('li').addClass('sidebar-show').siblings('li').removeClass('sidebar-show');
            }else{ //收缩
                //收缩已展开
                $(this).next('ul').slideUp(200);
                //$('.sidebar-item.sidebar-show').removeClass('sidebar-show');
                $(this).parent('li').removeClass('sidebar-show');
            }
        }
    });
    //菜单栏最小化
    $('#mini-button').on('click',function(){
        trigger_lsm_mini(false);

    });
    function trigger_lsm_mini(isNoAnim){
        if ($('.header-mini-btn input[type="checkbox"]').prop("checked")) {
            $('.sidebar-nav').removeClass('mini-sidebar');
            $('.sidebar-nav .change-href').attr('href','javascript:;');
            $('.sidebar-menu ul ul').css("display", "none");
            if(isNoAnim){
                $('.sidebar-nav').removeClass('animate-nav');
                $('.sidebar-nav').width(220);
            }
            else{
                $('.sidebar-nav').addClass('animate-nav');
                $('.sidebar-nav').stop().animate({width: 170},200);
            }
        }else{
            $('.sidebar-item.sidebar-show').removeClass('sidebar-show');
            $('.sidebar-menu ul').removeAttr('style');
            $('.sidebar-nav').addClass('mini-sidebar');
            $('.sidebar-nav .change-href').each(function(){$(this).attr('href',$(this).data('change'))});
            if(isNoAnim){
                $('.sidebar-nav').removeClass('animate-nav');
                $('.sidebar-nav').width(60);
            }
            else{
                $('.sidebar-nav').addClass('animate-nav');
                $('.sidebar-nav').stop().animate({width: 60},200);
            }
        }
        //$('.sidebar-nav').css("transition","width .3s");
    }
    //显示2级悬浮菜单
    $(document).on('mouseover','.mini-sidebar .sidebar-menu ul:first>li,.mini-sidebar .flex-bottom ul:first>li',function(){
        var offset = 2;
        if($(this).parents('.flex-bottom').length!=0)
            offset = -3;
        $(".sidebar-popup.second").length == 0 && ($("body").append("<div class='second sidebar-popup sidebar-menu-inner text-sm'><div></div></div>"));
        $(".sidebar-popup.second>div").html($(this).html());
        $(".sidebar-popup.second").show();
        var top = $(this).offset().top - $(window).scrollTop() + offset; 
        var d = $(window).height() - $(".sidebar-popup.second>div").height();
        if(d - top <= 0 ){
            top  = d >= 0 ?  d - 8 : 0;
        }
        $(".sidebar-popup.second").stop().animate({"top":top}, 50);
    });
    //隐藏悬浮菜单面板
    $(document).on('mouseleave','.mini-sidebar .sidebar-menu ul:first, .mini-sidebar .slimScrollBar,.second.sidebar-popup',function(){
        $(".sidebar-popup.second").hide();
    });
    //常驻2级悬浮菜单面板
    $(document).on('mouseover','.mini-sidebar .slimScrollBar,.second.sidebar-popup',function(){
        $(".sidebar-popup.second").show();
    });
 
    $(document).on('click', '.ajax-cm-home .ajax-cm', function(event) {
        event.preventDefault();
        var t = $(this); 
        var id = t.data('id');
        var box = $(t.attr('href')).children('.site-list');
        //console.log(box.children('.url-card').length);
        if( box.children('.url-card').length==0 ){ 
            t.addClass('disabled');
            $.ajax({
                url: theme.ajaxurl,
                type: 'POST', 
                dataType: 'html',
                data : {
                    action: t.data('action'),
                    term_id: id,
                },
                cache: true,
            })
            .done(function(response) { 
                if (response.trim()) { 
                    var url = $(response);
                    box.html(url);
                    if(isPC()) url.find('[data-toggle="tooltip"]').tooltip({ trigger: 'hover' });
                } else { 
                }
                t.removeClass('disabled');
            })
            .fail(function() { 
                t.removeClass('disabled');
            }) 
        }
    });

    
    // 搜索模块 -----------------------
    function intoSearch() {
        if(window.localStorage.getItem("searchlist")){
            $(".hide-type-list input#"+window.localStorage.getItem("searchlist")).prop('checked', true);
            $(".hide-type-list input#m_"+window.localStorage.getItem("searchlist")).prop('checked', true);
        }
        if(window.localStorage.getItem("searchlistmenu")){
            $('.s-type-list.big label').removeClass('active');
            $(".s-type-list [data-id="+window.localStorage.getItem("searchlistmenu")+"]").addClass('active');
        }
        toTarget($(".s-type-list.big"),false,false);
        $('.hide-type-list .s-current').removeClass("s-current");
        $('.hide-type-list input:radio[name="type"]:checked').parents(".search-group").addClass("s-current"); 
        $('.hide-type-list input:radio[name="type2"]:checked').parents(".search-group").addClass("s-current");

        $(".super-search-fm").attr("action",$('.hide-type-list input:radio:checked').val());
        $(".search-key").attr("placeholder",$('.hide-type-list input:radio:checked').data("placeholder")); 
        if(window.localStorage.getItem("searchlist")=='type-zhannei'){
            $(".search-key").attr("zhannei","true"); 
        }
    }
    $(document).on('click', '.s-type-list label', function(event) {
        //event.preventDefault();
        $('.s-type-list.big label').removeClass('active');
        $(this).addClass('active');
        window.localStorage.setItem("searchlistmenu", $(this).data("id"));
        var parent = $(this).parents(".s-search");
        parent.find('.search-group').removeClass("s-current");
        parent.find('#'+$(this).attr("for")).parents(".search-group").addClass("s-current"); 
        toTarget($(this).parents(".s-type-list"),false,false);
    });
    $('.hide-type-list .search-group input').on('click', function() {
        var parent = $(this).parents(".s-search");
        window.localStorage.setItem("searchlist", $(this).attr("id").replace("m_",""));
        parent.children(".super-search-fm").attr("action",$(this).val());
        parent.find(".search-key").attr("placeholder",$(this).data("placeholder"));

        if($(this).attr('id')=="type-zhannei" || $(this).attr('id')=="m_type-zhannei")
            parent.find(".search-key").attr("zhannei","true");
        else
            parent.find(".search-key").attr("zhannei","");

        parent.find(".search-key").select();
        parent.find(".search-key").focus();
    });
    $(document).on("submit", ".super-search-fm", function() {
        var key = encodeURIComponent($(this).find(".search-key").val())
        if(key == "")
            return false;
        else{
            window.open( $(this).attr("action") + key);
            return false;
        }
    });
    function getSmartTipsGoogle(value,parents) {
        $.ajax({
            type: "GET",
            url: "//suggestqueries.google.com/complete/search?client=firefox&callback=iowenHot",
            async: true,
            data: { q: value },
            dataType: "jsonp",
            jsonp: "callback",
            success: function(res) {
                var list = parents.children(".search-smart-tips");
                list.children("ul").text("");
                tipsList = res[1].length;
                if (tipsList) {
                    for (var i = 0; i < tipsList; i++) {
                        list.children("ul").append("<li>" + res[1][i] + "</li>");
                        list.find("li").eq(i).click(function() {
                            var keyword = $(this).html();
                            parents.find(".smart-tips.search-key").val(keyword);
                            parents.children(".super-search-fm").submit();
                            list.slideUp(200);
                        });
                    };
                    list.slideDown(200);
                } else {
                    list.slideUp(200)
                }
            },
            error: function(res) {
                tipsList = 0;
            }
        })
    }
    function getSmartTipsBaidu(value,parents) {
        $.ajax({
            type: "GET",
            url: "//sp0.baidu.com/5a1Fazu8AA54nxGko9WTAnF6hhy/su?cb=iowenHot",
            async: true,
            data: { wd: value },
            dataType: "jsonp",
            jsonp: "cb",
            success: function(res) {
                var list = parents.children(".search-smart-tips");
                list.children("ul").text("");
                tipsList = res.s.length;
                if (tipsList) {
                    for (var i = 0; i < tipsList; i++) {
                        list.children("ul").append("<li>" + res.s[i] + "</li>");
                        list.find("li").eq(i).click(function() {
                            var keyword = $(this).html();
                            parents.find(".smart-tips.search-key").val(keyword);
                            parents.children(".super-search-fm").submit();
                            list.slideUp(200);
                        });
                    };
                    list.slideDown(200);
                } else {
                    list.slideUp(200)
                }
            },
            error: function(res) {
                tipsList = 0;
            }
        })
    }
    var listIndex = -1;
    var parent;
    var tipsList = 0;
    var isZhannei = false;
    $(document).on("blur", ".smart-tips.search-key", function() {
        parent = '';
        $(".search-smart-tips").delay(150).slideUp(200)
    });
    $(document).on("focus", ".smart-tips.search-key", function() {
        isZhannei = $(this).attr('zhannei')!=''?true:false;
        parent = $(this).parents('#search');
        if ($(this).val() && !isZhannei) {
            switch(theme.hotWords) {
                case "baidu": 
                    getSmartTipsBaidu($(this).val(),parent)
                    break;
                case "google": 
                    getSmartTipsGoogle($(this).val(),parent)
                    break;
                default: 
            } 
        }
    });
    $(document).on("keyup", ".smart-tips.search-key", function(e) {
        isZhannei = $(this).attr('zhannei')!=''?true:false;
        parent = $(this).parents('#search');
        if ($(this).val()) {
            if (e.keyCode == 38 || e.keyCode == 40 || isZhannei) {
                return
            }
            switch(theme.hotWords) {
                case "baidu": 
                    getSmartTipsBaidu($(this).val(),parent)
                    break;
                case "google": 
                    getSmartTipsGoogle($(this).val(),parent)
                    break;
                default: 
            } 
            listIndex = -1;
        } else {
            $(".search-smart-tips").slideUp(200)
        }
    });
    $(document).on("keydown", ".smart-tips.search-key", function(e) {
        parent = $(this).parents('#search');
        if (e.keyCode === 40) {
            listIndex === (tipsList - 1) ? listIndex = 0 : listIndex++;
            parent.find(".search-smart-tips ul li").eq(listIndex).addClass("current").siblings().removeClass("current");
            var hotValue = parent.find(".search-smart-tips ul li").eq(listIndex).html();
            parent.find(".smart-tips.search-key").val(hotValue)
        }
        if (e.keyCode === 38) {
            if (e.preventDefault) {
                e.preventDefault()
            }
            if (e.returnValue) {
                e.returnValue = false
            }
            listIndex === 0 || listIndex === -1 ? listIndex = (tipsList - 1) : listIndex--;
            parent.find(".search-smart-tips ul li").eq(listIndex).addClass("current").siblings().removeClass("current");
            var hotValue = parent.find(".search-smart-tips ul li").eq(listIndex).html();
            parent.find(".smart-tips.search-key").val(hotValue)
        }
    });
    $('.nav-login-user.dropdown').hover(function(){
        if(!$(this).hasClass('show'))
            $(this).children('a').click();
    },function(){
        //$(this).removeClass('show');
        //$(this).children('a').attr('aria-expanded',false);
        //$(this).children('.dropdown-menu').removeClass('show');
    });
    $('#add-new-sites-modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); 
        var modal = $(this);
        modal.find('[name="term_id"]').val(  button.data('terms_id') );
        modal.find('[name="url"]').val(  button.data('new_url') );
        modal.find('[name="url_name"]').val('');
        modal.find('[name="url_summary"]').removeClass('is-invalid').val('');
        button.data('new_url','');
        var _url = modal.find('[name="url"]').val();
        if(_url!=''){
            getUrlInfo(_url,modal);
            urlStartValue = _url;
        }
    });
    var urlStartValue = '';
    $('#modal-new-url').on('blur',function(){
        var t = $(this);
        if(t.val()!=''){
            if(isURL(t.val())){
                if(urlStartValue!=t.val()){
                    urlStartValue = t.val();
                    getUrlInfo(t.val(),$('.add_new_sites_modal'));
                }
            }else{
                showAlert(JSON.parse('{"status":4,"msg":"URL 无效！"}'));
            }
        }
    });
    $('#modal-new-url-summary').on('blur',function(){
        var t = $(this);
        if(t.val()!=''){
            t.removeClass('is-invalid');
        }
    });
    function getUrlInfo(_url,modal){
        $('#modal-new-url-ico').show();
		$.post("//apiv2.iotheme.cn/webinfo/get.php", { url: _url ,key: theme.apikey },function(data,status){ 
			if(data.code==0){
                $('#modal-new-url-ico').hide();
				$("#modal-new-url-summary").addClass('is-invalid');
			}
			else{
                $('#modal-new-url-ico').hide();
                if(data.site_title=="" && data.site_description==""){
                    $("#modal-new-url-summary").addClass('is-invalid');
                }else{
                    modal.find('[name="url_name"]').val(data.site_title);   
                    modal.find('[name="url_summary"]').val(data.site_description);
                }
			}
		}).fail(function () {
            $('#modal-new-url-ico').hide();
			$(".refre_msg").html('访问超时，请再试试，或者手动填写').show(200).delay(4000).hide(200);
		});
    }
})(jQuery);
function isURL(URL){
    var str=URL;
    var Expression=/http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?/;
    var objExp=new RegExp(Expression);
    if(objExp.test(str)==true){
        return true;
    }else{
        return false;
    }
}
function isPC() {
    let u = navigator.userAgent;
    let Agents = ["Android", "iPhone", "webOS", "BlackBerry", "SymbianOS", "Windows Phone", "iPad", "iPod"];
    let flag = true;
    for (let i = 0; i < Agents.length; i++) {
        if (u.indexOf(Agents[i]) > 0) {
            flag = false;
            break;
        }
    }
    return flag;
}

function toTarget(menu, padding, isMult) {
    var slider =  menu.children(".anchor");
    var target = menu.children(".hover").first() ;
    if (target && 0 < target.length){
    }
    else{
        if(isMult)
            target = menu.find('.active').parent();
        else
            target = menu.find('.active');
    }
    if(0 < target.length){
        if(padding)
        slider.css({
            left: target.position().left + target.scrollLeft() + "px",
            width: target.outerWidth() + "px",
            opacity: "1"
        });
        else
        slider.css({
            left: target.position().left + target.scrollLeft() + (target.outerWidth()/4) + "px",
            width: target.outerWidth()/2 + "px",
            opacity: "1"
        });
    }
    else{
        slider.css({
            opacity: "0"
        })
    }
}
var ioadindex = 0;
function loadingShow(parent = "body"){
    if($('.load-loading')[0]){
        ioadindex ++;
        return $('.load-loading');
    }
    var load = $('<div class="load-loading" style="display:none"><div class="bg"></div><div class="rounded-lg bg-light" style="z-index:1"><div class="spinner-border m-4" role="status"><span class="sr-only">Loading...</span></div></div></div>');
    $(parent).prepend(load);
    load.fadeIn(200);
    return load;
}
function loadingHid(load){
    if(ioadindex>0)
        ioadindex--;
    else{
        ioadindex = 0;
        load.fadeOut(300,function(){ load.remove() });
    }
}
function ioPopupTips(type, msg, callBack) {
	var ico = '';
    switch(type) {
        case 1: 
            ico='icon-adopt';
            break;
        case 2: 
            ico='icon-tishi';
            break;
        case 3: 
            ico='icon-warning';
            break;
        case 4: 
            ico='icon-close-circle';
            break;
        default: 
    } 
	var c = type==1 ? 'tips-success' : 'tips-error';
	var html = '<section class="io-bomb '+c+' io-bomb-sm io-bomb-open">'+
					'<div class="io-bomb-overlay"></div>'+
                    '<div class="io-bomb-body text-center">'+
                        '<div class="io-bomb-content bg-white px-5"><i class="iconfont '+ico+' icon-8x"></i>'+
                            '<p class="text-md mt-3">'+msg+'</p>'+
                        '</div>'+
                    '</div>'+
                '</section>';
    var tips = $(html);
	$('body').addClass('modal-open').append(tips);
	setTimeout(function(){
        $('body').removeClass('modal-open');
        if ($.isFunction(callBack)) callBack(true); 
		tips.removeClass('io-bomb-open').addClass('io-bomb-close');
		setTimeout(function(){
			tips.removeClass('io-bomb-close');
			setTimeout(function(){
				tips.remove();
			}, 200);
		},400);
	},2000);
}
function ioPopup(type, html, maskStyle, btnCallBack) {
	var maskStyle = maskStyle ? 'style="' + maskStyle + '"' : '';
	var size = '';
	if( type == 'big' ){
		size = 'io-bomb-lg';
	}else if( type == 'no-padding' ){
		size = 'io-bomb-nopd';
	}else if( type == 'cover' ){
		size = 'io-bomb-cover io-bomb-nopd';
	}else if( type == 'full' ){
		size = 'io-bomb-xl';
	}else if( type == 'small' ){
		size = 'io-bomb-sm';
	}else if( type == 'confirm' ){
		size = 'io-bomb-md';
	}
	var template = '\
	<div class="io-bomb ' + size + ' io-bomb-open">\
		<div class="io-bomb-overlay" ' + maskStyle + '></div>\
		<div class="io-bomb-body text-center">\
			<div class="io-bomb-content bg-white">\
				'+html+'\
			</div>\
			<div class="btn-close-bomb mt-2">\
                <i class="iconfont icon-close-circle"></i>\
            </div>\
		</div>\
	</div>\
	';
	var popup = $(template);
	$('body').addClass('modal-open').append(popup);
	var close = function(){
        $('body').removeClass('modal-open');
		$(popup).removeClass('io-bomb-open').addClass('io-bomb-close');
		setTimeout(function(){
			$(popup).removeClass('io-bomb-close');
			setTimeout(function(){
				popup.remove();
			}, 200);
		},600);
	}
	$(popup).on('click touchstart', '.btn-close-bomb i, .io-bomb-overlay', function(event) {
		event.preventDefault();
        if ($.isFunction(btnCallBack)) btnCallBack(true); 
		close();
	}); 
	return popup;
} 
function ioConfirm(message, btnCallBack) {
	var template = '\
	<div class="io-bomb io-bomb-confirm io-bomb-open">\
		<div class="io-bomb-overlay"></div>\
		<div class="io-bomb-body">\
			<div class="io-bomb-content bg-white">\
				'+message+'\
                <div class="text-center mt-3">\
                    <button class="btn btn-danger mx-2" onclick="_onclick(true);">确定</button>\
                    <button class="btn btn-light mx-2" onclick="_onclick(false);">取消</button>\
                </div>\
			</div>\
		</div>\
	</div>\
	';
	var popup = $(template);
	$('body').addClass('modal-open').append(popup);
    _onclick = function (r) { 
        close();
        if ($.isFunction(btnCallBack)) btnCallBack(r); 
    };
	var close = function(){
        $('body').removeClass('modal-open');
		$(popup).removeClass('io-bomb-open').addClass('io-bomb-close');
		setTimeout(function(){
			$(popup).removeClass('io-bomb-close');
			setTimeout(function(){
				popup.remove();
			}, 200);
		},600);
	}
	return popup;
}


