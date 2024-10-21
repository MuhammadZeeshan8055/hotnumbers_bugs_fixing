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
                        .icon-wrapper {
                            display: inline-flex;
                            align-items: center;
                            padding: 10px;
                            background-color: transparent;
                            border-radius: 5px;
                            transition: background-color 0.3s ease;
                            cursor: pointer;
                            background: var(--red);
                        }

                        .icon-wrapper:hover {
                            background-color: gray;
                        }

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
                        /* Responsive adjustments */
                        @media (max-width: 768px) {
                        table.custom-table {
                            font-size: 14px; /* Slightly smaller font size for mobile */
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
                                            <table style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <!-- <th>Category</th> -->
                                                        <th>Image</th>
                                                        <th>Product Title</th>
                                                        <th>Price</th>
                                                        <th style="text-align:center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($row['products'])) {
                                                        foreach ($row['products'] as $product) {
                                                            $attributes = !empty($product['attributes']) && $product['type'] == "variable" ? json_decode($product['attributes'], true) : [];
                                                    ?>
                                                    <tr>
                                                        <!-- <td><?= $row['title'] ?></td> -->
                                                        <td>
                                                            <div>
                                                                <a href="<?php echo site_url($product['url']) ?>">
                                                                    <img src="<?php echo $product['image']; ?>" class="product-list-image">
                                                                </a>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <a href="<?php echo site_url($product['url']) ?>">
                                                                <span><?php echo $product['title']; ?></span>
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <?php
                                                                $productModel = model('ProductsModel');

                                                                if($product['type'] == "variable"){
                                                                    $price = $productModel->product_price($product['id']);
                                                                    $p = $price[0];
                                                                    $discount_price = $productModel->product_reduced_price($p);
                                                                    if($discount_price) {
                                                                        $p = $discount_price;
                                                                    }
                                                                    $price_text = '<span class="woocommerce-Price-currencySymbol">From '._price(number_format($p,2)).'</span>';

                                                                }else{

                                                                    $price = isset($product['price']) && is_numeric($product['price']) ? (float)$product['price'] : 0.00;
                                                                    $price_text = '<span class="woocommerce-Price-currencySymbol">' . _price(number_format($price, 2)) . '</span>';
    
                                                                }

                                                                                                                               
                                                            ?>
                                                            <span id="product_price"><?= $price_text ?></span>
                                                        </td>
                                                        <td style="text-align:center">
                                                            <div class="icon-wrapper" onclick="openModal('<?php echo $product['id']; ?>')">
                                                                <span>Add</span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php } } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php } ?>
                                </div>

                                <?php foreach ($loop_data as $row) { ?>
                                    <!-- Modals Section - outside the table -->
                                    <?php if (!empty($row['products'])) {
                                        foreach ($row['products'] as $product) {
                                            $attributes = !empty($product['attributes']) && $product['type'] == "variable" ? json_decode($product['attributes'], true) : [];
                                    ?>
                                            <div id="modal-<?php echo $product['id']; ?>" class="modal" style="display:none;">
                                                <div class="modal-content">
                                                    <span class="close" onclick="closeModal('<?php echo $product['id']; ?>')">&times;</span>
                                                    <form class="variations_form cart validate" action="" method="post" enctype="multipart/form-data">
                                                        <div class="modal-image-wrapper">
                                                            <img src="<?php echo $product['image']; ?>" style="border-radius: 2px;margin-bottom: 10px;">
                                                        </div>
                                                        <h5><?php echo $product['title']; ?></h5>
                                                        <!-- Variations and attributes -->
                                                        <table class="variations" cellspacing="0">
                                                            <tbody>
                                                                <?php if (!empty($attributes) && $product['type'] == "variable") {
                                                                    foreach ($attributes as $attribute) {
                                                                        if (!empty($attribute['attribute_variation'])) {
                                                                            $label = $attribute['label'];
                                                                            $label_id = strtolower(str_replace(' ', '-', $label));
                                                                ?>
                                                                <tr>
                                                                    <td width="150"><?php echo ucfirst($label); ?>:</td>
                                                                    <td style="padding:12px">
                                                                        <select name="variations[attribute_<?php echo $label_id; ?>]" required>
                                                                            <option value="">Select an option</option>
                                                                            <?php foreach ($attribute['value'] as $variation) { ?>
                                                                            <option value="<?php echo $variation; ?>"><?php echo $variation; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </td>
                                                                </tr>
                                                                <?php } } } ?>
                                                                <tr>
                                                                    <td>Quantity:</td>
                                                                    <td><input type="number" name="quantity" value="1" min="1"></td>
                                                                </tr>
                                                                <tr>
                                                                    <?php
                                                                        if ($product['type'] != "variable") {
                                                                            $price = isset($product['price']) && is_numeric($product['price']) ? (float)$product['price'] : 0.00;
                                                                            $price_text = '<span class="woocommerce-Price-currencySymbol">' . _price(number_format($price, 2)) . '</span>';
                                                                    ?>
                                                                        <td width="150" height="60px" class="label" style="padding:12px">Price: </td>
                                                                        <td style="padding:12px">
                                                                            <span id="product_price"><?= $price_text ?></span>
                                                                        </td>
                                                                    <?php } else { ?>
                                                                        <td width="150" height="60px" class="label" style="padding:12px">Price:</td>
                                                                        <td><span class="product_price"> </span></td>
                                                                    <?php } ?>
                                                                    <span class="product_price" style="display:none"></span>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2">
                                                                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">

                                                                        <div class="cart-close-button">
                                                                            <button type="submit" class="single_add_to_cart_button">Add to Cart</button>
                                                                            <span class="cancel_button" onclick="closeModal('<?php echo $product['id']; ?>')">Cancel</span>
                                                                        </div>
                                                                    </td>
                                                                    <td colspan="2">
                                                                        
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </form>
                                                </div>
                                            </div>
                                    <?php } } } ?>
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