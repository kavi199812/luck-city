$(document).ready(function() {
    "use strict";
    let base_url_ = $("#base_url_").val();

    if ($.fn.select2) {
        $('#category_filter').select2();
    }

    //counter class count like as 1,2,3,4
    function counter() {
        let i = 1;
        $(".counters").each(function(){
            $(this).html(i);
            i++;
        });
    }

    // Category change dropdown -> navigate to that category
    $('#category_filter').on('change', function() {
        let catId = $(this).val();
        window.location.href = base_url_ + 'foodMenu/sortingForPOS/' + catId;
    });

    // call dragsort function
    $('#sortMenu').dragsort({
        cursor:'move',
        dragEnd: function() {
            counter();
            let data = $("form#sorting_form").serialize();
            $.ajax({
                url     : base_url_ + 'Authentication/sortingFoodMenu',
                method  : 'get',
                dataType: 'json',
                data    : data,
                success:function(data){
                    if(typeof toastr !== 'undefined') {
                        toastr.options = { "timeOut": "1500" };
                        toastr['success']('Order updated successfully');
                    }
                }
            });
        },
    });
});
