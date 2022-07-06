/* Filters */
$('body').on('change', '.w_sidebar input', function(){
    let checked = $('.w_sidebar input:checked');
    let data = '';
    checked.each(function(){
        data += this.value + ',';
    });
    if(data){
        $.ajax({
            type: "GET",
            url: location.href,
            data: {filter: data},
            beforeSend: function(){
                $('.preloader').fadeIn(300, function(){
                    $('.product-one').hide();
                });
            },
            success: function (response) {
                $('.preloader').delay(200).fadeOut('slow', function(){
                    $('.product-one').html(response).fadeIn();
                    let url = location.search.replace(/filter(.+?)(&|$)/g, '');
                    let newUrl = location.pathname + url + (location.search ? "&" : "?") + "filter=" + data;
                    newUrl = newUrl.replace('&&', '&');
                    newUrl = newUrl.replace('?&', '?');
                    history.pushState({}, '', newUrl);
                    console.log(url, newUrl);
                });
            },
            error: function(){
                alert('Ошибка!');
            }
        });
    }else{
        window.location = location.pathname;
    }
});


/* Search */
let products = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.whitespace,
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    remote: {
        wildcard: '%QUERY',
        url: path + '/search/typeahead?query=%QUERY'
    }
});

products.initialize();

$("#typeahead").typeahead({
    // hint: false,
    highlight: true
},{
    name: 'products',
    display: 'title',
    limit: 10,
    source: products
});

$('#typeahead').bind('typeahead:select', function(ev, suggestion) {
    // console.log(suggestion);
    window.location = path + '/search/?s=' + encodeURIComponent(suggestion.title);
});

/*Cart*/
$('body').on('click', '.add-to-cart-link', function(e){
    e.preventDefault();
    let id = $(this).data('id'),
        qty = $('.quantity input').val() ? $('.quantity input').val() : 1,
        mod = $('.available select').val();
    $.ajax({
        type: "GET",
        url: "/cart/add",
        data: {id: id, qty: qty, mod: mod},
        success: function (res) {
            showCart(res);
        },
        error: function () {
            alert('Ошибка! Попробуйте позже')
        }
    });
});

$('#cart .modal-body').on('click', '.del-item', function(){
    let id = $(this).data('id');
    $.ajax({
        type: "GET",
        url: "/cart/delete",
        data: {id: id},
        success: function (res) {
            showCart(res);
        },
        error: function(){
            alert('Error!');
        }
    });
});

function showCart(cart){
    if($.trim(cart) == '<h3>Корзина пуста</h3>'){
        $('#cart .modal-footer a, #cart .modal-footer .btn-danger').css('display', 'none');        
    }else{
        $('#cart .modal-footer a, #cart .modal-footer .btn-danger').css('display', 'inline-block');  
    }
    $('#cart .modal-body').html(cart);
    $('#cart').modal();
    if($('.cart-sum').text()){
        $('.simpleCart_total').html($('#cart .cart-sum').text());
    }else{
        $('.simpleCart_total').text('Empty Cart');
    }
}
function getCart(){
    $.ajax({
        type: "GET",
        url: "/cart/show",
        success: function (res) {
            showCart(res);
        },
        error: function () {
            alert('Ошибка! Попробуйте позже')
        }
    });
}

function clearCart(){
    $.ajax({
        type: "GET",
        url: "/cart/clear",
        success: function (res) {
            showCart(res);
        },
        error: function () {
            alert('Ошибка! Попробуйте позже')
        }
    });
}
/*Cart*/



$('.currency').change(function (e) {
    window.location ='currency/change?curr=' + $(this).val();
});

$('.available select').on('change', function(){
    let modId = $(this).val(),
        color = $(this).find('option').filter(':selected').data('title'),
        price = $(this).find('option').filter(':selected').data('price'),
        basePrice = $('#base-price').data('base');
    if(price){
        $('#base-price').text(symboleLeft + price + symboleRight);
    }else{
        $('#base-price').text(symboleLeft + basePrice + symboleRight);
    }
});