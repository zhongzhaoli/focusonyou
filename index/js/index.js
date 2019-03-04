$(function () {
    let type = 1;
    let type_text = "";
    $(".input_qh li").on("click", function () {
        let name = $(this).data("fun");
        if (name === "feedback") {
            type = 2;
            $("[data-fun='feedback'] a").addClass("active");
            $("[data-fun='music'] a").removeClass("active");
            $("[data-card2]").show();
            $("[data-card1]").hide();
        } else {
            type = 1;
            $("[data-fun='music'] a").addClass("active");
            $("[data-fun='feedback'] a").removeClass("active");
            $("[data-card1]").show();
            $("[data-card2]").hide();
        }
    })
    $(".want_say div").on("click", function () {
        $(".dis_block").removeClass("dis_block");
        $(this).find("i").addClass("dis_block");
        type_text = $(this).find("span").html();
    });
    $("[data-upload]").on("click", function () {
        $.ajax({
            url: 'http://10.1.53.149:7899/appeal',
            data: $(".form_div").serialize() + "&type=" + type + "&type_text=" + type_text,
            type: "POST",
            success: function (e) {
                $.DialogByZ.Alert({
                    Title: "提示",
                    Content: e.message,
                    BtnL: "确定",
                    FunL: $.DialogByZ.Close()
                });
            },
            error: function (e) {
                $.DialogByZ.Alert({
                    Title: "错误",
                    Content: e.responseJSON.message,
                    BtnL: "确定",
                    FunL: $.DialogByZ.Close()
                });
            }
        });
    });
})
