$(function(){
//	$('.bxslidera').bxSlider({controls:false,auto:false,pause:5000,speed:800,gett:"user_tab",gettt:["订单","需求","收藏"]});
	widtha=$("body").width();
	var top=$(".nac_boxa").offset().top;
	var topa=$(".header_top").height();
	touch.on("body","touchmove",function(){
		auto();
	});
        $(".leave_word  .tj_spb i").each(function(){
		var data=$(this).html().replace(/-/g, "/");
                if(data==""||data==" "){
                    
                }else{
                    
                    var getdata=new Date(data);
                    var month=getdata.getMonth()+1;
                    var day=getdata.getDate();
                    var hours=getdata.getHours();

                    if(hours==0){
                            hours="";
                    }else{
                            hours=hours+"点";
                    }
                    $(this).html("["+month+"月"+day+"日"+ hours+"]");
                }
            });
//	//统计圆环
//	var size=parseInt(parseInt($("html").css("font-size"))*1.2);
//		var widtht=parseInt($("html").css("font-size"))*.08;
//		$('.percentage-light').easyPieChart({
//				 barColor: '#ff4e38',
//	      		trackColor: '#eeefef',
//	      		scaleColor: '#ffffef',
//				scaleColor: false,
//				size:size,
//				lineCap: 'butt',
//				lineWidth: 4,
//				animate: 1000
//	});	
	var indexaa;
//	//左右滑动事件
//	touch.on("#nav_box","swipeleft",function(){
//		if(indexaa<2){
//			indexaa++;
//			duang();
//		}
//	});
//	touch.on("#nav_box","swiperight",function(){
//		if(indexaa>0){
//			indexaa--;
//			duang();
//		}
//	});
	
	//帮助按钮
	$(document).on("click",".shanchu_box",function(){
		var _this=$(this);
			_this.parents(".iconfont").remove();
		$.ajax({
				type:"get",
				url:"",
				async:true,
				success:function(data){
					
				
				}
		});
	});
	touch.on(".help_btn","tap",function(ev){
		if($(".help_bot_zd").css("display")=="none"){
			$(".help_bot_zd").show().animate({opacity:.5},300);
			$(".iphone_btn").show().animate({top:"-1.68rem"},300);
			$(".bot_btn").show().animate({top:"-2.96rem"},300);
		}else{
			$(".iphone_btn").animate({top:0},300);
			$(".bot_btn").animate({top:0},300);
			$(".help_bot_zd").animate({opacity:.5},300,function(){
				$(".help_bot_zd,.bot_btn,.iphone_btn").hide();
			});
		}
		
	})
	touch.on(".help_bot_zd","tap",function(ev){
		$(".iphone_btn").animate({top:0},300);
			$(".bot_btn").animate({top:0},300);
			$(".help_bot_zd").animate({opacity:.5},300,function(){
				$(".help_bot_zd,.bot_btn,.iphone_btn").hide();
			});
		
	});
	
	function auto(){
		var scrtop=document.body.scrollTop;
		if(scrtop>=top-topa){
			$(".nac_boxa").addClass("fixed_top");
		}else{
			$(".nac_boxa").removeClass("fixed_top");
		}
	}
	
//	 document.body.addEventListener('touchmove', function(event) {
//      event.preventDefault();
//   }, false);

 touch.on(".true_btn","tap",function(ev){
 	$(ev.currentTarget).parents(".zy_pp").addClass("foin_zy").siblings().removeClass("foin_zy");
 });
var yes_no="";
touch.on(".queren_btn .true_btnaa","tap",function(ev){
		var _this=$(this).parents(".zy_pp");
		var order_id=_this.attr("order_id");
		var url=$(ev.currentTarget).attr("url");
		yes_no="yes";
		$.ajax({
			type: "get",
			url: url+"&&params="+order_id+","+yes_no,
			async: true,
			success: function(data) {
				_this.find(".right_type").html("已深度合作");
				_this.find(".jm_money:eq(0),.jm_time:eq(0)").remove();
				_this.find(".queren_btn").remove();
	}
		});
	});
	
	touch.on(".queren_btn .true_btna","tap",function(ev){
		var _this=$(this).parents(".zy_pp");
		var order_id=_this.attr("order_id");
		var url=$(ev.currentTarget).attr("url");
		yes_no="no";
		$.ajax({
			type: "get",
			url: url+"&&params="+order_id+","+yes_no,
			async: true,
			success: function(data) {
				var html='<span class="ooo_spa ">已见面</span><span class="ooo_spb">未深度合作</span>'
				_this.find(".right_type").html(html).addClass("color_ccca");
				_this.find(".tj_box").remove();
				_this.find(".queren_btn").remove();
			}
		});
	});
	
	//空白页面居中
		$(".Blank_Page").each(function(){
			var tishia=$(this).parent().siblings(".tishi").height();
		var height=$(window).height()-$("#user_box").height()-tishia-$(".header_top").height()*2;
		var heighta=(height-$(this).find(">span").height())/2;
		$(this).css("height",height);
                
		$(this).find(">span").css("marginTop",heighta)
	});
//	 document.body.addEventListener('touchmove', function(event) {
//      event.preventDefault();
//   }, false);
touch.on(".true_btn","tap",function(ev){
 	$(ev.currentTarget).parents(".zy_pp").addClass("foin_zy").siblings().removeClass("foin_zy");
 });
var currYear = (new Date()).getFullYear();	
			var opt={};
			opt.date = {preset : 'date'};
			opt.datetime = {preset : 'datetime'};
			opt.time = {preset : 'time'};
			opt.default = {
				theme: 'android-ics light', //皮肤样式
		        display: 'modal', //显示方式 
		        mode: 'scroller', //日期选择模式
				dateFormat: 'yyyy-mm-dd',
				lang: 'zh',
				showNow: true,
				nowText: "今天",
				stepMinute: 30,
		        startYear: currYear - 10, //开始年份
		        endYear: currYear + 10 //结束年份
		        
			};
			$(".true_btn").each(function(ev){
				var gettt=[];
				var grrr=$(this).parents(".zy_pp").find(".time_list");
				grrr.find(">i").each(function(){
                                    
					var h=$(this).html().replace(/\s+/g,"");
                                        h=h.replace(/^[\[]/,"").replace(/[\]]$/,"");
                                        if(h==""){
                                            
                                        }else{
					 gettt.push(h);	
                                        };
				});
				console.log(gettt)
				var time_list="";
				var optTime = $.extend(opt['time'], opt['default'],{get:gettt});
		    	$(this).mobiscroll(optTime).time(optTime);
			});
		  	
		    
});
