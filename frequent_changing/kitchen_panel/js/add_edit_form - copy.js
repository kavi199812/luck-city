$(function () {
    "use strict";
    let base_url = $("#base_url_hide").val();
    function getCategories() {
        let outlet_id = $("#outlet_id").val();
        $.ajax({
            url: base_url+'Kitchen/getKitchenCategoriesByAjax',
            method: "POST",
            data: {outlet_id: outlet_id},
            dataType: 'json',
            success: function (data) {
                $(".category_list").html(data);
            }

        });
    }
    $(document).on('change', '#outlet_id', function() {
        getCategories();
    });
    getCategories();
});