
<script>var admin_url = "<?php echo base_url(ADMIN) ?>";</script>
<script>var site_url = "<?php echo base_url() ?>";</script>
<script type="text/javascript" src="<?php echo base_url('/assets/admin/js/custom.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('/assets/javascript/custom.js') ?>"></script>

<!---->
<!--<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.13.1/sweetalert2.min.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/rowreorder/1.4.1/js/dataTables.rowReorder.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/dataTables.semanticui.min.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>


<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.semanticui.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.4.1/css/rowReorder.dataTables.min.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<script src="<?php echo base_url('/assets/javascript/Sortable.min.js') ?>"></script>



<?php
/*<script src="//cdn.ckeditor.com/4.20.0/full/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/ckeditor-autogrow@1.0.0/plugin.js"></script>
<script src="<?php echo base_url('/assets/javascript/ckeditor/plugins/imageresizerowandcolumn/plugin.js') ?>"></script>*/

echo view("admin/includes/file_manager_script");
?>


<?php
/*<!--<script src="https://cdn.jsdelivr.net/npm/tinymce@6.3.0/tinymce.min.js" referrerpolicy="origin"></script>-->



<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.12.17/grapes.min.js"></script>-->
<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.12.17/css/grapes.min.css">-->
<!--<script src="--><?php //echo base_url('/assets/admin/js/grapesjs-preset-webpage.min.js') ?><!--"></script>-->
<!--<script src="--><?php //echo base_url('/assets/admin/js/grapesjs-blocks-bootstrap4.min.js') ?><!--"></script>-->

<!--<script src="--><?php //echo base_url('/assets/admin/js/grapejs-config.js') ?><!--"></script>-->*/
?>

<script src="<?php echo base_url('/assets/admin/ckeditor/ckeditor.js') ?>"></script>

<script src="<?php echo base_url('/assets/admin/js/ckeditor-config.js') ?>"></script>

