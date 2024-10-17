const hasCollapsible = document.querySelectorAll(".has-collapsible");
// Collapsible Menu
hasCollapsible.forEach(function (collapsible) {
    collapsible.addEventListener("click", function () {
        collapsible.classList.toggle('active');

        // Close Other Collapsible
        hasCollapsible.forEach(function (otherCollapsible) {
            if (otherCollapsible !== collapsible) {
                otherCollapsible.classList.remove("active");
            }
        });
    });
});

document.querySelectorAll("[data-slug]").forEach((ele)=>{
    const slug = ele.getAttribute('data-slug');
    const target = document.querySelector(slug);
    ele.addEventListener('keyup',(input)=>{
        let slug = input.target.value.toLowerCase();
        slug = slug.replaceAll(' ','-',slug);
        slug = slug.replaceAll('_','-',slug);
        slug = slug.trim();
        slug = slug.replace(/[^\w-]+/g, '');
        target.value = slug;
    });
});



window.customers_autocomplete = ()=> {
    $('select.customers_autocomplete').select2({
        minimumInputLength: 2,
        ajax: {
            url: site_url+"/admin/ajax/user_list_json",
            dataType: 'json',
            processResults: function (resp) {
                if(resp.length) {
                    let remoteData = [
                        {id: 0, text: 'All users'}
                    ];
                    resp.forEach((res)=>{
                        // let displayname = res.display_name;
                        // if(!displayname) {
                        //
                        // }
                        let displayname = res.display_name;
                        if(!displayname) {
                            displayname = res.fname+' '+res.lname;
                        }
                        if(!displayname) {
                            displayname = res.username;
                        }
                        displayname += ' ('+res.email+')';
                        remoteData.push({id: res.user_id, text: displayname});
                    });
                    return {
                        results: remoteData
                    };
                }
            }
        }
    }).on('select2:open', function (e) {
        document.querySelector('.select2-search__field').focus();
    });
}
customers_autocomplete();

window.product_autocomplete = ()=> {
    $('select.product_autocomplete').each(function() {
        $(this).select2({
            minimumInputLength: 2,
            ajax: {
                url: site_url+"/admin/ajax/product_list_json?fields=id,title",
                dataType: 'json',
                processResults: function (resp) {
                    if(resp.length) {
                        let remoteData = [
                            {id: 0, text: 'Select Product'}
                        ];
                        resp.forEach((res)=>{
                            remoteData.push({id: res.id, text: res.title});
                        });
                        return {
                            results: remoteData
                        };
                    }
                }
            }
        }).on('select2:open', function (e) {
            document.querySelector('.select2-search__field').focus();
        });
    })
}
product_autocomplete();

window.select_val = ()=> {
    document.querySelectorAll('select[value]').forEach((select)=>{
        const v = select.getAttribute('value').split(',');
        if(v) {
            v.forEach((val)=>{
                $(select).find('option').each(function(opt) {
                    if($(this).attr('value') === val.trim()) {
                        $(this).attr('selected','selected');
                    }
                });
            });
        }
    });
}

$(function() {
    select_val();
    select2_init();

    $('form').each(function() {
        $(this).on('submit', function(e) {
            const _this = this;
            $(_this).find('[type=submit]').attr('disabled','disabled');
            $(document).ajaxComplete(function() {
                $(_this).find('[type=submit]').attr('disabled','disabled');
            });
        });
    });

    $('.datepicker').each(function() {
        $(this).attr('type','text');
        const val = this.value;
        $(this).datepicker({
            autoPick: false,
            format: 'DD/MM/YYYY'
        });
        $(this).trigger('change');
    });

    $(document).on('click','.list-dropdown', function(e) {
        e.preventDefault();
        $('.list-dropdown').not(this).removeClass('open');
        $(this).addClass('open');
        $(this).find('.dropdown a').on('click', function() {
            window.location = this.href;
        });
    });

    $(window).on('click', function(e) {
        const target = e.target;
        if(!$(target).closest('.list-dropdown').length) {
            $('.list-dropdown').removeClass('open');
        }
    });
})


