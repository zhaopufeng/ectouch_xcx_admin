<?php exit;?>a:3:{s:8:"template";a:5:{i:0;s:59:"E:/phpStudy/WWW/ectouch/themes/default/category_top_all.dwt";i:1;s:66:"E:/phpStudy/WWW/ectouch/themes/default/library/new_page_header.lbi";i:2;s:67:"E:/phpStudy/WWW/ectouch/themes/default/library/new_search_small.lbi";i:3;s:61:"E:/phpStudy/WWW/ectouch/themes/default/library/new_search.lbi";i:4;s:66:"E:/phpStudy/WWW/ectouch/themes/default/library/new_page_footer.lbi";}s:7:"expires";i:1515032503;s:8:"maketime";i:1515028903;}<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta name="format-detection" content="telephone=no" />
<meta charset="utf-8">
<title>所有分类</title>
<link rel="stylesheet" href="/ectouch/themes/default/assets/bootstrap/css/font-awesome.min.css">
<link rel="stylesheet" href="/ectouch/themes/default/statics/css/swiper-3.2.5.min.css" />
<link rel="stylesheet" href="/ectouch/themes/default/statics/css/ectouch.css" />
<link rel="stylesheet" href="/ectouch/themes/default/statics/fonts/iconfont.css" />
<link rel="stylesheet" href="/ectouch/themes/default/statics/css/search.css" />
</head>
<body style="max-width:640px;">
<div id="loading"><img src="/ectouch/themes/default/statics/img/loading.gif" /></div><div class="con">
	<div class="category-top blur-div">
	<header>
		<section class="search">
			<div class="text-all dis-box j-text-all">
				<div class="box-flex input-text">
					<a class="a-search-input j-search-input" href="javascript:void(0)"></a>
					<input type="text" placeholder="商品搜索" />
					<i class="iconfont icon-guanbi is-null j-is-null"></i>
				</div>
			</div>
		</section>
	</header>
</div>	<aside>
		<div class="menu-left scrollbar-none" id="sidebar">
			<ul>
								<li  class="active">手机</li>
								<li >电脑</li>
							</ul>
		</div>
	</aside>
		<section class="menu-right padding-all j-content">
													<h5>手机</h5>
				<ul>
									<li class="w-3"><a href="/ectouch/index.php?m=default&c=category&a=index&id=2&u=0"></a>
					<img src="http://localhost/ectouch/data/assets/images/201711/thumb_img/3_thumb_G_1512001106689.jpg" /><span>iphone</span></li>
									<li class="w-3"><a href="/ectouch/index.php?m=default&c=category&a=index&id=3&u=0"></a>
					<img src="http://localhost/ectouch/data/assets/images/201711/thumb_img/2_thumb_G_1512001067413.jpg" /><span>vivo</span></li>
								</ul>
																						</section>
		<section class="menu-right padding-all j-content" style="display:none">
													<h5>电脑</h5>
				<ul>
									<li class="w-3"><a href="/ectouch/index.php?m=default&c=category&a=index&id=5&u=0"></a>
					<img src="http://localhost/ectouch/data/assets/images/201712/thumb_img/4_thumb_G_1512062543814.jpg" /><span>平板</span></li>
									<li class="w-3"><a href="/ectouch/index.php?m=default&c=category&a=index&id=6&u=0"></a>
					<img src="http://localhost/ectouch/data/assets/images/201712/thumb_img/5_thumb_G_1512062571014.jpg" /><span>笔记本</span></li>
								</ul>
																						</section>
	</div>
<div class="search-div j-search-div ts-3">
	<section class="search">
		<form action="/ectouch/index.php?m=default&c=category&a=index&u=0" method="post">
		<div class="text-all dis-box j-text-all">
			<div class="box-flex input-text">
				<input class="j-input-text" type="text" name="keywords" placeholder="请输入搜索关键词！" />
				<i class="iconfont icon-guanbi is-null j-is-null"></i>
			</div>
			<button type="submit" class="btn-submit">搜索</button>
		</div>
		</form>
	</section>
	<section class="search-con">
		<div class="swiper-scroll history-search">
			<div class="swiper-wrapper">
			<div class="swiper-slide">
		<p>
			<label class="fl">热门搜索</label>
		</p>
		<ul class="hot-search a-text-more">
					</ul>
		<p class="hos-search">
			<label class="fl">最近搜索</label>
			<span class="fr" onclick="javascript:clearHistroy();"><i class="iconfont icon-xiao10"></i></span>
		</p>
		
			<ul class="hot-search a-text-more a-text-one" id="search_histroy">
							</ul>
			</div>
			</div>
			<div class="swiper-scrollbar"></div>
		</div>
	</section>
	<footer class="close-search j-close-search">
		点击关闭
	</footer>
</div>
<script type="text/javascript">
//设置cookie
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}
function clearHistroy(){
	setCookie('ECS[keywords]', '', -1);
	document.getElementById("search_histroy").style.visibility = "hidden";
}
</script>
<script type="text/javascript" src="/ectouch/themes/default/statics/js/jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="/ectouch/themes/default/statics/js/swiper-3.2.5.min.js"></script>
<script type="text/javascript" src="/ectouch/themes/default/statics/js/ectouch.js"></script>
<script type="text/javascript" src="/ectouch/data/assets/js/jquery.json.js"></script> 
<script type="text/javascript" src="/ectouch/data/assets/js/common.js"></script>
<script type="text/javascript">
	$(function($){
		$('#sidebar ul li').click(function(){
			$(this).addClass('active').siblings('li').removeClass('active');
			var index = $(this).index();
			$('.j-content').eq(index).show().siblings('.j-content').hide();
		})
	})
</script>
</body>
</html>