<script>
    $(document).ready(function () {
        if(typeof DataTable !== "undefined") {
            $('.data_table').each(function() {
                let table =  $(this);
                let options = {
                    "dom": '<"table-top" fil>rt<"table-bottom" p><"clear">',
                    pageLength: 50,
                    searchDelay: 600,
                    lengthMenu: [
                        [25,50,75,100,-1],
                        [25, 50, 75,100, 'All']
                    ]
                };
                if(table.data('remote')) {
                    options.ajax = table.data('remote');
                    options.processing = true;
                    options.serverSide = true;
                }
                if(table.data('draggable')) {
                    options.rowReorder = true;
                }
                if(table.data('orderable') === false) {
                    options.ordering = false;
                }
                if(table.data('search') === false) {
                    options.searching = false;
                }

                if(typeof table.data('sortcol') !== "undefined") {
                    options.order = [[table.data('sortcol'), table.data('sortorder')]];
                }

                <?php
                if(isset($_GET['sort-orders']) && isset($_GET['sort-cols'])) {
                $sort_ords = explode(',',$_GET['sort-orders']);
                $sort_cols = explode(',',$_GET['sort-cols']);
                $ord_arr = [];
                foreach($sort_cols as $i=>$col) {
                    $ord = $sort_ords[$i];
                    $ord_arr[] = [intval($col), $ord];
                }
                $ord_arr = json_encode($ord_arr);
                ?>
                options.order = <?php echo $ord_arr ?>;
                <?php
                }
                ?>

                window.dtable = table.DataTable(options);

                if(table.data('filter')) {
                    table.data('filter').split(',').forEach((id)=>{
                        $('#'+id).on('change', function() {
                            dtable.destroy();
                            options.ajax += '&'+id+'='+this.value;
                            dtable = table.DataTable(options);
                            const url = new URL(location);
                            url.searchParams.set(id,this.value);

                            history.pushState('', document.title, url);
                        });
                        $('#'+id).trigger('change');
                    });
                }

                if(table.data('draggable') && table.data('onreorder')) {
                    dtable.on('row-reorder', function (e, diff, edit) {
                        const fn = new Function(table.data('onreorder'));
                        fn();
                    });
                }

                <?php
                if(!empty($_GET['search']) && !is_array($_GET['search'])) {
                $s = $_GET['search'];
                ?>
                dtable.search('<?php echo $s ?>').draw();
                <?php
                }
                ?>


                dtable.on('draw', function () {
                    const url = new URL(location);
                    const sortings = dtable.settings()[0].aaSorting;
                    const page = dtable.page();
                    const searchInput = $('#books_table_filter [type="search"]').val();
                    let sort_cols = [];
                    let sort_orders = [];
                    const hasSorting = url.searchParams.has('sort-cols');
                    sortings.forEach((sort)=>{
                        sort_cols.push(sort[0]);
                        sort_orders.push(sort[1]);
                    });
                    sort_cols = sort_cols.join(',');
                    sort_orders = sort_orders.join(',');

                    url.searchParams.set("sort-cols", sort_cols);
                    url.searchParams.set("sort-orders", sort_orders);

                    url.searchParams.set("page", page);

                    if(searchInput) {
                        url.searchParams.set("search", searchInput);
                    }

                    history.pushState('page'+page, document.title, url);
                });

                dtable.on('init.dt', ()=> {
                    $('table.dataTable').each(function() {
                        $(this).find('input.checkall').on('change', function() {
                            if(this.checked) {
                                $(this).closest('table').find('.checkrow').prop('checked','checked');
                            }else {
                                $(this).closest('table').find('.checkrow').prop('checked',false);
                            }
                        })
                    });
                    $('[name="books_table_length"]').select2({
                        minimumResultsForSearch: -1
                    });
                })
            });
        }
    });
    $(document).on("click", ".edit_faq", function () {
        // $(this).closest('tr').hide();
        // $(this).closest('tr').prev().show().find(".save_row");
        $(this).closest('tr').find('.view').hide();
        $(this).closest('tr').find('.edit').show();
    });

    $(document).on("click", ".save_row", function (e) {
        // var form_data = $(this).closest('form').serialize();
        e.preventDefault();

        var inputs = $(this).closest('tr').find('input,select,textarea');
        //  console.log($(inputs).text());
        var form_data = inputs.serialize();

        fetch("<?php echo  base_url(ADMIN).'/faqs' ?>",
            {
                method: "POST",
                // whatever data you want to post with a key-value pair
                body:form_data,
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },

            }).then(res=>res.json()).then(function(res) {
            $row_id = res.id.trim();

            if(typeof  res.id > "0"){

                for (let x in res.data) {

                    console.log("#table_row_"+$row_id+" ."+x);
                    $("#faqs_table").find("#table_row_"+$row_id+" ."+x).text(res.data[x]);
                    // $($table_row).find(' > td').remove();
                }



                // $("#faqs_table").find("#text_row_"+$row_id);
                // $("#faqs_table").find("#text_row_"+$row_id).hide();
                $table_row =  $("#faqs_table").find("#table_row_"+$row_id);

                $($table_row).closest('tr').find('.view').show();
                $($table_row).closest('tr').find('.edit').hide();

                // $($table_row).find(' > td').remove();
                // $($table_row).html(res.data).show();

            }

        });


    });

</script>
<script>
    function openNav() {
        document.getElementById("mySidebar").style.width = "80%";
        //document.getElementById("main").style.marginLeft  = "250px";
    }

    function closeNav() {
        document.getElementById("mySidebar").style.width = "0";
        document.getElementById("main").style.marginLeft = "0";
    }

    var width_find = true;
    $(window).on("load resize scroll ", function (e) {
        var newWidth = ($(window).width());
        if (newWidth <= 576) {
            if (width_find) {
                $("#mySidebar").addClass('sidebar');
                document.getElementById("mySidebar").style.width = "0%";
                width_find = false;
                console.log(newWidth);
            }


        } else {
            width_find = true;
            $("#mySidebar").removeClass('sidebar');
        }

    });
    window.onresize = function () {

    }

    $('#js-example-basic-hide-search-multi').select2();

    $('#js-example-basic-hide-search-multi').on('select2:opening select2:closing', function( event ) {
        var $searchfield = $(this).parent().find('.select2-search__field');
        $searchfield.prop('disabled', true);
    })


    $(document).ready(function()
    {
        var slider_width = $('.pollSlider').width();//get width automaticly
        $('#pollSlider-button').click(function() {
            if($(this).css("margin-right") == slider_width+"px" && !$(this).is(':animated'))
            {
                $('.pollSlider,#pollSlider-button').animate({"margin-right": '-='+slider_width});
            }
            else
            {
                if(!$(this).is(':animated'))//perevent double click to double margin
                {
                    $('.pollSlider,#pollSlider-button').animate({"margin-right": '+='+slider_width});
                }
            }


        });
    });



</script>

</body>
</html>