ajax_submit = (form)=> {
    form.classList.add('processing');
    let formData = new FormData(form);
    let action = form.getAttribute('action') ? form.getAttribute('action') : "";
    let onsubmit = $(form).data('onsubmit');
    let onfailure = $(form).data('onfailure');

    fetch(action,{
        method: "POST",
        body: formData
    }).then(response=>response.json()).then((data)=>{
        if(data.success) {
            Swal.fire({
                title: data.message,
                toast: true,
                timer: 4000,
                position: 'top',
                background: '#d8262f',
                showConfirmButton:false
            });
            if(onsubmit) {
                let fn = new Function(onsubmit);
                fn();
            }
        }else {
            if(data.message) {
                Swal.fire({
                    title: data.message,
                    toast: true,
                    timer: 4000,
                    position: 'top',
                    background: '#d8262f',
                    showConfirmButton:false
                });
                if(onfailure) {
                    let fn = new Function(onfailure);
                    fn();
                }
            }
        }
        form.classList.remove('processing');
    });
}

const featured_title = document.querySelectorAll(" .search_row  .featured_title");

featured_title.forEach(function (featured) {
    featured.addEventListener("keyup", function () {
        if ($(this).val().length !== 0) {
            $(this).after().remove("ul");
            const data = new URLSearchParams({name: this.value, slug: $("#type").val()}).toString();
            fetch(admin_url + "/home/search_featured_books", {
                method: 'post', // or 'PUT'
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: data
            })
                .then(response => response.json())
                .then(result => {
                    $(this).parent().find('.search_result').html(result.response);
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        } else {

            $(this).next().val("");
            console.log("empty");

        }
    });
});


$(document).on("keyup", ".book_title,.author_title", function () {

    $search_field = $(this).data('searchfiled');

    const data = new URLSearchParams({name: this.value}).toString();
    fetch(admin_url + "/post/" + $search_field, {
        method: 'post', // or 'PUT'
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: data
    })
        .then(response => response.json())
        .then(result => {
            console.log($(this));
            $(this).parent().find('.search_result').html(result.response);
        })
        .catch(error => {
            console.error('Error:', error);
        });
});

$(document).on("click", ".featured_post", function () {
    $post_id = $(this).data('postid');
    if ($(this).is(":checked")) {
        $('#books_table .featured_post').prop('checked', false)
        $(this).prop('checked', true);
    }
    data = $(this).serialize();

    Swal.fire({
        title: 'Status Updating',
        html: 'Please Wait..',// add html attribute if you want or remove
        showConfirmButton: false,
        allowOutsideClick: false,
        onBeforeOpen: () => {
            Swal.showLoading()
        },

    });
    fetch(admin_url + "/post/update_featured_post/" + $post_id, {
        method: 'post', // or 'PUT'
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: data
    })
        .then(response => response.json())
        .then(result => {
            setTimeout(function () {
                Swal.close()
            }, 1000)


        })
        .catch(error => {
            console.error('Error:', error);
        });

});

$(document).on("click", ".add_more_field", function () {
    $(this).closest('.search_row').find('.search_input_filed:first-child').clone().insertBefore($(this)).append("<i class=\"lni lni-cross-circle\"></i>").find("input[type='text']").val("");
});

$(document).on("click", ".lni lni-cross-circle", function () {
    $search_lenght = $(this).closest('.search_row ').find(".search_input_filed").length;
    if ($search_lenght > '2') {
        $(this).closest('.search_input_filed').remove();
    } else {
        $(this).closest('.search_input_filed').prev().find(".lni lni-cross-circle").remove();
        $(this).closest('.search_input_filed').remove();
    }
});

$(document).on("click", ".select_title", function () {
    var title = $(this).text();
    //$(this).data() ($(this).data('id'));
    console.log($(this).data('img'));
    $(this).closest(".search_input_filed").children('input').val(title);
    $(this).closest(".search_input_filed").children('input[type=hidden]').val($(this).data('id'));
    $(this).closest(".search_row ").find('.featured_book_cover_img').css("background-image", "url(" + $(this).data('img') + ")");
    $(this).closest(".serach_books").remove();

});


$(document).ready(function () {
    $(document).on('change', '#status', function () {

        // From the other examples
        if (!this.checked) {
            $(this).closest(".swatch").find('#status_input').val("0");
        } else {
            $(this).closest(".swatch").find('#status_input').val("1");

        }
    });
    $('.board_notice').addClass('show animated fadeInRight');
    setTimeout(function () {
        $('.board_notice').removeClass('fadeInRight').addClass('fadeOutRight');
    }, 3000)

});

function input_checkbox_empty_fix(ele = 'input[type=checkbox]') {
    $(ele).each(function() {
        const rid = '_'+Math.random().toString(5).substring(5);
        if(!this.id) {
            this.id = rid;
        }
        const name = this.name;
        if (!$(this).prev('.checkbox_input').length) {
            $(this).before('<input type="hidden" class="checkbox_input" name="' + name + '" value="">');
            if(this.checked) {
                this.name = name;
                $(this).prev('.checkbox_input').attr('name','');
            }else {
                this.name = '';
                $(this).prev('.checkbox_input').attr('name',name);
            }
            $(document).on('click','#'+this.id, function() {
                if(this.checked) {
                    this.name = name;
                    $(this).prev('.checkbox_input').attr('name','');
                }else {
                    this.name = '';
                    $(this).prev('.checkbox_input').attr('name',name);
                }
            });
        };
    });
}

$(document).ready(function () {
    $("#login_form").submit(function (e) {
        
        e.preventDefault();
        $(".loading").css("visibility", "visible");
        $(".alert_danger").css("visibility", "hidden");
        const data = $(this).serialize();
        
        fetch(admin_url + "/authentication", {
            method: 'post', // or 'PUT'
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: data
        })
            .then(response => response.json())
            .then(result => {
                //  $(".alert_danger").removeClass("hide");
                setTimeout(function () {
                    $(".loading").css("visibility", "hidden");
                    if (result.success) {
                        window.location.replace(result.redirect_url);
                    } else {
                        $(".alert_danger").html(result.msg);
                        $(".alert_danger").css("visibility", "visible");
                    }
                    console.log(result);
                }, 3000);

                //$(this).parent().find('.search_result').html(result.response);
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });

    $("#forget_form").submit(function (e) {

// $(document).on("submit", "#login_form", function () {

        $("#forget_form .loading").css("visibility", "visible");
        $("#forget_form .alert_danger").css("visibility", "hidden");
        const data = $(this).serialize();

        fetch(admin_url + "/pass_req", {
            method: 'post', // or 'PUT'
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: data
        })
            .then(response => response.json())
            .then(result => {
                //  $(".alert_danger").removeClass("hide");
                setTimeout(function () {
                    $("#forget_form .loading").css("visibility", "hidden");
                    if (result.error > 0) {
                        $(" #forget_form .alert_danger").html(result.msg);
                        $(" #forget_form .alert_danger").css("visibility", "visible");

                    } else {
                        $(" #forget_form .alert_danger").html(result.msg);
                        $(" #forget_form .alert_danger").css("visibility", "visible");

                    }
                    console.log(result);
                }, 3000);

                //$(this).parent().find('.search_result').html(result.response);
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });

    $(".send_status_update_email").on("click", function () {
        $status_select = $(this).closest('td').find("select").val();
        $customer_id = $(this).closest('td').find("select").data('id');

        const data = {status_notification: $status_select, customer_id: $customer_id};
        fetch(admin_url + "/orders/send_status_update", {

            method: 'post', // or 'PUT'
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams(data).toString()
        })
            .then(response => response.json())
            .then(result => {
                if(result.type == 'success') {
                    Swal.fire({
                        title: "Success",
                        toast: true,
                        position: 'top-end',
                        timer: 4000
                    })
                }else {
                    Swal.fire({
                        title: "Error",
                        toast: true,
                        position: 'top-end',
                        timer: 4000
                    })
                }

                //$(this).parent().find('.search_result').html(result.response);
            })
        ;
    });

    $("#restpwd_form").submit(function (e) {
// $(document).on("submit", "#login_form", function () {
        $("#restpwd_form .loading").css("visibility", "visible");
        $("#restpwd_form .alert_danger").css("visibility", "hidden");
        const data = $(this).serialize();

        fetch(admin_url + "/pass_request", {
            method: 'post', // or 'PUT'
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: data
        })
            .then(response => response.json())
            .then(result => {

                setTimeout(function () {
                    if (result.error == '0') {

                        $("#restpwd_form .loading").css("visibility", "hidden");
                        $(" #restpwd_form .alert_danger").css("visibility", "visible").html(result.msg);
                        setTimeout(function () {
                            window.location.replace(result.redirect_url);
                        }, 3000);
                    } else {
                        $(" #restpwd_form .alert_danger").html(result.msg);
                        $(" #restpwd_form .alert_danger").css("visibility", "visible");
                    }
                    console.log(result);
                }, 3000);

                //$(this).parent().find('.search_result').html(result.response);
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });

    window.tabLinksInit = function() {
        // Reference the tab links.
        const tabLinks = $('#tab-links li a');
        // Handle link clicks.
        tabLinks.click(function (event) {
            event.preventDefault();
            var $this = $(this);
            var scrollY = window.scrollY;
            // Prevent default click behaviour.
            //location.hash = $this.attr('href');
            // Remove the active class from the active link and section.
            $('#tab-links a.active, section.active').removeClass('active');
            // Add the active class to the current link and corresponding section.
            $this.addClass('active');
            $($this.attr('href')).addClass('active');
            if(scrollY) {
                // setTimeout(()=>{window.scrollTo(scrollY,0)},1);
            }
            var curr_url = location.href.split('#');
            var new_url = curr_url[0]+$this.attr('href');
            $('[data-tab-current-url]').val(new_url);
            history.pushState({}, '', $this.attr('href'));
        });

        if(location.hash) {
            $('#tab-links li').find('[href="'+location.hash+'"]').click();
        }
    }

    tabLinksInit();

    // $('select').each(function () {
    //     if (typeof $(this).attr('value') !== "undefined" && $(this).attr('value')) {
    //         const values = this.value.split(',');
    //
    //         $(this).val($(this).attr('value'));
    //
    //     }
    // });

    //Fix unchecked input posts
    input_checkbox_empty_fix();
    $(document).on('click', 'input[type=checkbox]', function() {
        input_checkbox_empty_fix(this);
    })
});

function del_item($url, $join = false) {

    var row_id = $url.substr($url.lastIndexOf('/') + 1);

    $(".table_row_" + row_id).css({'background': '#dd33336b'});
    Swal.fire({
        title: 'Are you sure to perform this action?',
        showCancelButton: true,
        cancelButtonColor: ' #4143448',
        confirmButtonText: 'Yes',
        confirmButtonColor: '#d33',
        showLoaderOnConfirm: true,
        preConfirm: (login) => {
            return fetch($url)
                .then(response => {
                    console.log(response);
                    if (!response.ok) {
                        throw new Error(response.statusText)
                    }
                    return response.json()
                })
                .catch(error => {
                    Swal.showValidationMessage(
                        `Request failed: ${error}`
                    )
                })
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title:'Deleted!',
                text: 'Record has been deleted',
                icon:'success',
                showConfirmButton:false,
                showCloseButton:false,
                allowOutsideClick:false
            });
            setTimeout(()=>{
                location.reload();
            },2000);
        } else {
            $(".table_row_" + row_id).removeAttr('style');
        }

    });
}

if(document.querySelectorAll('select[value]').length) {
    document.querySelectorAll('select[value]').forEach((select)=>{
        const val = select.getAttribute('value');
        this.value = val;
    });
}

function remove_product_media(_this) {
    let img_input = $(_this).closest('.upload_media_images').find('.media_input');
    const parent = $(_this).closest('.upload_media_images');
    let fileID = $(_this).data('id');

    if(img_input.val()) {
        let input_vals = img_input.val().split(',');
        for(let i in input_vals) {
            if(input_vals[i] === fileID.toString()) {
                delete input_vals[i];
            }
        }

        input_vals = input_vals.filter(n=>n);

        // $(parent).find('.media_input').val(input_vals.join(',').trim());
    }else {
        //$(parent).find('.media_input').val('');
    }

    $(_this).parent().remove();
}

function media_browser_select_file(fileID,_this) {
    let gall_images = $(_this).closest('.upload_media_images').find('.gallery-images');
    let is_multiple = $(_this).data('multiple') || false;

    gall_images.html('');

    const ipt_name = gall_images.data('inputname') || '';

    window.selected_media_files = [];

    fetch(admin_url+'/media-library/media_path_by_id/'+fileID).then(request=>request.text()).then((imgpath)=>{
        if($(_this).data('editor')) {
            const textareaContext = '<img src="'+imgpath+'" width="300">';
            const editorID = $(_this).data('editor');
            if(typeof CKEDITOR !== "undefined") {
                CKEDITOR.instances[editorID].insertHtml(textareaContext);
            }
            if(typeof tinymce !== "undefined") {
                const eleID = editorID.replace('#','');
                tinymce.get(eleID).execCommand('mceInsertContent', false, textareaContext);
            }
        }
        else {
            if($('.upload_media_images_gallery').find('#media-library-selected-ids').length) {
                const inputvals = $('.upload_media_images_gallery').find('#media-library-selected-ids').val();
                //$(_this).closest('.upload_media_images').find('.media_input').val(inputvals);
            }
        }
        if(is_multiple) {
            gall_images.append(`<div class="media-slide"><div onclick="remove_product_media(this)" class="lni lni-cross-circle del-image" data-id="${fileID}"></div> <div><img class="media_image" src="${imgpath}" width="100%" height="100%"><input type="hidden" name="${ipt_name}[]" value="${fileID}" class="media_input"></div></div>`);
        }else {
            gall_images.html('');
            gall_images.append(`<div class="media-slide"><div onclick="remove_product_media(this)" class="lni lni-cross-circle del-image" data-id="${fileID}"></div> <div><img class="media_image" src="${imgpath}" width="100%" height="100%"></div><input type="hidden" name="${ipt_name}[]" value="${fileID}" class="media_input"></div>`);
        }

        // gall_images.find('.media-slide').each(function() {
        //     $(this).sortable({
        //         revert: true
        //     });
        // });

        selected_media_files.push(imgpath);
    });

    Swal.close();
}

// function media_library_click_handle() {
//     let is_multiple = $(browse_media_target).data('multiple') || false;
//     $(document).on('click',function(e) {
//         if(!$(e.target).hasClass('media-row') && !$(e.target).closest('.media-row').length) {
//             // $('.media-library-viewer .library-view > .media-row').removeClass('selected');
//         }
//     });

//     $('.swal2-container .media-library-viewer .library-view .media-row').on('click', function(e) {
//         if(e.target.nodeName === "IMG" || e.target.nodeName === "INPUT" || $(this).hasClass('copyText')) {
//             return;
//         }
//         if(!is_multiple) {
//             $('.media-library-viewer .library-view > .media-row').removeClass('selected');
//         }

//         const _this_id = $(this).data('id');
//         const _this_id_str = _this_id.toString();

//         $(this).toggleClass('selected');

//         let selectedvals;

//         if(is_multiple) {
//             selectedvals = $(this).closest('.media-library-viewer').find('#media-library-selected-ids').val();
//             selectedvals = selectedvals.split(',');

//             if($(this).hasClass('selected')) {
//                 if(selectedvals.indexOf(_this_id_str) === -1) {
//                     selectedvals.push(_this_id_str);
//                 }
//             }else {
//                 if(selectedvals.indexOf(_this_id_str) !== -1) {
//                     selectedvals = selectedvals.filter((val)=>{
//                         return val !== _this_id_str;
//                     });
//                 }
//             }
//             selectedvals = selectedvals.filter((val)=>{
//                 return val !== "";
//             });
//         }else {
//             selectedvals = [_this_id_str];
//         }

//         if(selectedvals.length) {
//             $('.upload_media_images_gallery .gallery_footer').addClass('open');
//         }else {
//             $('.upload_media_images_gallery .gallery_footer').removeClass('open');
//         }

//         let item_text = 'items';
//         if(selectedvals.length == 1) {
//             item_text = 'item';
//         }



//         $('.upload_media_images_gallery').find('#item_selected_count').text(selectedvals.length+' '+item_text+' selected');

//         $('.media-library-viewer').find('#media-library-selected-ids').val(selectedvals.join(','));
//     });


//     $('.upload_media_images_gallery .gallery_footer > .back').off('click').on('click', function() {
//         let selectedvals = $('.upload_media_images_gallery').find('#media-library-selected-ids').val();
//         // let old_vals = $(browse_media_target).closest('.upload_media_images').find('.media_input').val();
//         // selectedvals = old_vals + ',' + selectedvals;
//         //  selectedvals = selectedvals.split(',').filter((value, index, array)=>{return array.indexOf(value) === index;});

//         selectedvals = selectedvals.split(',');

//         if(selectedvals) {
//             selectedvals.forEach((selectedID) => {
//                 media_browser_select_file(selectedID, browse_media_target);
//             });
//         }
//     });
// }

function media_library_click_handle() {
    let is_multiple = $(browse_media_target).data('multiple') || false;

    // Handle click events for media rows
    $(document).on('click', '.swal2-container .media-library-viewer .library-view .media-row', function(e) {
        console.log('Media row clicked:', $(this).data('id'));

        // Skip selection for image or input clicks, or if clicking on 'copyText'
        if (e.target.nodeName === "IMG" || e.target.nodeName === "INPUT" || $(this).hasClass('copyText')) {
            console.log('Ignored click on image/input or copyText');
            return;
        }

        // Deselect all if not in multiple selection mode
        if (!is_multiple) {
            $('.media-library-viewer .library-view > .media-row').removeClass('selected');
            console.log('Deselected all media rows');
        }

        const _this_id = $(this).data('id');
        const _this_id_str = _this_id.toString();
        console.log('Current row ID:', _this_id_str);

        $(this).toggleClass('selected');
        console.log('Row selected state:', $(this).hasClass('selected'));

        let selectedvals;

        if (is_multiple) {
            // Retrieve currently selected values
            selectedvals = $(this).closest('.media-library-viewer').find('#media-library-selected-ids').val();
            console.log('Selected IDs before update:', selectedvals);
            selectedvals = selectedvals.split(',');

            // Update selected values based on current selection
            if ($(this).hasClass('selected')) {
                if (selectedvals.indexOf(_this_id_str) === -1) {
                    selectedvals.push(_this_id_str);
                    console.log('Added ID to selected:', _this_id_str);
                }
            } else {
                if (selectedvals.indexOf(_this_id_str) !== -1) {
                    selectedvals = selectedvals.filter((val) => val !== _this_id_str);
                    console.log('Removed ID from selected:', _this_id_str);
                }
            }
            selectedvals = selectedvals.filter((val) => val !== ""); // Remove any empty values
        } else {
            selectedvals = [_this_id_str]; // Single selection
            console.log('Single selection:', selectedvals);
        }

        // Update footer visibility based on selected items
        if (selectedvals.length) {
            $('.upload_media_images_gallery .gallery_footer').addClass('open');
            console.log('Footer opened, selected items:', selectedvals);
        } else {
            $('.upload_media_images_gallery .gallery_footer').removeClass('open');
            console.log('Footer closed, no items selected');
        }

        // Update selected count text
        let item_text = selectedvals.length === 1 ? 'item' : 'items';
        $('.upload_media_images_gallery').find('#item_selected_count').text(selectedvals.length + ' ' + item_text + ' selected');
        console.log('Selected count updated:', selectedvals.length + ' ' + item_text + ' selected');

        // Store selected IDs
        $('.media-library-viewer').find('#media-library-selected-ids').val(selectedvals.join(','));
        console.log('Media library selected IDs updated:', selectedvals.join(','));
    });

    // Handle the back button click
    $('.upload_media_images_gallery .gallery_footer > .back').off('click').on('click', function() {
        let selectedvals = $('.upload_media_images_gallery').find('#media-library-selected-ids').val();
        console.log('Back button clicked, selected IDs:', selectedvals);
        selectedvals = selectedvals.split(',');

        if (selectedvals) {
            selectedvals.forEach((selectedID) => {
                console.log('Processing selected ID for media browser select:', selectedID);
                media_browser_select_file(selectedID, browse_media_target);
            });
        }
    });
}


window.browse_media_target = null;

media_popup_open = (_this = '')=>{
    browse_media_target = _this;

    window.selected_media_files = [];

    $('.upload_media_images_gallery').remove();
    let selectedMedias = [];

    _this.closest('.upload_media_images').querySelectorAll('.gallery-images .media_input').forEach((input)=>{
        selectedMedias.push(input.value);
    });

    selectedMedias = selectedMedias.join(',');

    return Swal.fire({
        title: 'Media Browser',
        showCancelButton: false,
        showConfirmButton: false,
        showCloseButton:true,
        showClass: {
            popup: 'animated windowIn'
        },
        hideClass: {
            popup: 'animated windowOut'
        },
        didOpen: () => {
            Swal.showLoading();
            fetch(admin_url+'/media-library-frame?selected='+selectedMedias).then(request=>request.text()).then((html)=>{
                let media_gallery_html = `<div class="upload_media_images_gallery"> 
                    <div class="gallery_images">${html}</div> 
                </div>`;
                Swal.fire({
                    title: 'Media Browser',
                    showCancelButton: false,
                    showConfirmButton: false,
                    showCloseButton:true,
                    width:960,
                    html: media_gallery_html,
                    didOpen: () => {
                        media_library_click_handle();
                    },
                    showClass: {
                        popup: 'animated windowIn'
                    },
                    hideClass: {
                        popup: 'animated windowOut'
                    },
                    customClass: {
                        container: 'media-browser-popup'
                    }
                });

            });
        }
    });
}

$(document).on('click','.upload_media_images .browse_media', function(e) {
    e.preventDefault();
    media_popup_open(this);
});

$(function() {
    $('.upload_media_images').find('.gallery-images').each(function() {
        const rid = '_'+Math.random().toString().substring(5);
        this.id = rid;
        const parent = this;
        //  new Sortable(this,{
        //           invertSwap: true,
        //           handle: parent
        // });
        Sortable.create(this,{
            animation: 150
        });
    });
})

window.mediaBrowserPagination = (i,_this)=>{
    let selectedIDs = $(_this).closest('.library-view').find('#media-library-selected-ids').val();
    let url = admin_url+'/media-library-frame?page='+i+'&selected='+selectedIDs;
    $('.media-gallery-rows').html('<div style="text-align: center; width: 100%"><img width="100" src="'+site_url+'/assets/images/loader-2.svg"></div>');
    $.get(url, function(data) {
        let html = $(data).find('.media-gallery-rows').html();
        $('.media-gallery-rows').html(html);
        $('.pagination a').removeClass('active');
        $(_this).addClass('active');
        if(!$(".swal2-container").length) {
            history.pushState('page','page','?page='+i);
        }

        media_library_click_handle();
    });
}

window.search_gallery_media = (form)=>{
    let url = admin_url+'/media-library-frame?q='+form.search[0].value;
    $('.media-gallery-rows').html('<div style="text-align: center; width: 100%"><img width="100" src="'+site_url+'/assets/images/loader-2.svg"></div>');
    $.get(url, function(data) {
        let html = $(data).find('.media-gallery-rows').html();
        $('.media-gallery-rows').html(html);
        $('.pagination a').removeClass('active');

        media_library_click_handle();
    });
    return false;
}

function mediaLibClipboard() {
    $(document).on('click','.copyText', function(a) {
        a.preventDefault();
        const text = this.getAttribute('data-text');
        navigator.clipboard.writeText(text);
        if(!$(this).closest('.upload_media_images_gallery').length) {
            Swal.fire({
                'title':"'"+text+"'"+' copied to clipboard',
                toast:true,
                showConfirmButton:false,
                showCloseButton:false,
                timer:2000,
                position:'top'
            });
        }else {
            alert("'"+text+"'"+' copied to clipboard');
        }
    })
}
mediaLibClipboard();

function browse_media_delete() {
    $(document).on('click','.media-remove', function(a) {
        a.preventDefault();
        const _this = this;
        const page = $(this).data('page');

        if(confirm('Are you sure to delete this image')) {
            const url = admin_url+'/media-library/delete-media/'+$(this).data('id');
            $(this).closest('.media-row').addClass('loading');
            $.get(url,function(data) {
                data = JSON.parse(data);
                alert(data.msg);
                if(data.success) {
                    $(_this).closest('.media-row').remove();
                }
            });
        }
    });
}

browse_media_delete();


function browse_media_sidebar_open() {
    $('.browse_media_sidebar_container').remove();
    let sidebarHTML = `
            <div class="browse_media_sidebar_container swal2-container">
                <div id="browse_media_sidebar_close" class="btn button_red btn-submit btn back"><i class="lni lni-cross-circle"></i> Close</div>
                <div class="upload_media_images_gallery">
                    <img id="loadingdata" width="100" src="${site_url}/assets/images/loader-2.svg">
                </div>
            </div>`;
    let url = admin_url+'/media-library-frame?page=1';
    $.get(url, function(data) {
        $container = $('.browse_media_sidebar_container');
        let html = $(data);
        $container.find('.upload_media_images_gallery').html(html);
        $('.pagination a').removeClass('active');
    });
    $('body').append(sidebarHTML).addClass('browse_media_sidebar_open');
}

function browse_media_sidebar_close() {
    $('body').addClass('sidebar_closing');
    setTimeout(()=>{
        $('.browse_media_sidebar_container').remove();
        $('body').removeClass('sidebar_closing browse_media_sidebar_open');
    },100);
}

function media_gallery_upload() {
    $(document).on('submit', '.media_gallery_upload', function(e) {
        e.preventDefault();
        const url = this.getAttribute('action');
        const formData = new FormData(this);

        $('#upload_media_progress').remove();
        $(this).after('<div id="upload_media_progress"><progress value="0" max="100"></progress></div>');

        $.ajax({
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = (evt.loaded / evt.total) * 100;
                        $('#upload_media_progress > progress').attr('value', percentComplete);
                    }
                }, false);
                return xhr;
            },
            url: url,
            type: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            success: function(result) {
                result = JSON.parse(result); // Parse JSON string to object
                if (result.success == 1) {
                    // alert(result.message); // Show success message
                    // location.reload(true); // Reloads the page from the server
                    let url = admin_url + '/media-library-frame?q=';
                    // $('.media-gallery-rows').html('<div style="text-align: center; width: 100%"><img width="100" src="'+site_url+'/assets/images/loader-2.svg"></div>');
                    $.get(url, function(data) {
                        let html = $(data).find('.media-gallery-rows').html();
                        $('.media-gallery-rows').html(html);
                        $('.pagination a').removeClass('active');

                        // Clear form inputs
                        $('.media_gallery_upload')[0].reset();
                
                        // media_library_click_handle();
                    });
                } else {
                    alert(result.message); // Show error message
                }
            },
            error: function(xhr, status, error) {
                alert("An error occurred while uploading: " + error); // Handle AJAX error
            },
            complete: function() {
                $('#upload_media_progress').remove(); // Remove progress bar when complete
            }
        });
    });
}


media_gallery_upload();

$(document).on('click','.browse_media_sidebar', function() {
    browse_media_sidebar_open();
});

$(document).on('click','#browse_media_sidebar_close', function() {
    browse_media_sidebar_close();
});

$(function() {
    $('.context li').click(()=>{
        $('[id*=context_menu_]').removeClass('active');
    });
    $('body').on('click', function(e) {
        if(!$(e.target).closest('.context').length && !$(e.target).closest('.menu-toggle').length) {
            $('[id*=context_menu_]').removeClass('active');
        }
    });

    $(window).on('scroll', function() {
        const winTop = $(window).scrollTop();
        $('.sticky-container').each(function() {
            const top = winTop - $(this).offset().top;
            const ele = $(this).children('.sticky-element');
            if(top > -60) {
                if(!$(this).hasClass('sticky')) {
                    $(this).addClass('sticky');
                }
                ele.css({top: top + 50});
            }
            else {
                if($(this).hasClass('sticky')) {
                    $(this).removeClass('sticky');
                    ele.css({top: 0});
                }
            }
        });
    })
});