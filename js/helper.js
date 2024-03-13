
$(document).ready(function () {
    let adress = document.location.pathname;
    let find= "/leads.html";
    let find_menu = "/leads.html";
    if(adress ===find){
        $("ul#topmenu").find("a[href='"+find_menu+"']").closest("li").addClass("active");
    }
});
$(document).ready(function () {
    $('input.dp').datepicker({
        format: "yyyy-mm-dd",
        maxViewMode: 2,
        //clearBtn: true,
        language: "ru",
        autoclose: true,
        toggleActive: true
    });
});



