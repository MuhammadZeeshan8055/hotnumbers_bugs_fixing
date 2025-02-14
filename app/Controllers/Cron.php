<?php

namespace App\Controllers;

use App\Models\MailModel;

class Cron extends BaseController
{

    public function subscriptions()
    {
        $subscriptionModel = model('SubscriptionModel');

        $current_day_renewals = $subscriptionModel->current_day_renewals();
        $expired_subscriptions = $subscriptionModel->get_expired("AND o.status='active'");

        if (!empty($current_day_renewals)) {
            foreach ($current_day_renewals as $subscription) {
                $subscriptionModel->renew($subscription->order_id);
            }
        }

        if (!empty($expired_subscriptions)) {
            foreach ($expired_subscriptions as $subscription) {
                $subscriptionModel->expire($subscription->order_id);
            }
        }
    }
    public function check_14_days_expiry()
    {
        $userModel = model('UserModel');
        $mail = new MailModel();
        $db = db_connect();
        $processed_users = []; // Track unique users

        foreach (activeSubscription() as $subscription) {
            $user_id = $subscription['customer_user'];

            // ✅ Skip duplicate users before any processing
            if (isset($processed_users[$user_id])) continue;

            $user_info = $userModel->get_user($user_id);
            if (!$user_info) continue; // Safety check if user doesn't exist

            $user_name = $user_info->name;
            $user_email = $user_info->email;

            // Fetch card details
            $card_meta = $db->query("SELECT value FROM tbl_card_meta WHERE user_id = ?", [$user_id])->getResultArray();

            foreach ($card_meta as $meta) {
                $card = json_decode($meta['value'], true)['card'] ?? null;
                if (!$card) continue;

                $expiry_date = strtotime("{$card['exp_year']}-{$card['exp_month']}-01 +1 month -1 day");
                $reminder_date = strtotime("-14 days", $expiry_date);

                echo "User ID: $user_id - Expiry: " . date("Y-m-d", $expiry_date) . " - Reminder: " . date("Y-m-d", $reminder_date) . "<br>";

                if (time() >= $reminder_date) {
                    echo "✅ Send email reminder to: $user_email<br>";

                    //sending reminder email
                    $mailbody = $mail->get_parsed_content('before_14_days_card_expiry', [
                        'display_name' => $user_name
                    ]);
            
                    $mail->send_email($user_email, $mailbody);
            
                    notice_success('Email Sent Successfully');
                }
            }

            // ✅ Mark user as processed immediately
            $processed_users[$user_id] = true;
        }
    }

    public function check_07_days_expiry() {

        $userModel = model('UserModel');
        $mail = new MailModel();
        $db = db_connect();
        $processed_users = []; // Track unique users
    
        foreach (activeSubscription() as $subscription) {
            $user_id = $subscription['customer_user'];
    
            // ✅ Skip duplicate users before any processing
            if (isset($processed_users[$user_id])) continue;
    

            $user_info = $userModel->get_user($user_id);
            if (!$user_info) continue; // Safety check if user doesn't exist

            $user_name = $user_info->name;
            $user_email = $user_info->email;

            // Fetch card details
            $card_meta = $db->query("SELECT value FROM tbl_card_meta WHERE user_id = ?", [$user_id])->getResultArray();
    
            foreach ($card_meta as $meta) {
                $card = json_decode($meta['value'], true)['card'] ?? null;
                if (!$card) continue;
    
                $expiry_date = strtotime("{$card['exp_year']}-{$card['exp_month']}-01 +1 month -1 day");
                $reminder_date = strtotime("-7 days", $expiry_date);
    
                echo "User ID: $user_id - Expiry: " . date("Y-m-d", $expiry_date) . " - Reminder: " . date("Y-m-d", $reminder_date) . "<br>";
    
                if (time() >= $reminder_date) {
                    echo "✅ Send email reminder<br>";


                    //sending reminder email
                    $mailbody = $mail->get_parsed_content('before_07_days_card_expiry', [
                        'display_name' => $user_name
                    ]);
            
                    $mail->send_email($user_email, $mailbody);
            
                    notice_success('Email Sent Successfully');

                }
            }
    
            // ✅ Mark user as processed immediately
            $processed_users[$user_id] = true;
        }
    }
    


    public function testing_send_email()
    {
        $mail = new MailModel();

        $mailbody = $mail->get_parsed_content('before_14_days_card_expiry', [
            'display_name' => 'Muhammad Zeeshan'
        ]);

        $mail->send_email('muhammadzeeshan8055@gmail.com', $mailbody);

        notice_success('Testing Email Sent Successfully');
    }

    // $mail = new MailModel();

    // $mailbody = $mail->get_parsed_content('password_change', [
    //     'display_name'=>$user_row['display_name']
    // ]);

    // $mail->send_email($user_row['email'],$mailbody);

    // $success_message .= "<p>Your password has been updated successfully</p>";


    // ===================================


    // $mail = new MailModel();
    // $mailbody = $mail->get_parsed_content('password_change', [
    //     'display_name'=>$user_row['display_name']
    // ]);

    // $mail->send_email($user_row['email'],$mailbody);

    // notice_success('Password Updated Successfully');
    // return redirect()->to(base_url("account/edit-account"));

}
