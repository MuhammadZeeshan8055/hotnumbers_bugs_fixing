
<?php $session = session(); ?>

<?php echo view( 'includes/header');?>

<?php squareup_script_tag(); ?>

    <style>
        .row>* {
            flex-shrink: 0;
            width: 100%;
            max-width: 100%;
            padding-right: unset;
            padding-left: unset;
            margin-top: unset;
        }
    </style>


<div class="underbanner" style="background: url('<?php echo base_url('assets/images'); ?>/banner.jpg');"></div>

<!-- wrapper -->
<div class="wrapper">
        <!-- title -->
	<h1 class="pagetitle">My account</h1>
			<div class="container">

                <div class="woocommerce">


                    <?php include "menu.php" ?>
                    <!--- payment methods --->
                    <div class="woocommerce-MyAccount-content">
                        <?php echo get_message() ?>

                        <?php
                        /*if(is_wholesaler()) {
                            ?>
                        <form method="post" class="ajax_submit" action="<?php echo base_url('account/enable-payment-invoice') ?>">
                            <div class="input_field inline-checkbox mb-20">
                                <label><input type="checkbox" name="pay_by_invoice" value="1" <?php echo $pay_by_invoice == 'true' ? 'checked':'' ?> onclick="this.form.submit()">Enable Payment by invoice</label>
                            </div>
                        </form>
                        <?php
                        }*/
                        ?>

                        <?php
                        if(!empty($payment_methods)) {
                            ?>
                           <table class="table">
                               <thead>
                                    <tr>
                                        <th width="250">Cardholder name</th>
                                        <th>Type</th>
                                        <th>Expiry date</th>
                                        <th>Last 4 digits</th>
                                        <td width="100"></td>
                                    </tr>
                               </thead>
                               <tbody>
                                    <?php
                                    foreach($payment_methods as $method) {
                                        if(!empty($method['card']) && !empty($method['db_id'])) {
                                        $card = $method['card'];
                                        $card_id = $method['db_id'];
                                        ?>
                                            <tr>
                                                <td><?php echo @$card['cardholder_name'] ?></td>
                                                <td><?php echo @$card['card_brand'] ?></td>
                                                <td><?php echo @$card['exp_month'] ?> / <?php echo @$card['exp_year'] ?></td>
                                                <td><?php echo @$card['last_4'] ?></td>
                                                <td><a href="#" onclick="disableCard('<?php echo $card['id'] ?>'); return false;" class="btn btn-danger btn-sm">Delete</a> </td>
                                            </tr>
                                        <?php
                                        }
                                    }?>
                               </tbody>
                           </table>
                                <?php
                        }else {
                            ?>
                            <div class="woocommerce-Message woocommerce-Message--info woocommerce-info">No saved methods found.</div>
                        <?php
                        } ?>


                        <form id="checkout" class="validate" method="post" action="<?php echo base_url('account/add-payment-method') ?>" style="visibility: hidden; height: 0">
                            <div id="payment-method-add">
                                <div class="payment_box">
                                    <div class="sq_payment_box">
                                        <h5>Add new card</h5>
                                        <div class="pb-1">
                                            <label>Card holder name</label>
                                            <input type="text" data-error="Card holder name is required" name="card_holder_name" value="<?php echo !empty(old('card_holder_name')) ? old('card_holder_name') : $display_name ?>" required placeholder="Card holder name">
                                        </div>

                                        <div>
                                            <img id="dropin-loader" src="<?php echo base_url('assets/images/loader-2.svg') ?>">
                                            <div id="square_payment"></div>
                                        </div>
                                    </div>

                                    <div class="woocommerce-notices-wrapper" style="display: none; margin-top: 0; margin-bottom: 1em"></div>
                                </div>

                                <style>
                                    .sq_payment_box {
                                        border: 1px solid #eee;
                                        padding: 1em 1em 5px;
                                        margin-top: 2em;
                                        margin-bottom: 1em;
                                    }
                                    .error_message {
                                        padding-bottom: 2em;
                                    }
                                </style>

                                <script type="text/javascript">
                                    function setSquareErr(e) {
                                        window.location = '#square_payment';
                                        document.querySelector('.woocommerce-notices-wrapper').style.display = 'block';
                                        const err = 'Payment error: '+ e;
                                        document.querySelector('.woocommerce-notices-wrapper').innerHTML += '<div>' + err + '</div>';
                                        document.querySelector('#checkout').classList.remove('loading');
                                    }

                                    async function init_payment() {
                                        const payments = Square.payments('<?php echo env('squareup.app_id') ?>');
                                        try {
                                            const card = await payments.card();
                                            await card.attach('#square_payment');
                                            document.querySelector('#dropin-loader').style.display = 'none';
                                            document.querySelector('#submit-button').hidden = false;

                                            const form = document.getElementById('checkout');

                                            form.addEventListener('submit', async (event) => {
                                                event.preventDefault();
                                                document.querySelector('#checkout').classList.add('loading');

                                                document.querySelector('.woocommerce-notices-wrapper').style.display = 'none';
                                                document.querySelector('.woocommerce-notices-wrapper').innerHTML = '';

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
                                                        formData.set('process_add_card',1);
                                                        formData.set('payment_method','squareup');

                                                        formData.set('billing_first_name','<?php echo display_name($user) ?>');

                                                        const action = form.getAttribute('action');

                                                        fetch(action, {
                                                            method: "POST",
                                                            body: formData,
                                                        }).catch((e)=>{
                                                            setSquareErr(e);
                                                        }).then(res=>res.json()).then(data=>{
                                                            if(typeof data.success !== "undefined" && data.success) {
                                                                Swal.fire({
                                                                    title: data.message
                                                                }).then(res=>{
                                                                    location.reload();
                                                                });
                                                            }else {
                                                                if(typeof data.message[0].detail !== "undefined" && !data.success) {
                                                                    setSquareErr(data.message[0].detail);
                                                                }
                                                            }
                                                        });
                                                        document.querySelector('[type="submit"]').disabled = false;
                                                    });

                                                }catch (e) {
                                                    setSquareErr(e);
                                                }
                                            });
                                        } catch (e) {
                                            setSquareErr(e);
                                        }
                                    }

                                    document.addEventListener('DOMContentLoaded', init_payment);
                                </script>
                            </div>

                            <button id="submit-button" type="submit" class="btn">Save payment method</button>
                        </form>

                        <button type="button" onclick="addPaymentBoxShow(this)" class="btn">Add payment method</button>
                    </div>
                </div>

            </div>
</div>

<script>
    const addPaymentBoxShow = (button)=> {
        $(button).hide();
        $('#checkout').css('height','auto');
        $('#checkout').css('visibility','visible');
    }

    const disableCard = (card_id) => {
        message("Are you sure to delete this card?", {
            showCancelButton: true
        }).then(res=>{
            if(res.isConfirmed) {
                $('.woocommerce-MyAccount-content').addClass('.loading');
                window.location = '<?php echo base_url('account/disable-payment-method') ?>/'+card_id;
            }
        });
    }
</script>



<!------------footer ---------------------------------------->
<?php echo view('includes/footer');?>
<!--------------- footer end -------------------------------->


