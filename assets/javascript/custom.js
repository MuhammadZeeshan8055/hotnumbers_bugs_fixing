select2_init_timeout = 0;
window.select2_init = (opts={})=>{
        clearTimeout(select2_init_timeout);
        select2_init_timeout = setTimeout(()=>{
            try {
                console.log($('select.select2'));
                $('select.select2').each(function() {
                    $(this).removeClass('select2-hidden-accessible');
                    $(this).next('.select2').remove();
                    if($(this).hasClass('.select2-hidden-accessible')) {
                        $(this).select2('destroy');
                    }
                    if($(this).data('search') === false) {
                        opts.minimumResultsForSearch = -1;
                    }else {
                        opts.minimumResultsForSearch = '';
                    }

                    $(this).select2(opts);
                }).on('select2:open', function (e) {
                    document.querySelector('.select2-container--open .select2-search__field').focus();
                });
            }catch (e) {
                console.log(e);
            }
        },200);
}

$(document).ready(function() {

    document.querySelectorAll('select[value]').forEach((select)=>{
        if(select.getAttribute('value')) {
            select.value = select.getAttribute('value');
        }
    });

    select2_init();

    $('.prevent-enter').keydown(function(e) {
       if(e.keyCode == 13) {
           if(e.keyCode == 13) {
               e.preventDefault();
               return false;
           }
       }
    });

    $(window).scroll(function(event) {
        let scroll = $(this).scrollTop();
        //let opacity = 1 - (scroll / 1000);
        if (scroll >= 100) {
            $('.logo_back').fadeOut('fast');
        }
        else {
            $('.logo_back').fadeIn('fast');
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

    if(location.hash) {
        const hash_position = $(location.hash).offset().top - 100;
        setTimeout(()=>{
            window.scrollTo(0, hash_position);
        },200)
    }
});

function changeImage() {
    var image = document.getElementById("img1");
    if (image.src.match("menu")) {
        image.src = "<?php echo base_url() ?>/assets/images/close.png";
    } else {
        image.src = "<?php echo base_url() ?>/assets/images/menu.png";
    }
}

window.fetchPostRequest = async (url, body)=>{
    return await fetch(url, {
        method: "POST",
        body: body
    }).then(res=> {
        if(res.status === 200) {
            return res.json();
        }else {
            warn(res.status+' '+res.statusText);
            throw 'Ajax error: '+res.status+' '+res.statusText;
        }
    }).catch(res=>{
        if(res) {
            warn(res);
        }
    });
}

function setCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
function eraseCookie(name) {
    document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}

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
            popup: 'animated fadeIn info'
        },
        hideClass: {
            popup: 'animated fadeOut info'
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
            popup: 'animated windowIn message'
        },
        hideClass: {
            popup: 'animated windowOut message'
        },
        iconColor:'#d62135',
        background:'#fff'
    };
    return Swal.fire({...default_config,...config});
}

window.notification = (message,timer=6000)=>{
    let opts = {
        html: '<div style="color: #fff">'+message+'</div>',
        toast: true,
        position: 'top',
        background: '#d8262f',
        showConfirmButton:false,
        customClass: {
            container: 'notification_msg'
        },
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

document.querySelectorAll('form button[type*=submit]').forEach((btn)=>{
    btn.closest('form').addEventListener('submit', ()=>{
       // btn.disabled = true
    })
})

$('form.validate button[type=submit]').on('click',function(e) {
    const form = this.closest('form');
    form.classList.add('loading');
    $(form).find('input[required],select[required],textarea[required]').each(function() {

        let error = $(this).data('error');
        $(this).parent().find('.error_message').remove();

        if(typeof error === "undefined") {
            error = this.validationMessage;
        }

        if(!this.checkValidity()) {
            e.preventDefault();
            $(this).parent().append('<div class="error_message">'+error+'</div>');
            this.focus();
        }

        $(this).on('change', function() {
            if(this.checkValidity()) {
                $(this).parent().find('.error_message').remove();
            }
        });

    }).promise().done(function() {
        form.classList.remove('loading');
        if($(form).find('.error_message').length) {
            const cart_div_offset = $('#woocommerce-cart-form-div').offset() ? $('#woocommerce-cart-form-div').offset().top : 0;
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
            });
        }
    });
}


function passwordStrengthCheck(input_string, input_element='') {
    const n = input_string.length;
    // Checking lower alphabet in string
    let hasLower = false;
    let hasUpper = false;
    let hasDigit = false;
    let specialChar = false;
    const normalChars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890 ";

    for (let i = 0; i < n; i++) {
        if (input_string[i] >= "a" && input_string[i] <= "z") {
            hasLower = true;
        }
        if (input_string[i] >= "A" && input_string[i] <= "Z") {
            hasUpper = true;
        }
        if (input_string[i] >= "0" && input_string[i] <= "9") {
            hasDigit = true;
        }
        if (!normalChars.includes(input_string[i])) {
            specialChar = true;
        }
    }

    let strength = '';

    if(n > 0) {
        // Strength of password
        strength = "weak";
        if (hasLower && hasUpper && hasDigit && specialChar && n >= 14) {
            strength = "strong";
        } else if (((hasLower || hasUpper)) && n >= 12) {
            strength = "moderate";
        }
    }


    return strength;
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

    $('.password_input').each(function() {
       const old_errormsg = $(this).attr('data-error') || '';
       $(this).on('keyup', function() {
          const strength = passwordStrengthCheck(this.value, this);
          $(this).removeClass('password-weak');
          $(this).removeClass('password-medium');
          $(this).removeClass('password-strong');
          $(this).next('.error_message').remove();
          const btn = $(this).closest('form').find('[type="submit"]');

           btn.prop('disabled',false);

          if(strength === "weak") {
              $(this).addClass('password-weak');
              $(this).attr('data-error','Please enter a strong password');
              $(this).after('<div class="error_message"><p>Please enter a strong password</p></div>');
              btn.prop('disabled',true);
              $(this).parent().find('.invalid-msg').show();
          }else {
              $(this).attr('data-error',old_errormsg);
          }
           if(strength === "moderate") {
               $(this).addClass('password-medium');
               $(this).parent().find('.invalid-msg').hide();
           }
           if(strength === "strong") {
               $(this).addClass('password-strong');
               $(this).parent().find('.invalid-msg').hide();
           }
       });
    });

    $('form.ajaxsubmit').on('submit', function(e) {
        const form = this;
        $(form).addClass('processing');
        e.preventDefault();
        const action = $(this).attr('action');
        $(form).find('[type="submit"]').removeAttr('disabled');

        $.post(action,$(this).serialize(), function(data) {
            const data_ = JSON.parse(data);
            if(data_.success) {
                message(data_.message);
                $(form).removeClass('processing');
            }
        });
    });
});
