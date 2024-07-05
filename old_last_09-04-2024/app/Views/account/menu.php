<!-- menue user dashboard -->
<nav class="woocommerce-MyAccount-navigation">
    <ul>
        <li class="<?php echo current_page() == 'dashboard' ? 'is-active':'' ?>">
            <a href="<?php echo base_url('account') ?>">Dashboard</a>
        </li>
        <li class="<?php echo current_page() == 'orders' ? 'is-active':'' ?>">
            <a href="<?php echo base_url('account/orders') ?>">Orders</a>
        </li>
        <li class="<?php echo current_page() == 'subscriptions' ? 'is-active':'' ?>">
            <a href="<?php echo base_url('account/subscriptions') ?>">Subscriptions</a>
        </li>
        <li class="<?php echo current_page() == 'edit-address' ? 'is-active':'' ?>">
            <a href="<?php echo base_url('account/edit-address') ?>">Address</a>
        </li>
        <?php /* <li class="<?php echo current_page() == 'payment-methods' ? 'is-active':'' ?>">
            <a href="<?php echo base_url('account/payment-methods') ?>">Payment methods</a>
        </li>*/ ?>
        <li class="<?php echo current_page() == 'edit-account' ? 'is-active':'' ?>">
            <a href="<?php echo base_url('account/edit-account') ?>">Account details</a>
        </li>

        <li class="<?php echo current_page() == 'logout' ? 'is-active':'' ?>">
            <a class="logout-btn" href="<?php echo base_url('account/logout') ?>">Logout</a>
        </li>
    </ul>
</nav>