//////javascript scroll down opacity 
$(document).ready(function() {
    $(window).scroll(function(event) {
        let scroll = $(this).scrollTop();
        //let opacity = 1 - (scroll / 1000);
        if (scroll >= 100) {
            $('.logo_back').css('opacity', 0);
        }
        else {
            $('.logo_back').css('opacity', 1);
        }
    });

    $('link[data-href]').each(function() {
        $(this).attr('href',$(this).data('href'));
    });

    const page_image_carousel_init = ()=>{
        $('.page-image-carousel').each(function() {

            const perview = this.getAttribute('data-perview');

            let opts = {
                lazyLoad: true,
                autoPlay: false,
                prevNextButtons: true,
                pageDots: false,
                groupCells: '80%',
                //cellAlign: 'left',
                selectedAttraction: 0.01,
                friction: 0.15,
                contain: true,
                accessibility: true
            };

            $(this).flickity(opts);
        });
        setTimeout(()=>{
            $('.content-carousel').each(function() {
                let childLength = $(this).children().length;
                let options = {
                    cellAlign: 'left',
                    contain: true,
                    groupCells: '80%',
                    accessibility: true,
                    pageDots: false,
                    on: {
                        ready: ()=>{
                            if(childLength <= 1) {
                                $(this).find('.flickity-button').hide();
                            }
                        }
                    }
                };
                $(this).flickity(options);
            });
        },400);
    }

    setTimeout(page_image_carousel_init,500);


    $('.logout-btn').on('click', function(e) {
        e.preventDefault();
        let href = $(this).attr('href');
        Swal.fire({
            title:'Logout from current session?',
            showCancelButton:true,
            showClass: {
                popup: 'animated fadeIn'
            },
            hideClass: {
                popup: 'animated fadeOut'
            }
        }).then((confirm)=>{
            if(confirm.isConfirmed) {
                window.location = href;
            }
        });
    });

    $(document).on('click','[data-popup]', function() {
        const popup = $(this).data('popup');
        $(popup).toggleClass('open');
    });
    $(document).on('click','.popup_close', function() {
        $(this).closest('.popup_container').removeClass('open');
    });
});


function changeImage() {
    var image = document.getElementById("img1");
    if (image.src.match("menu")) {
        image.src = "<?php echo base_url() ?>/assets/images/close.png";
    } else {
        image.src = "<?php echo base_url() ?>/assets/images/menu.png";
    }
}

/*Bilal code*/

window.reload_element = function(element, fn) {
    $.get(location.href).done(function(html) {
        $(html).each(function() {
            let _this = this;
            if($(_this).find(element).length) {
                $(_this).find(element).each(function() {
                    let _find = $(this);
                    $(element).each(function() {
                        if($(this).index() == _find.index()) {
                            $(this).html(_find.html());
                        }
                    });
                });
            }
        }).promise().done(function() {
            if(typeof fn == "function") {
                fn();
            }
        });
    });
}

window.warn = (message,config={}) =>{
    let default_config = {
        position: 'center',
        icon: 'error',
        title: message,
        showConfirmButton: true,
        showCancelButton: false,
        showClass: {
            popup: 'animated fadeIn'
        },
        hideClass: {
            popup: 'animated fadeOut'
        },
        iconColor:'#d62135',
        background:'#fff'
    };
    return Swal.fire({...default_config,...config});
}

window.info = (message,config={}) =>{
    let default_config = {
        position: 'center',
        icon: 'info',
        title: message,
        showConfirmButton: true,
        showCancelButton: false,
        showClass: {
            popup: 'animated fadeIn'
        },
        hideClass: {
            popup: 'animated fadeOut'
        },
        iconColor:'#d62135',
        background:'#fff'
    };
    return Swal.fire({...default_config,...config});
}

window.message = (message,config={}) =>{
    let default_config = {
        position: 'center',
        title: message,
        showConfirmButton: true,
        showCancelButton: false,
        showClass: {
            popup: 'animated fadeIn'
        },
        hideClass: {
            popup: 'animated fadeOut'
        },
        iconColor:'#d62135',
        background:'#fff'
    };
    return Swal.fire({...default_config,...config});
}

window.notification = (message,timer=6000)=>{
    let opts = {
        text: message,
        toast: true,
        position: 'top',
        color: '#fff',
        background: '#d8262f',
        showConfirmButton:false,
        showClass: {
            popup: 'animated windowIn'
        },
        hideClass: {
            popup: 'animated windowOut'
        },
    };
    if(timer) {
        opts.timer = timer;
    }
    Swal.fire(opts);
    Swal.resetValidationMessage();
}

