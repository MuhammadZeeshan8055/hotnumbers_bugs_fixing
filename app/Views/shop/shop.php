<?php echo view( 'includes/header');?>
<style>
    .row > * {
        flex-shrink: 0;
        width: 100%;
        max-width: 100%;
        padding-right: unset;
        padding-left: unset;
        margin-top: unset;
    }
    .header-caption {
        padding: 40px 0 22px;
        text-align: center;
        max-width: 90%;
        margin: auto;
        display: table;
        font-size: 1.2em;
    }
    .container {
        display: flex; /* Flexbox for layout */
        flex-direction: column; /* Arrange items in a column */
    }
    .category-container {
        display: flex; /* Flexbox for category and products */ 
        flex-wrap:wrap;
        margin-bottom: 20px; /* Space between categories */
        border-bottom: 1px solid #ddd; /* Border for separation */
        max-width:100%;
        gap:10px;
    }
    .category {
        width: 29%; /* Width for the category column */
    }

    .category ul.products li {
        width: 100% !important;
    }

    .products-listing {
        width: 70%; /* Width for the products column */
    }
    

    /* modal  */
    .modal {
        color: black;
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1000; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflfdsaow: auto; /* Enable scroll if needed 
        background-color: rgba(0, 0, 0, 0.7); /* Black w/ opacity */
        padding-top: 60px; /* Padding at the top */
    }

    .modal-content {
        background-color: #fefefe;
        margin: auto; /* Center horizontally */
        padding: 20px;
        border: 1px solid #888;
        width: 80%; /* Width of the modal */
        max-width: 500px; /* Maximum width of the modal */
        border-radius: 5px;
        position: relative; /* Needed for absolute positioning of children */
        top: 50%; /* Center vertically */
        transform: translateY(-50%); /* Adjust for half the height of the modal */
    }

    .close {
        color: #aaa;
        font-size: 28px;
        font-weight: bold;
        margin-left: auto; /* Push close button to the right */
        cursor: pointer; /* Change cursor to pointer */
        padding: 0 0 10px 0; /* Add padding for better spacing */
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
    }


    /* testing  */
    
    ul.category-links {
        list-style: none;
        display: flex;
        /*align-items: center;*/
        /*flex-wrap:wrap;*/
        gap: 30px;
        text-align: center;
        overflow-x: auto; 
        width: 94%;
    }

    /* For Webkit browsers (Chrome, Safari) */
    ul.category-links::-webkit-scrollbar {
        width: 12px; /* Width of the scrollbar */
    }

    ul.category-links::-webkit-scrollbar-track {
        background: #f1f1f1; /* Background of the scrollbar track */
    }

    ul.category-links::-webkit-scrollbar-thumb {
        background: var(--red); /* Color of the scrollbar thumb */
        border-radius: 10px; /* Round edges of the thumb */
    }

    /* For Firefox */
    ul.category-links {
        scrollbar-width: thin; /* Makes the scrollbar thin */
        scrollbar-color: var(--red) #f1f1f1; /* thumb color and track color */
    }
    
    .category-image{
        border-radius: 50%;
        height: 100px !important;
        width: 108px;
    }
    .category-title{
        flex-wrap: wrap;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .categories-list {
        transition: top 0.3s; /* Smooth transition */
    }

    /*.sticky {*/
    /*    position: fixed;*/
    /*    top: 0px;*/
    /*    background-color: #000;*/
    /*    color:white;*/
    /*    z-index: 12000;*/
    /*    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);*/
    /*    width: 100%;*/
    /*    margin-right: auto;*/
    /*    margin-left: auto;*/
    /*    clear: both;*/
    /*    max-width: 1100px;*/
    /*    padding: 3em 0em 0em 0;*/
    /*    height: auto;*/
    /*}*/
    
    .sticky {
        position: fixed;
        top: 0px;
        background-color: #000;
        color: white;
        z-index: 100;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        width: 100%;
        margin-right: auto;
        margin-left: auto;
        clear: both;
        max-width: 1100px;
        padding: 3em 0em 0em 0; /* Top padding */
        height: auto; 
        display: flex;             /* Add Flexbox layout */
        flex-direction: column;    /* Stack children vertically */
        justify-content: space-between; /* Distribute space between children */
    }
    .category-links {
        padding-top: 80px;      /* Add some padding for spacing */
    }
    .category-image-wrapper {
        width: 100px;
        height: 100px;
        overflow: hidden;
    }

  
    .category-container.active {
        background-color: black;
        /* z-index: 1300; */
        padding-top: 38%;
    }
    a.category-link.active{
        text-decoration:none;
    }
    a.category-link.active p.category-title {
        color: var(--red) ! Important;
        font-weight: bold;
    }
    /* .modal-image-wrapper {
        height: 300px;
        width: 458px;
        overflow: hidden;
    } */

    
    @media screen and (max-width: 1024px) {
        .category {
            width: 50%; /* Width for the category column */
        }
        .products-listing {
            width: 100%;
        }
        .products.column-2 li {
            width: 48% !important;
        }
        .category-container.active {
            padding-top:40%;
        }
        
    }
    @media screen and (max-width: 768px) {
       
        .category-container.active {
            padding-top:60%;
        }
        .sticky {
            margin-left: -48px;
        }
    }
    @media screen and (max-width: 480px) {
        .category-container {
            flex-wrap:wrap;
            flex-direction: column;
        }
        .category {
                width: 100%; /* Width for the category column */
        }
        .products-listing {
                width: 100%; /* Width for the products column */
        }
        .woocommerce .shop_container ul.products li{
            width: 100% !important;
        }
        .sticky {
            margin-left: -24px;
        }
        .category-container.active {
            padding-top:120%;
        }
    }
    @media screen and (max-width: 375px) {
        .category-container.active {
            padding-top:150%;
        }
    }
</style>
<!---Main body ----->
<div class="underbanner" style="background-image:url(<?php echo base_url('./assets/images/shop/banner.jpg') ?>);" > </div>
<div id="shop" class="woocommerce wrapper content-area">
<div class="container home-container" role="main">
<nav class="woocommerce-breadcrumb"><a href="<?php echo base_url() ?>">Home</a>&nbsp;&#47;&nbsp;<?php echo @$title; ?></nav>
<div class="shop_container">
    <div class="header">
        <br>
        <div class="container">
            <?php if($list_type == 'category') { ?>
                <!-- Header of shopping -->
                <div class="pull-left">
                    <h1 style="text-transform: none;"><?php echo $title ?></h1>
                </div>
                <div class="pull-right">
                    <h5 style="margin-top: 32px;">Please select an item</h5>
                </div>

                <?php
                    $internal_user=is_internal();
                    $wholesaler_user=is_wholesaler();

                    if($internal_user || $wholesaler_user){ 

                ?>

                <!-- Categories Section -->
                <div class="categories-list" id="stickyCategories">
                    <ul class="category-links">
                        <?php foreach ($loop_data as $row) { ?>
                            <li>
                                <a href="#category-<?php echo $row['cid']; ?>" class="category-link">
                                    <div class="category-image-wrapper">
                                        <img class="category-image" src="<?php echo $row['image']; ?>">
                                    </div>
                                    <p class="category-title"><?php echo $row['title']; ?></p>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>

                <?php
                    }
                ?>

                <div class="clear"></div>
            <?php } ?>
            <?php if(!empty($caption)) { ?>
                <div class="header-caption">
                    <p><?php echo $caption ?></p>
                </div>
            <?php } ?>
        </div>
    </div>
   <div class="body woocommerce">
      <?php if(!empty($loop_data)){ ?>
      <div class="container">
        <?php
                    
            $contains = uri_string(); // shop/category/coffee
            $disable_style_and_products = (strpos($contains, 'shop/category/') === 0 && count(explode('/', $contains)) == 3);

                
            

        if(!$disable_style_and_products && $internal_user || $wholesaler_user){ 
        ?>

        <style>
        .products-listing ul {
            display: flex;
            flex-wrap: wrap; /* Allow items to wrap to the next line */
        }
        .products.column-2 li{
            width: 33% !important;
        }
        .shop_container .woocommerce ul.products li a {
            border: 2px solid transparent;
            transition: all 0.2s ease;
            display: block;
            height: 260px;
            /*overflow: hidden;*/
            position: relative;
        }
        .shop_container .woocommerce ul.products li a span {
            display: block;
            position: absolute;
            bottom: 0.5em;
            left: 0;
            width: 100%;
            text-align: center;
            color: #fff;
            height: auto;
            line-height: 1;
            font-weight: 500;
            padding: 0 16px;
            font-size: 32px;
        }
        .shop_container .woocommerce ul.products li {
            display: flex;
            flex-direction: column;
        }
        </style>
        
         
        <?php
            foreach($loop_data as $row)
            {
        ?>
        
                <div class='category-container' id="category-<?php echo $row['cid']; ?>">
                    <div class='category'>
                        <ul class="products">
                            <li>
                                <div>
                                    <a href="<?php echo site_url($row['url']) ?>">
                                        <img src="<?php echo $row['image'] ?> ">
                                       
                                        <span><?php echo $row['title']; ?></span>
                                    </a>
                                </div>
                            </li>
                        </ul>
                        <!-- <div>
                            <a href="<?php echo site_url($row['url']) ?>">
                                <img src="<?php echo $row['image'] ?> ">
                                       
                                <span><?php echo $row['title']; ?></span>
                            </a>
                        </div> -->
                    </div>
                    <div class='products-listing'>
                        <ul class="products column-2">
                            <?php if (!empty($row['products'])) {
                                foreach ($row['products'] as $product) { 
                                    $attributes = !empty($product['attributes']) && $product['type'] == "variable" ? json_decode($product['attributes'],true) : [];
                                   
                            ?>
                                
                                            <li>
                                                <div>
                                                    <a href="<?php echo site_url($product['url']) ?>">
                                                        <img src="<?php echo $product['image']; ?>">
                                                        <span><?php echo $product['title']; ?></span>
                                                    </a>
                                                </div>
                                               <button onclick="openModal('<?php echo $product['id']; ?>')">Add to Cart</button>
                                            </li>

                                            <!-- Modal -->
                                            <div id="modal-<?php echo $product['id']; ?>" class="modal">
                                                <div class="modal-content">
                                                    <span class="close" onclick="closeModal('<?php echo $product['id']; ?>')">&times;</span>

                                                    <!-- <table class="attributes">
                                                        <tbody>
                                                        <?php foreach($attributes as $attribute){
                                                            if(!empty($attribute['attribute_visibility']) && $attribute['attribute_visibility']==1) {
                                                                ?>
                                                                <tr>
                                                                    <th align="top" width="100"><?php echo $attribute['label'] ?></th>
                                                                    <td align="top"><?php echo !is_array($attribute['value']) ? $attribute['value'] : implode(' | ',$attribute['value']) ?></td>
                                                                </tr>
                                                            <?php }
                                                        }?>
                                                        </tbody>
                                                    </table> -->


                                                    <form class="variations_form cart validate" action="" method="post" enctype="multipart/form-data">
                                                        <div class="modal-image-wrapper">
                                                            <img src="<?php echo $product['image']; ?>" style="border-radius: 2px;margin-bottom: 10px;">
                                                        </div>

                                                            <h5><?php echo $product['title']; ?></h5>
                                                        
                                                        <table class="variations" cellspacing="0" style="">
                                                            <tbody>
                                                                <?php
                                                                $is_variation = false;
                                                                $err = false;
                                                                    if(!empty($attributes) && $product['type'] == "variable") {
                                                                        
                                                                        $var_list = [];
                                                                        if($product['type'] == "variable" && !empty($variation_arr)) {
                                                                            foreach($variation_arr as $variation) {
                                                                                foreach($variation['keys'] as $id=>$k) {
                                                                                    $k = empty($k) ? 'any': $k;
                                                                                    $var_list[$id][] = $k;
                                                                                }
                                                                            }
                                                                        }
                                                                        foreach($attributes as $attribute) {
                                                                            $label = $attribute['label'];
                                                                            $label_id = strtolower($label);
                                                                            $label_id = str_replace(' ','-',$label_id);
                                                                            if(!empty($attribute['attribute_variation'])) {
                                                                                $is_variation = true;
                                                                                ?>

                                                                                <tr>
                                                                                    <td width="150" height="60px" class="label"><?php echo ucfirst($label) ?> </td>
                                                                                    <td width="60%" height="60px">
                                                                                             <?php
                                                                                                if(!empty($attribute['value'])) {
                                                                                                    ?>
                                                                                                        <select class="form-control variation_select" name="variations[attribute_<?php echo $label_id ?>]" required data-error="Please select <?php echo $label ?>">
                                                                                                            <option selected value="">Select an option</option>
                                                                                                            <?php
                                                                                                            foreach($attribute['value'] as $i=>$variation) {
                                                                                                                $var_id = strtolower($variation);
                                                                                                                $var_id = str_replace(' ','-',$label_id);
                                                                                                                $name = 'attribute_'.$var_id;
                                                                                                                $var_val = $var_list[$name];

                                                                                                                
                                                                                                                    ?>
                                                                                                                        <option value="<?php echo $variation ?>"><?php echo $variation;
                                                                                                                    ?></option>
                                                                                                                    <?php
                                                                                                                    
                                                                                                            }?>
                                                                                                        </select>
                                                                                                    <?php
                                                                                                }
                                                                                            ?>
                                                                                    </td>
                                                                                </tr>
                                                                               


                                                                              
                                                                                <?php

                                                                            }
                                                                            
                                                                        }
                                                                    }
                                                                    else if(empty($product['stock']) && $product['stock_managed'] == 'yes') {
                                                                        $err = 'Out of stock';
                                                                    }else {
                                                                    ?>
                                                                        <script>
                                                                            $(function() {
                                                                                setTimeout(()=>{
                                                                                    $('#input_qunatity').trigger('change');
                                                                                },100);
                                                                            })
                                                                        </script>
                                                                        <?php
                                                                    }
                                                                    if(!$err && empty($product['sold_individually']) ) {
                                                                ?>
                                                                    <tr>
                                                                        <td width="150" height="60px" class="label">Quantity: </td>
                                                                        <td width="60%">
                                                                            <input id="input_qunatity" type="number" class="input-text qty text" step="1" min="1"  name="quantity" value="1" title="Qty" size="4" placeholder="" inputmode="numeric">
                                                                            <div id="status_message" class="text-right error_message"></div>
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <?php
                                                                            if($product['type'] == "variable"){
                                                                        ?>
                                                                            <td width="150" height="60px" class="label">Price: </td>
                                                                        <?php
                                                                            }
                                                                        ?>
                                                                        <td width="60%">
                                                                            
                                                                            <span class="product_price">
                                                                                
                                                                            </span>
                                                                        </td>
                                                                    </tr>

                                                                    <?php }
                                                                    ?>

                                                                    <tr>
                                                                        <td>
                                                                            <?php
                                                                            if($product['sold_individually']) {
                                                                            ?>
                                                                                <input type="hidden" name="quantity" value="1"></td>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                            <input type="hidden" name="product_id" value="<?php echo $product['id'] ?>"></td>
                                                                           
                                                                            
                                                                    </tr>
                                                                    <tr>
                                                                            <?php
                                                                                if($product['type'] != "variable"){
                                                                                    $price = isset($product['price']) && is_numeric($product['price']) ? (float)$product['price'] : 0.00;
                                                                                    $price_text = '<span class="woocommerce-Price-currencySymbol">'._price(number_format($price, 2)).'</span>';
                                                                                ?>
                                                                                    <td width="150" height="60px" class="label">Price: </td>
                                                                                    <td>
                                                                                        <span id="product_price">
                                                                                            <?=$price_text?>
                                                                                        </span>
                                                                                    </td>
                                                                                <?php
                                                                                }
                                                                            ?>
                                                                    </tr>
                                                            </tbody>
                                                        </table>

                                                        
                                                        <div class="pt-25"></div>
                                                        
                                                        <div class="row">
                                                            <div class="col-md-6 d-flex align-items-center justify-content-start">
                                                                <!-- You can add additional actions or links here, if necessary -->
                                                            </div>
                                                            <div class="col-md-6 justify-content-end d-flex">
                                                                <button type="submit" class="single_add_to_cart_button button alt">Add to Cart</button>
                                                            </div>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>

                                          

                            <?php
                                    
                                }
                            } else { ?>

                                <li>No products found in this category.</li>

                            <?php } ?>
                        </ul>
                    </div>
                </div>

        <?php        
            }
        }else{
        ?>
                <ul class="products column-2">
                    <?php
                    
                        $contains = uri_string(); // shop/category/coffee
                        $disable_style_and_products = (strpos($contains, 'shop/category/') === 0 && count(explode('/', $contains)) == 3);

                        foreach($loop_data as $row)
                        {
                    ?>
                    <!-- list pic ---->
                            
                            <li>
                                <div>
                                    <a href="<?php echo site_url($row['url']) ?>">
                                        <img src="<?php echo $row['image'] ?> ">
                                       
                                        <span><?php echo $row['title']; ?></span>
                                    </a>
                                </div>
                            </li>
                           
                    <?php        
                        }
                    ?>
                </ul>

        <?php
            }
        ?>
      </div>
      <div class="bottom_arrow">
         <img src="<?php echo base_url('./assets/images/shop/icon-coffee-small.png') ?>" width="32"> 
      </div>
      <?php }else {
         ?>
      <div class="container">
         <h3 class="white_out">No <?php echo $list_type ?> found</h3>
      </div>
      <?php
         }?>
   </div>
</div>
<!------------footer ---------------------------------------->
<?php echo view('includes/footer');?>
<!--------------- footer end -------------------------------->

<script>
    function openModal(productId) {
        // Reset the select boxes and price display
        resetModal(productId);
        document.getElementById('modal-' + productId).style.display = "block";
    }

    function closeModal(productId) {
        document.getElementById('modal-' + productId).style.display = "none";
    }

    // Reset the select box and price display
    function resetModal(productId) {
        // Reset select boxes within the modal
        var modal = document.getElementById('modal-' + productId);
        var selectBoxes = modal.querySelectorAll('select.variation_select');

        // Reset each select box to the default option
        selectBoxes.forEach(function(select) {
            select.selectedIndex = 0; // Set to "Select an option"
        });

        // Clear the displayed price
        modal.querySelector('.product_price').innerHTML = ''; // Clear the price display
    }

    // Close modal when clicking outside of it
    window.onclick = function(event) {
        var modals = document.getElementsByClassName("modal");
        for (var i = 0; i < modals.length; i++) {
            if (event.target == modals[i]) {
                modals[i].style.display = "none";
            }
        }
    }
</script>

<script>

$(document).ready(function() {
    // Handle variation changes for weight, size, and quantity
    $('select[name="variations[attribute_weight]"], select[name="variations[attribute_size]"], select[name="variations[attribute_quantity]"]').change(function() {
        var selectedOption = $(this).val();
        var productId = $(this).closest('tr').nextAll('tr').find("input[name='product_id']").val();

        if (selectedOption && productId) {
            $.ajax({
                url: "<?php echo base_url('/testing_price'); ?>",
                type: "POST",
                data: {
                    product_id: productId,
                    variation: selectedOption
                },
                success: function(response) {
                    // Update the price on success and add currency symbol
                    var currencySymbol = '£'; // Change this to your desired currency symbol
                    $('.product_price').html(currencySymbol + response);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("Error fetching price: " + textStatus, errorThrown);
                }
            });
        }
    });
});




</script>

<!-- Smooth scrolling with JavaScript -->
<script>
document.querySelectorAll('.category-links a').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});
</script>


<script>

    window.addEventListener('scroll', function() {
        var categoriesList = document.getElementById('stickyCategories');
        var sticky = categoriesList.offsetTop; // Get the initial position of the categories list

        if (window.pageYOffset > sticky) {
            categoriesList.classList.add('sticky'); // Add sticky class
        } else {
            categoriesList.classList.remove('sticky'); // Remove sticky class
        }
    });

</script>

<script>
    $(document).ready(function() {
        // When a category link is clicked
        $('.category-links a').on('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all categories and sections
            $('.category-links a').removeClass('active');  // For category links
            $('.category-container').removeClass('active');  // For category sections

            // Add active class to clicked category link
            $(this).addClass('active');
            
            // Get the href attribute (which contains the target section ID)
            var target = $(this).attr('href');
            
            // Add active class to the corresponding section
            $(target).addClass('active');
            
            // Scroll to the section and place it at the bottom
            $('html, body').animate({
                scrollTop: $(target).offset().top
            }, 500);
        });
    });
</script>
