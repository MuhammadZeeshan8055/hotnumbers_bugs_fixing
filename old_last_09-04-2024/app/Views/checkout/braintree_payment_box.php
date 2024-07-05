<div>
    <p>Test card:</p>
    <p>Number: 4111111111111111</p>
    <p>Exp: 05/25</p>
    <h3 id="order_review_payment" style="margin-bottom: 0;">Make Payment</h3>
    <div class="payment_box">
        <input type="hidden" name="checkout_place_order" value="1">
        <input type="hidden" name="type" value="product">
        <input type="hidden" id="nonce" name="payment_method_nonce">
        <input type="hidden" id="device_data" name="device_data">


        <img id="dropin-loader" src="<?php echo base_url('assets/images/loader-2.svg') ?>">
        <div id="dropin-container"></div>


        <br>
        <script>

            const token = 'sandbox_d58rj2pr_8cnhzs3gnpkcvcr2';

            braintree.client.create({
                authorization: token
            },(err, clientInstance)=> {
                braintree.dataCollector.create({
                    client: clientInstance, // `clientInstance` not dropIn `instance`
                    paypal: true
                }, function (err, dataCollectorInstance) {
                    if (err) {
                        console.log(err);
                        return;
                    }
                    document.querySelector('#device_data').value =  dataCollectorInstance.deviceData;
                    document.querySelector('#dropin-loader').style.display = 'none';
                });

                braintree.dropin.create({
                    authorization: token,
                    container: '#dropin-container'
                }, function (createErr, instance) {
                    if(createErr) {
                        document.querySelector('.woocommerce-notices-wrapper').style.display = 'block';
                        document.querySelector('.woocommerce-notices-wrapper').innerHTML = createErr;
                    }
                    else {
                        document.querySelector('.woocommerce-notices-wrapper').style.display = 'none';
                        document.querySelector('.woocommerce-notices-wrapper').innerHTML = '';
                        document.querySelector('#submit-button').style.display = 'block';
                        form.addEventListener('submit', event => {
                            event.preventDefault();

                            instance.requestPaymentMethod((error, payload) => {
                                if (error) console.error(error);
                                document.getElementById('nonce').value = payload.nonce;
                                const formData = new FormData(form);
                                const action = form.getAttribute('action');
                                document.querySelector('#checkout').classList.add('loading');
                                fetch(action,{
                                    method: "post",
                                    body: formData
                                }).then(res=>res.json()).then((result)=>{
                                    const message = result.message;
                                    if(result.success) {
                                        const orderID = result.orderID;
                                        window.location = '<?php echo site_url() ?>cart/order-complete?id='+orderID;
                                    }else {
                                        document.querySelector('.woocommerce-notices-wrapper').style.display = 'block';
                                        document.querySelector('.woocommerce-notices-wrapper').innerHTML = message;
                                        document.querySelector('#checkout').classList.remove('loading');
                                    }
                                })
                            });
                        });

                    }

                });
            });


        </script>
    </div>

</div>