$(document).on('click','[data-confirm]', function(e) {
    e.preventDefault();
    const confirm = $(this).data('confirm');
    const href = $(this).data('href');
    message(confirm,{showCancelButton:true}).then((res)=>{
        if(res.isConfirmed) {
            window.location = href;
        }
    });
});

$.get(site_url+'/ajax/get_cart', function(response) {
    $('.cart-count-number').hide();
    if(response && response !== "null") {
        const res = JSON.parse(response);
        const count = typeof res.products !== "undefined" ? Object.keys(res.products).length : 0;
        if(count) {
            $('.cart-count-number').text(count).show();
        }else {
            $('.cart-count-number').text(count).hide();
        }
    }
});

$('.single_add_to_cart_button').closest('form').on('submit', function(e) {
    e.preventDefault();
    if(this.reportValidity()) {
        const form = $(this).serialize();
        const url = site_url + '/ajax/add_cart';
        $('.cart-count-number').hide();
        $(this).closest('.add_to_cart_form').addClass('loading');
        const _this = this;
        $.post(url, form, function (response) {
            if (response) {
                const res = JSON.parse(response);
                if(typeof res.status !== "undefined" && res.status === "error") {
                    let errors = res.errors.join('<br>');
                    Swal.fire({
                        position: 'bottom-end',
                        html: errors,
                        showConfirmButton: false,
                        showCancelButton: false,
                        timer: 5000,
                        backdrop: 'transparent',
                        toast: true,
                        color: '#d8262f',
                        showClass: {
                            popup: 'animated fadeInDown single_add_to_cart error'
                        },
                        hideClass: {
                            popup: 'animated fadeOutUp single_add_to_cart error'
                        }
                    });
                }
                const count = typeof res.products !== "undefined" ? Object.keys(res.products).length : 0;
                if (count) {
                    $('.cart-count-number').text(count).show();
                    Swal.fire({
                        position: 'bottom-end',
                        title: 'Product is added to basket',
                        html: '<a href="'+site_url+'cart">View basket</a>',
                        showConfirmButton: false,
                        showCancelButton: false,
                        // timer: 5000,
                        backdrop: 'transparent',
                        toast: true,
                        color: '#d8262f',
                        showClass: {
                            popup: 'animated fadeInDown single_add_to_cart success'
                        },
                        hideClass: {
                            popup: 'animated fadeOutUp single_add_to_cart success'
                        }
                    });
                } else {
                    $('.cart-count-number').text(count).hide();
                }
                $(_this).closest('.add_to_cart_form').removeClass('loading');
            }
        });
    }
});

$('form.validate button[type=submit]').on('click',function(e) {
    const form = this.closest('form');
    $(form).find('input,select,textarea').each(function() {

        let error = $(this).data('error');
        $(this).next('.error_message').remove();
        if(typeof error === "undefined") {
            error = this.validationMessage;
        }
        if(!this.checkValidity()) {
            if(this.value.length) {
                error = this.validationMessage;
            }
            e.preventDefault();
            $(this).after('<div class="error_message">'+error+'</div>');
            this.focus();
        }
        $(this).on('change', function() {
            if(this.checkValidity()) {
                $(this).next('.error_message').remove();
            }
        });
    }).promise().done(function() {
        if($(form).find('.error_message').length) {
            const cart_div_offset = $('#woocommerce-cart-form-div').offset().top;
            let offset = $(form).find('.error_message').offset().top;
            offset = offset - (cart_div_offset + 100);

            $("html,body").scrollTop(offset);

        }
    });
});

remove_cart_item = (_this)=>{

    message('Remove this item?',{showCancelButton:true}).then((res)=>{
        if(res.isConfirmed) {
            const pid = $(_this).data('item');
            $('.shop_table').addClass('loading');
            const url = site_url+'cart/remove_item';
            const form = 'item_id='+pid;
            $.post(url, form, function(response) {
                location.reload();
                // reload_element('#woocommerce-cart-form-div', function() {
                //     $('.shop_table').removeClass('processing');
                //     if(response) {
                //         const res = JSON.parse(response);
                //         const count = typeof res.products !== "undefined" ? Object.keys(res.products).length : 0;
                //         if(count) {
                //             $('.cart-count-number').text(count).show();
                //         }else {
                //             $('.cart-count-number').text(count).hide();
                //         }
                //     }
                // });
                // reload_element('.woocommerce-cart-form');
            });
        }
    });
}

$(function() {
    window.init_shop_subscription_form = ()=> {
        $('.shop_subscription_form').each(function() {
            const _this = this;
            const price = $(this).data('price');
            const qty = $(this).data('qty');
            $(_this).html("");
            $.get(site_url+`ajax/shop_subscription_form?price=${price}&qty=${qty}`, function(response) {
                $(_this).html(response);
            })
        });
    }
})



