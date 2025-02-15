<div class="payment_box">


    <div class="woocommerce-notices-wrapper" style="display: none"></div>

    <button type="submit" id="submit-button" name="proceed" value="1" style="margin: 0;">Complete Order</button>

</div>

<script type="text/javascript">
    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const formData = new FormData(form);
        formData.set('payment_method','direct');
        const action = form.getAttribute('action');

        document.querySelector('#checkout').classList.add('loading');

        const response = await fetch(action, {
            method: "POST",
            body: formData
        });
        const data = await response.json();

        if(data.success) {
            const orderID = data.orderID;
            window.location = '<?php echo site_url() ?>cart/order-complete?id='+orderID;
        }else {
            document.querySelector('#checkout').classList.remove('loading');
        }

    });
</script>