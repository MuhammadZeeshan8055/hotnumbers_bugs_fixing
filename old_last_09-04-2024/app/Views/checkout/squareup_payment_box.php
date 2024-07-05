<h3 id="order_review_payment" style="margin-bottom: 0;">Make Payment</h3>

<div class="payment_box">

    <p>Test card:</p>
    <p>Number: 4111 1111 1111 1111</p>
    <p>Exp: 05/25</p>
    <p>CVV: 111</p>

    <img id="dropin-loader" src="<?php echo base_url('assets/images/loader-2.svg') ?>">
    <div id="square_payment"></div>

    <div class="woocommerce-notices-wrapper" style="display: none"></div>

    <br>

    <button type="submit" id="submit-button" hidden="" name="proceed" value="1" style="margin: 0;">Complete Order</button>
</div>

<script type="text/javascript">

    (async()=>{
        const payments = Square.payments('<?php echo env('squareup.app_id') ?>');
        try {
            const card = await payments.card();
            await card.attach('#square_payment');
            document.querySelector('#dropin-loader').style.display = 'none';
            document.querySelector('#submit-button').hidden = false;

            const cardButton = document.getElementById('submit-button');

            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                document.querySelector('#checkout').classList.add('loading');
                const tokenResult = await card.tokenize();
                document.querySelector('.woocommerce-notices-wrapper').style.display = 'none';
                document.querySelector('.woocommerce-notices-wrapper').innerHTML = '';
                if (tokenResult.status === 'OK') {
                    const token = tokenResult.token;

                    const formData = new FormData(form);
                    formData.set('token', token);
                    formData.set('process_checkout',1);
                    formData.set('payment_method','squareup');

                    const action = form.getAttribute('action');
                    const response = await fetch(action, {
                        method: "POST",
                        body: formData,
                    });
                    const data = await response.json();

                    console.log(data);

                    if(data.success) {
                        const orderID = data.orderID;
                        window.location = '<?php echo site_url() ?>cart/order-complete?id='+orderID;
                    }else {
                        document.querySelector('.woocommerce-notices-wrapper').style.display = 'block';
                        data.message.forEach((msg)=>{
                            document.querySelector('.woocommerce-notices-wrapper').innerHTML += '<div>'+msg.detail+'</div>';
                        });
                        document.querySelector('#checkout').classList.remove('loading');
                    }

                } else {
                    document.querySelector('.woocommerce-notices-wrapper').style.display = 'block';

                    tokenResult.errors.forEach((error)=>{
                        document.querySelector('.woocommerce-notices-wrapper').innerHTML += '<div>'+error.message+'</div>';
                    })


                    document.querySelector('#checkout').classList.remove('loading');
                }

            });
        } catch (e) {
            console.error(e);
            document.querySelector('#dropin-loader').style.display = 'none';
        }
    })();
</script>

<style>
    #square_payment {
        padding-top: 1em;
    }
</style>