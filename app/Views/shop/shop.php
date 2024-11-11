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
        max-width:100%;
        gap:10px;
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
        padding: 30px;
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

            $internal_user=is_internal();
            
            $contains = uri_string(); // shop/category/coffee
            $disable_style_and_products = (strpos($contains, 'shop/category/') === 0 && count(explode('/', $contains)) == 3);

                
            

                if(!$disable_style_and_products && $internal_user)
                    { 
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

                        /* view list style  */

                        html body button:not(.flickity-button), html body a.button, html body input[type=submit] {
                            padding: 7px;
                            border: 1px solid;
                            margin: 0em 0;
                            font-size: .9em;
                            font-weight: 700;
                        }


                        /* categories style  */

                        .category-container {
                            gap:10px;
                            width: 72%;
                        }
                
                    
                        .category-product-list {
                            display: flex;
                            flex-wrap: wrap;
                            gap: 20px;
                        }

                        .category ul.products li {
                            width: 100% !important;
                        }

                        .products-listing {
                            width: 100%; /* Width for the products column */
                        }

                        .category ul.products li {
                            width: 100% !important;
                        }

                        .products-listing {
                            width: 100%; /* Width for the products column */
                        }
                        
                        ul.category-links {
                            list-style: none;
                            overflow-x: auto; 
                            width: 94%;
                        }

                        

                        /* table design  */

                        .products-listing table {
                            width: 100%;
                            margin: auto;
                            color: white; /* White text inside the table */
                            background-color: transparent; /* Keep background transparent to blend with site */
                        }

                        .products-listing th {
                            background-color: var(--red); /* Red background for the table headers */
                            color: white; /* White text in the header */
                            padding: 10px;
                        }

                        .products-listing table td {
                            padding: 12px; /* Padding for table cells */
                            border: 1px solid #555;
                        
                        }

                        /* Custom striped rows */
                        .products-listing table tbody tr:nth-child(odd) {
                            background-color: #1a1a1a; /* Dark gray for odd rows */
                        }

                        .products-listing table tbody tr:nth-child(even) {
                            background-color: #000; /* Black for even rows */
                        }

                        .products-listing table .product-list-image{
                            height: 60px;
                            width: 60px;
                            border-radius: 10%;
                        }

                        /* Hover effect */
                        .products-listing table tbody tr:hover {
                            border:2px solid var(--red); 
                            color: white; 
                        }

                        /* cart trollery style  */
                        /* .icon-wrapper {
                            display: inline-flex;
                            align-items: center;
                            padding: 10px;
                            background-color: transparent;
                            border-radius: 5px;
                            transition: background-color 0.3s ease;
                            cursor: pointer;
                            background: var(--red);
                        } */


                        .icon-wrapper svg path {
                            fill: white;
                            transition: fill 0.3s ease;
                        }

                        .icon-wrapper:hover svg path {
                            fill: white;
                        }

                        .icon-wrapper span {
                            color: white;
                            font-size: 20px;
                            margin-left: 3px;
                            transition: color 0.3s ease;
                        }

                        .icon-wrapper:hover span {
                            color: white;
                        }
                        a.category-link.active {
                        text-decoration:none;
                        }
                        a.category-link.active p {
                            color: var(--red);
                            font-weight: 600;
                        }
                        .cart-close-button {
                            display: flex;
                            gap: 20px;
                            align-items: center;
                        }
                        .cancel_button {
                            display: inline-block;
                            padding: 6px 18px ;
                            background-color: #d2d2d2 ;
                            color: black ;
                            text-align: center;
                            text-decoration: none;
                            border-radius: 0px ;
                            cursor: pointer;
                            transition: background-color 0.3s;
                        }

                        /* tool tip style  */

                        .icon-wrapper {
                            position: relative;
                            display: inline-block;
                            cursor: pointer;
                        }

                        .icon-wrapper .tooltip {
                            visibility: hidden;
                            width: 120px;
                            background-color: #555;
                            color: #fff;
                            text-align: center;
                            padding: 5px;
                            border-radius: 6px;

                            /* Position the tooltip above the icon-wrapper */
                            position: absolute;
                            bottom: 125%; /* 125% places it above the icon-wrapper */
                            left: 50%;
                            transform: translateX(-50%);
                            z-index: 1;

                            /* Tooltip arrow */
                            opacity: 0;
                            transition: opacity 0.3s;
                        }

                        .icon-wrapper .tooltip::after {
                            content: "";
                            position: absolute;
                            top: 100%; /* Arrow will be at the bottom of the tooltip */
                            left: 50%;
                            margin-left: -5px;
                            border-width: 5px;
                            border-style: solid;
                            border-color: #555 transparent transparent transparent;
                        }

                        .icon-wrapper:hover .tooltip {
                            visibility: visible;
                            opacity: 1;
                        }
                        .select-box-alignment {
                            display: flex;
                            margin:18px 0 0 0;
                            gap: 10px;
                            align-items:center;
                        }
                        html body .wrapper label{
                            padding:0px;
                        }
                        .select-box-alignment select {
                            border-radius: 2px;
                            font-size: 15px;
                            background: black !important;
                            color: white;
                        }
                        .swal2-container.swal2-bottom-end.swal2-backdrop-show {
                            width: 250px !important;
                        }
                        .swal2-popup.swal2-toast.animated.fadeInDown.single_add_to_cart.error {
                            width: 250px !important;
                        }

                        .subscription_checkbox {
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            gap: 10px;
                        }
                        input[type="number"] {
                            margin-bottom: 0px !important;
                            padding: 7px !important;
                            border: 1px solid;
                            margin: 0em 0 !important;
                            font-size: .9em;
    
                        }
                        /* Responsive adjustments */
                        @media (max-width: 768px) {
                            table.custom-table {
                                font-size: 14px; /* Slightly smaller font size for mobile */
                            }
                        }
                        @media (max-width: 931px) {
                            .products-listing {
                                width: 130%;
                                overflow-x: scroll;
                            }
                        }
                        
                        </style>
                    <?php
                        }

                        if(!$disable_style_and_products && $internal_user)
                        { 
                            ?>

                            <div class="category-product-list">
                                <!-- Categories Section -->
                                <div class="categories-list" id="stickyCategories">
                                    <ul class="category-links">
                                        <?php foreach ($loop_data as $index => $row) { ?>
                                            <li>
                                                <a href="javascript:void(0);" class="category-link <?php echo $index === 0 ? 'active' : ''; ?>" onclick="showCategory('<?php echo $row['cid']; ?>', this)">
                                                    <p class="category-title"><?php echo $row['title']; ?></p>
                                                </a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>

                                <div class='category-container'>
                                    <?php foreach ($loop_data as $index => $row) { ?>
                                        <div class='products-listing' id="category-<?php echo $row['cid']; ?>" style="display: <?php echo $index === 0 ? 'block' : 'none'; ?>;">
                                        <table style="width: 100%; border-collapse: collapse;">
                                            <thead>
                                                <tr style="background-color: #f2f2f2;">
                                                    <th>Image</th>
                                                    <th>Product Title</th>
                                                    <th>Price</th>
                                                    <th style="text-align:center">Action</th>
                                                    <th style="text-align:center">Quantity</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($row['products'])) {
                                                    foreach ($row['products'] as $product) {
                                                        $attributes = !empty($product['attributes']) && $product['type'] == "variable" ? json_decode($product['attributes'], true) : [];
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <div>
                                                                    <a href="<?php echo site_url($product['url']); ?>">
                                                                        <img src="<?php echo $product['image']; ?>" class="product-list-image" alt="<?php echo htmlspecialchars($product['title']); ?>">
                                                                    </a>
                                                                </div>
                                                            </td>
                                                            <td>
                                                               
                                                                <a href="<?php echo site_url($product['url']); ?>">
                                                                    <span><?php echo $product['title']; ?></span>
                                                                </a>
                                                                <br/>
                                                                    <!-- Product attributes (for variable products) -->
                                                                <div class="select-box-alignment">
                                                                    <?php if (!empty($attributes) && $product['type'] == "variable") { ?>
                                                                    <?php foreach ($attributes as $attribute) {
                                                                        if (!empty($attribute['attribute_variation'])) {
                                                                            $label = $attribute['label'];
                                                                            $label_id = strtolower(str_replace(' ', '-', $label));
                                                                            ?>
                                                                            <label for="attribute_<?php echo $label_id; ?>"><?php echo $label; ?></label>
                                                                            <select name="variations[attribute_<?php echo $label_id; ?>]" data-attribute="<?php echo $label_id; ?>" required form="form_<?php echo $product['id']; ?>">
                                                                                <option value="">Select an option</option>
                                                                                <?php foreach ($attribute['value'] as $variation) { ?>
                                                                                    <option value="<?php echo $variation; ?>"><?php echo $variation; ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        <?php }
                                                                        } ?>
                                                                    <?php } ?>
                                                                </div>
                                                             
                                                            </td>
                                                            <td>
                                                                <?php
                                                                $productModel = model('ProductsModel');
                                                                if ($product['type'] == "variable") {
                                                                    $price = $productModel->product_price($product['id']);
                                                                    $p = $price[0];
                                                                    $discount_price = $productModel->product_reduced_price($p);
                                                                    if ($discount_price) {
                                                                        $p = $discount_price;
                                                                    }
                                                                    $price_value = is_numeric($p) ? (float)$p : 0.00;  // Ensure $p is numeric, otherwise set a default value
                                                                    $price_text = '<span class="woocommerce-Price-currencySymbol">From ' . _price(number_format($price_value, 2)) . '</span>';
                                                                } else {
                                                                    $price = isset($product['price']) && is_numeric($product['price']) ? (float)$product['price'] : 0.00;
                                                                    $price_text = '<span class="woocommerce-Price-currencySymbol">' . _price(number_format($price, 2)) . '</span>';
                                                                }
                                                                ?>
                                                                <span class="base_price"><?= $price_text ?></span> <!-- Updated to use class for base price -->
                                                                <span class="product_price"> </span>
                                                            </td>
                                                            <td style="text-align:center">
                                                                <!-- Form for adding the product to the cart -->
                                                                <form id="form_<?php echo $product['id']; ?>" class="variations_form cart validate" action="" method="post" enctype="multipart/form-data">
                                                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                                                    <?php 

                                                                        if($product['type'] ==  "variable"){
                                                                                                                                            
                                                                            $product_id=$product['id'];
                                                                            $check = check_manage_stock_for_variations($product_id);

                                                                            // Decode the JSON string
                                                                            $checkvariations = json_decode($check['variation'], true);
                                                                            
                                                                            if (is_array($checkvariations)) {
                                                                                $manage_stock = 'no'; // Initialize default value
                                                                                $stock_status = 'Out of Stock'; // Default stock status

                                                                                foreach ($checkvariations as $variation) {
                                                                                    if ($variation['values']['manage_stock'] == 'yes') {
                                                                                        $manage_stock = 'yes';
                                                                                    }
                                                                                    
                                                                                    if ($variation['values']['stock_status'] == 'instock') {
                                                                                        $stock_status_class = 'stock stock_available';
                                                                                        $stock_status = 'In Stock';
                                                                                        break; // Exit the loop if 'In Stock' is found
                                                                                    }
                                                                                }
                                                                            }
                                                                        }

                                                                        if($product['type'] ==  "variable" && $product['stock_managed']=='no' && $manage_stock=='no' && $product['stock_status']=='outofstock'){
                                                                            ?>

                                                                            <div class="icon-wrapper" style="background:gray">
                                                                                <button type="submit" class="greyed_button single_add_to_cart_button" style="background:gray" disabled>Add</button>
                                                                                <div class="tooltip">Out Of Stock</div>
                                                                            </div>

                                                                        <?php
                                                                        }
                                                                        elseif ($product['stock_status'] == 'outofstock') { 
                                                                                                                                               

                                                                        ?>
                                                                            <div class="icon-wrapper" style="background:gray">
                                                                                <button type="submit" class="greyed_button single_add_to_cart_button" style="background:gray" disabled>Add</button>
                                                                                <div class="tooltip">Out Of Stock</div>
                                                                            </div>
                                                                    <?php } else { ?>
                                                                        <button type="submit" class="single_add_to_cart_button">Add</button>
                                                                    <?php } ?>
                                                                </form>
                                                            </td>
                                                            
                                                            <td style="text-align:center">
                                                                <!-- Quantity field inside the form, linked with the same form ID -->
                                                                <input type="number" name="quantity" value="1" min="1" style="width: 60px;" form="form_<?php echo $product['id']; ?>">
                                                            </td>
                                                           
                                                        </tr>
                                                    <?php }
                                                } ?>
                                            </tbody>
                                        </table>




                                        </div>
                                    <?php } ?>
                                </div>





                                
                            </div>


                            <?php
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
    // Use event delegation to handle changes on any attribute select
    $(document).on('change', 'select[name^="variations\\[attribute_"]', function() {
        var $currentSelect = $(this);
        var productId = $currentSelect.closest('tr').find("input[name='product_id']").val();
        var productPriceSpan = $currentSelect.closest('tr').find('.product_price');
        var basePriceSpan = $currentSelect.closest('tr').find('.base_price');

        // Get all select boxes in the current row
        var $selects = $currentSelect.closest('tr').find('select[name^="variations\\[attribute_"]');

        // Initialize variables
        var selectedOption = null;
        var attributeName = null;

        // Loop through each select box to find the first valid selection
        $selects.each(function() {
            var $select = $(this);
            var value = $select.val();

            // Check if this select has a value
            if (value) {
                selectedOption = value; // Store the first valid selection
                attributeName = $select.data('attribute'); // Store the attribute name
                return false; // Break the loop after finding the first valid selection
            }
        });

        // If no valid selections were found, hide the price display and show base price
        if (!selectedOption) {
            productPriceSpan.hide();
            basePriceSpan.show();
            return; // Exit early
        }

        // If a valid selection was found, make the AJAX request
        if (productId) {
            $.ajax({
                url: "<?php echo base_url('/get_price'); ?>",
                type: "POST",
                data: {
                    product_id: productId,
                    variation: selectedOption,
                    attribute: attributeName // Send the attribute name for the valid selection
                },
                success: function(response) {
                    var currencySymbol = '£'; // Change this to your desired currency symbol
                    if (response && response !== "No matching variation found") {
                        productPriceSpan.html(currencySymbol + response).show();
                        basePriceSpan.hide();
                    } 
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
function showCategory(categoryId) {
    // Hide all categories
    const allCategories = document.querySelectorAll('.products-listing');
    allCategories.forEach(function(category) {
        category.style.display = 'none';
    });

    // Show the selected category
    const selectedCategory = document.getElementById('category-' + categoryId);
    if (selectedCategory) {
        selectedCategory.style.display = 'block';
    }
}
</script>

<script>
    function showCategory(categoryId, element) {
        // Hide all categories
        var categories = document.querySelectorAll('.products-listing');
        categories.forEach(function (category) {
            category.style.display = 'none';
        });

        // Show the selected category
        var selectedCategory = document.getElementById('category-' + categoryId);
        if (selectedCategory) {
            selectedCategory.style.display = 'block';
        }

        // Remove active class from all links and add to the clicked link
        var links = document.querySelectorAll('.category-link');
        links.forEach(function (link) {
            link.classList.remove('active');
        });
        element.classList.add('active');
    }
</script>

<script>
    function subscription_switch(productId) {
        var selectBox = document.getElementById('select_' + productId);
        var delivered_every = document.getElementById('delivered_every_' + productId);
        var checkbox = document.getElementById('checkbox_' + productId);
        if (checkbox.checked) {
            selectBox.style.display = 'block';
            delivered_every.style.display = 'block';
        } else {
            selectBox.style.display = 'none';
            delivered_every.style.display = 'none';
        }
    }
</script>