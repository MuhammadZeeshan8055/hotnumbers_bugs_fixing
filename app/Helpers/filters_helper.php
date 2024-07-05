<?php
function filter_shipping_rules($cart=[]) {

    $shipping_rules = get_setting('shipment_rule',true);
    $userModel = model('UserModel');
    $prodModel = model('ProductsModel');
    $shipping_cost = $cart['shipping_cost'];
    $subtotal = $cart['product_total'];
    $cart_tax = $cart['total_tax'];
    $cart_total = $cart['cart_total'];

    $user_id = is_logged_in();

    unset($cart['shipping_add']);
    unset($cart['shipping_rule']);

    foreach($shipping_rules as $rules) {
        $option_name = !empty($rules['option_name']) ? $rules['option_name'] : '';
        $option_value = !empty($rules['option_value']) ? $rules['option_value'] : '';
        $option_type = !empty($rules['option_type']) ? $rules['option_type'] : '';

        $condition_match = [];

        foreach($rules['option_data'] as $rule) {
            $subject = $rule['subject'];
            $condition = $rule['condition'];
            $value = $rule['value'];

            if($subject === "user") {
                if($condition === 'equal_to') {
                    if($user_id === $value) {
                        $condition_match[] = ['subject'=>$subject,'condition'=>$condition,'value'=>$value];
                    }
                }elseif($condition === 'not_equal_to') {
                    if($user_id !== $value) {
                        $condition_match[] = ['subject'=>$subject,'condition'=>$condition,'value'=>$value];
                    }
                }
            }

            if($subject === "user_role") {
                $role = $userModel->get_user_roles($user_id);
                $db_role = shop_roles('WHERE id="'.$value.'"');
                if(!empty($db_role)) {
                    $db_role = $db_role[0]->role;
                    if($condition === 'equal_to') {
                        if(array_key_exists($db_role,$role)) {
                            if($option_name === 'discount') {
                                $condition_match[] = ['subject'=>$subject,'condition'=>$condition,'value'=>$value];
                            }
                            if($option_name === 'add_cost') {
                                $condition_match[] = ['subject'=>$subject,'condition'=>$condition,'value'=>$value];
                            }
                        }
                    }elseif($condition === 'not_equal_to') {
                        if(!array_key_exists($db_role,$role)) {
                            if($option_name === 'discount') {
                                $condition_match[] = ['subject'=>$subject,'condition'=>$condition,'value'=>$value];
                            }
                            if($option_name === 'add_cost') {
                                $condition_match[] = ['subject'=>$subject,'condition'=>$condition,'value'=>$value];
                            }
                        }
                    }
                }
            }

            if($subject === "product_id" && !empty($cart['products'])) {
                foreach($cart['products'] as $product) {
                    if($condition === 'equal_to' && $product['product_id'] === $value) {
                        $condition_match[] = ['subject'=>$subject,'condition'=>$condition,'value'=>$value];
                        break;
                    }elseif($condition === 'not_equal_to' && $product['product_id'] !== $value) {
                        $condition_match[] = ['subject'=>$subject,'condition'=>$condition,'value'=>$value];
                        break;
                    }
                }
            }

            if($subject === "product_category" && !empty($cart['products'])) {
                foreach($cart['products'] as $product) {
                    $categories = $prodModel->product_categories($product['product_id'],'id');
                    foreach($categories as $category) {
                        if($condition === 'equal_to' && $category['id'] === $value) {
                            $condition_match[] = ['subject'=>$subject,'condition'=>$condition,'value'=>$value];
                            break;
                        }elseif($condition === 'not_equal_to' && $category['id'] !== $value) {
                            $condition_match[] = ['subject'=>$subject,'condition'=>$condition,'value'=>$value];
                            break;
                        }
                    }
                }
            }

            if($subject === "subtotal") {
                if($condition === "equal_to" && $subtotal == $value) {
                    $condition_match[] = ['subject'=>$subject,'condition'=>$condition,'value'=>$value];
                }
                if($condition === "greater_than" && $subtotal > $value) {
                    $condition_match[] = ['subject'=>$subject,'condition'=>$condition,'value'=>$value];
                }
                if($condition === "less_than" && $subtotal < $value) {
                    $condition_match[] = ['subject'=>$subject,'condition'=>$condition,'value'=>$value];
                }
            }

            if($subject === "total") {
                if($condition === "equal_to" && $cart_total == $value) {
                    $condition_match[] = ['subject'=>$subject,'condition'=>$condition,'value'=>$value];
                }
                if($condition === "greater_than" && $cart_total > $value) {
                    $condition_match[] = ['subject'=>$subject,'condition'=>$condition,'value'=>$value];
                }
                if($condition === "less_than" && $cart_total < $value) {
                    $condition_match[] = ['subject'=>$subject,'condition'=>$condition,'value'=>$value];
                }
            }

            if($subject === "quantity") {
                $total_quantity = 0;
                foreach($cart['products'] as $product) {
                    $total_quantity += $product['quantity'];
                }
                if($condition === "equal_to" && $total_quantity == $value) {
                    $condition_match[] = ['subject'=>$subject,'condition'=>$condition,'value'=>$value];
                }
                if($condition === "greater_than" && $total_quantity > $value) {
                    $condition_match[] = ['subject'=>$subject,'condition'=>$condition,'value'=>$value];
                }
                if($condition === "less_than" && $total_quantity < $value) {
                    $condition_match[] = ['subject'=>$subject,'condition'=>$condition,'value'=>$value];
                }
            }

            if($subject === "tax") {
                if($condition === "equal_to" && $cart['total_tax'] == $value) {
                    $condition_match[] = ['subject'=>$subject,'condition'=>$condition,'value'=>$value];
                }
                if($condition === "greater_than" && $cart['total_tax'] > $value) {
                    $condition_match[] = ['subject'=>$subject,'condition'=>$condition,'value'=>$value];
                }
                if($condition === "less_than" && $cart['total_tax'] < $value) {
                    $condition_match[] = ['subject'=>$subject,'condition'=>$condition,'value'=>$value];
                }
            }
        }

        if($condition_match == $rules['option_data']) {
            $shipping_add = 0;
            $shipping_discount = 0;
            if($option_name === "add_cost") {
                if($option_type === 'percent') {
                    $cart['shipping_add'] = $shipping_add = percent_increase($shipping_cost,$option_value, true);
                    $cart['shipping_cost'] = percent_increase($shipping_cost,$option_value);
                    $cart_tax += percent_increase($cart['shipping_tax'],$option_value, true);
                }
                if($option_type === 'fixed') {
                    $cart['shipping_add'] = $shipping_add = $shipping_cost + $option_value;
                    $cart['shipping_cost'] = $shipping_cost + $option_value;
                    $cart_tax += $cart['shipping_tax'] + $option_value;
                }
            }
            if($option_name === "discount") {
                if($option_type === 'percent') {
                    $cart['shipping_discount'] = $shipping_discount = percent_reduce($shipping_cost,$option_value,true);
                    $cart['shipping_cost'] = percent_reduce($shipping_cost,$option_value);
                    $cart_tax -= percent_reduce($cart['shipping_tax'],$option_value,true);
                }
                if($option_type === 'fixed') {
                    $cart['shipping_discount'] = $shipping_discount = ($shipping_cost - $option_value) < 0 ? 0 : $shipping_cost - $option_value;
                    $cart['shipping_cost'] = $shipping_cost - $option_value;
                    $cart_tax -= $cart['shipping_tax'] - $option_value;
                }
            }

            $cart['shipping_rule'] = [
                'option'=>$option_name,
                'value'=>$option_value,
                'type'=>$option_type
            ];
        }
    }

    if($cart_tax < 0) {
        $cart_tax = 0;
    }

    $cart['total_tax'] = $cart_tax;
    $cart['vat_price'] = $cart_tax;


    return $cart;
}