$(document).ready(function(){
    $('.btn-search').click(function(){
        var url = baseUrl + '/user/search-business';
        var data = {search: $('.search-input').val()};
        paging(url, data);
        // $.ajax({
        //     url: baseUrl + '/user/search-business',
        //     type: 'POST',
        //     data: {
        //         search: $('.search-input').val(),
        //         _token: $('meta[name="csrf-token"]').attr('content')
        //     },
        //     success: function(data) {
        //         $('.content-data-list').html(data);
        //     },
        //     error: function(data) {
        //     }
        // });
    });
    $('.search-input').keydown(function(e){
        if (e.keyCode == 13) {
            $('.btn-search').trigger('click');
        }
    });
});