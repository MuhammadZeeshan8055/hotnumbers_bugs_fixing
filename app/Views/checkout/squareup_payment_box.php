<div class="payment_box">
    <p>Test card:</p>
    <p>Number: 	4111 1111 1111 1111</p>
    <p>Exp: 05/25</p>
    <p>CVV: 111</p>
    <p>Zipcode: 44444</p>
    <img id="dropin-loader" src="<?php echo base_url('assets/images/loader-2.svg') ?>">
    <div id="square_payment"></div>
</div>

<script type="text/javascript">
    function setSquareErr(e) {
        window.location = '#square_payment';
        document.querySelector('.woocommerce-notices-wrapper').style.display = 'block';
        const err = 'Payment error: '+ e;
        document.querySelector('.woocommerce-notices-wrapper').innerHTML += '<div>' + err + '</div>';
        document.querySelector('#checkout').classList.remove('loading');
    }

    function formSubmitProcess(form, formData) {

        formData.set('process_checkout',1);
        formData.set('payment_method','squareup');
        formData.set('is_ajax','true');

        const action = form.getAttribute('action');

        const response = fetch(action, {
            method: "POST",
            body: formData,
        }).catch((e)=>{
            setSquareErr(e);
        }).then(res=>res.json()).then(data=>{
            if(data.success) {
                const orderID = data.orderID;
                window.location = '<?php echo site_url() ?>cart/order-complete?id='+orderID;
            }else {

                document.querySelector('.woocommerce-notices-wrapper').style.display = 'block';

                if(typeof data.type !== "undefined" && data.type === "billing_email") {
                    window.location = '#billing_state_field';
                    $('[name="billing_email"]').closest('.form-row').find('.error_message').remove();
                    $('[name="billing_email"]').after(`<div class="error_message">${data.message}</div>`);
                }else {
                    window.location = '#square_payment';
                    data.message.forEach((msg)=>{
                        document.querySelector('.woocommerce-notices-wrapper').innerHTML += '<div>'+msg.detail+'</div>';
                    });
                }
                document.querySelector('#checkout').classList.remove('loading');
            }
        });

        return response;
    }

    async function init_payment() {

        const payments = Square.payments('<?php echo env('squareup.app_id') ?>');
        try {

            const card = await payments.card();
            await card.attach('#square_payment');

            document.querySelector('#dropin-loader').style.display = 'none';
            document.querySelector('#submit-button').hidden = false;

            const form = document.getElementById('checkout');

           // const cardButton = document.getElementById('submit-button');

            document.getElementById('checkout').addEventListener('submit', async (event) => {

                event.preventDefault();

                setTimeout(()=>{
                    document.querySelector('#checkout').classList.add('loading');
                },100);

                document.querySelector('.woocommerce-notices-wrapper').style.display = 'none';
                document.querySelector('.woocommerce-notices-wrapper').innerHTML = '';

                if($('[name="customer_user_card"]:checked').length) {
                    const formData = new FormData(form);
                    formData.set('customer_user_card', $('[name="customer_user_card"]:checked').val());
                    formData.set('is_ajax','true');
                    formSubmitProcess(form, formData);
                }else {
                    try {
                        card.tokenize().catch((e)=>{
                            setSquareErr(e);
                        }).then((tokenResult)=>{
                            const token = tokenResult.token;
                            if(typeof tokenResult.errors !== "undefined") {
                                document.querySelector('.woocommerce-notices-wrapper').style.display = 'block';
                                tokenResult.errors.forEach((error) => {
                                    document.querySelector('.woocommerce-notices-wrapper').innerHTML += '<div>' + error.message + '</div>';
                                })
                                document.querySelector('#checkout').classList.remove('loading');
                            }
                            const formData = new FormData(form);
                            formData.set('token', token);
                            formData.set('is_ajax','true');
                            formSubmitProcess(form, formData);
                        });
                    }catch (e) {
                        setSquareErr(e);
                    }
                }


            });

        } catch (e) {
            setSquareErr(e);
        }
    }

    document.addEventListener('DOMContentLoaded', init_payment);
</script>

<style>
    #square_payment {
        padding-top: 1em;
    }
</style>