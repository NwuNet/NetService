(function ($) {
    $.fn.labelauty = function (tag, tag2) {
        
        //判断是否选中
        rdochecked(tag);

        //单选or多选
        if (tag2 == "rdo") {
            //单选
            $(".rdobox").click(function () {
                $(this).prev().prop("checked", "checked");
                rdochecked(tag);
            });
        } else {
            //多选
            $(".chkbox").click(function () {
                //
                if ($(this).prev().prop("checked") == true) {
                    $(this).prev().removeAttr("checked");
                }
                else {
                    $(this).prev().prop("checked", "checked");
                }
                rdochecked(tag);
            });
        }

        //判断是否选中
        function rdochecked(tag) {
            $('.' + tag).each(function (i) {
                var rdobox = $('.' + tag).eq(i).next();
                if ($('.' + tag).eq(i).prop("checked") == false) {
                	if(rdobox.hasClass("chkwarning")){
                		rdobox.removeClass("checked-warning").addClass("unchecked");
                	}else if(rdobox.hasClass("chkdanger")){
                		rdobox.removeClass("checked-danger").addClass("unchecked");
                	}else{
                		rdobox.removeClass("checked").addClass("unchecked");
                	}
                    rdobox.find("#chkbox-img").removeClass("check-image").addClass("uncheck-image");
                }
                else {
                	if(rdobox.hasClass("chkwarning")){
                		rdobox.removeClass("unchecked").addClass("checked-warning");
                	}else if(rdobox.hasClass("chkdanger")){
                    	rdobox.removeClass("unchecked").addClass("checked-danger");
                	}else{
                		rdobox.removeClass("unchecked").addClass("checked");
                	}
                    rdobox.find("#chkbox-img").removeClass("uncheck-image").addClass("check-image");
                }
            });
        }
    }
}(jQuery));