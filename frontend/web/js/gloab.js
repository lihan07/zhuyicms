$(function () {

    touch.on(".down_right li:last-child", "tap", function (ev) {
        if($(ev.currentTarget).find("a").html()=="暂时登出"){
        $(".out_true_box").show();
    }
    });
    touch.on(".out_true_box .quxiao", "tap", function () {
        $(".out_true_box").hide();
    });
 
    
    var widthaa = $("body").width();
    $(".here_img,.pro_here").css("height", widthaa * .56);

    touch.on(".top_right", "tap", function (ev) {
        var right = parseInt($(".down_right").css("right"));
        if (right < 0) {
            $(".down_right_zd").show();
            $(".down_right").animate({right: '0rem'}, 300);
            $(".down_right_zd").animate({opacity: .5}, 200);
        } else {

            $(".down_right").animate({right: '-2.3rem'}, 300);
            $(".down_right_zd").animate({opacity: 0}, 200, function () {
                $(".down_right_zd").hide();
            });
        }
    });
    touch.on(".down_right_zd", "tap", function () {
        $(".down_right").animate({right: '-2.3rem'}, 300);
        $(".down_right_zd").animate({opacity: 0}, 200, function () {
            $(".down_right_zd").hide();
        });
    });
//		var width=parseInt($("body").width())*90/165;
//		$(".pro_here").css("height",width);
//		$(window).resize(function(){
//			var width=parseInt($("body").width())*90/165;
//			$(".pro_here").css("height",width);
//		});
    //input 校验
    $(".dema_ipt").change(function () {
        if ($(this).val() != "") {
            $(this).addClass("red_border");
        } else {
            $(this).removeClass("red_border");
        }
    });


    function out_line() {
        $(".out_Capacity").show().animate({
            opacity: 1
        }, 1000, function () {
            $(".out_Capacity").animate({
                opacity: 0
            }, 1000, function () {
                $(".out_Capacity").hide();
            })
        });
    }
});


