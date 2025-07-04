<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;



use App\Mail\OrderConfirmationMail;
use App\Models\PromoCode;
use App\Models\Quote;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;


class UpdateOrderStatus extends Command
{
    protected $signature = 'policy:update-order-status';

    protected $description = 'Get  payment and update order status';

    public function __construct()
    {
        parent::__construct();
    }



    public function handle()
    {

        try {

            $date_before = Carbon::now()->subDays(1);

            // Get contributions to check
            $quotes = Quote::whereNot('payment_status', 1)
                ->where('created_at', '>', $date_before)
                ->whereNotNull('product_id')
                ->get();


            foreach ($quotes as $quote) {
                // Raw query to get order statuses for the product_id
                $orders = DB::select("
                SELECT orders.id AS order_id, orders.status AS order_status
                FROM wp_wc_orders AS orders
                JOIN wp_wc_order_product_lookup AS product_lookup
                    ON orders.id = product_lookup.order_id
                WHERE product_lookup.product_id = ?
            ", [$quote->product_id]);



                // Prioritize order statuses
                foreach ($orders as $order) {
                    if (in_array($order->order_status, ['wc-completed', 'wc-processing'])) {
                        

                        // Update event_users table
                        DB::update("
                        UPDATE quotes 
                        SET payment_status = 1 
                        WHERE payment_status != 1 
                        AND id = ?", [$quote->id]);


                        if($quote->user_id != null){

                            // ADJUST TIME
                            $quote = $this->adjustOrderStartTime($quote);
                            //WE WILL SEND CONFIRMATION MESSAGE HERE                    
                            Mail::to($quote->user()->first())->send(new OrderConfirmationMail($quote));

                        }



                    }
                }
            }

        } catch (\Throwable $e) {
            // Log the exact error
            \Log::error('Order confirmation mail failed: ' . $e->getMessage(), [
                'quote_id' => $quote->id ?? null,
                'trace' => $e->getTraceAsString(),
            ]);
        }


    }




    private function adjustOrderStartTime($quote)
    {

        // Combine start date and time into a timestamp
        $startTimestamp = strtotime("{$quote->start_date} {$quote->start_time}");
        $currentTimestamp = time(); // Current time based on the server's timezone

        // Check if the start time is behind the current time
        if ($startTimestamp <= $currentTimestamp) {
            
            // Calculate the next nearest 5th minute mark
            $adjustedStartTimestamp = ceil($currentTimestamp / 300) * 300;

            $quote->start_date = date('Y-m-d', $adjustedStartTimestamp);
            $quote->start_time = date('H:i:s', $adjustedStartTimestamp);

            // Calculate the duration difference and adjust the end time accordingly
            $endTimestamp = strtotime("{$quote->end_date} {$quote->end_time}");
            $duration = $endTimestamp - $startTimestamp; // Maintain the original duration
            $adjustedEndTimestamp = $adjustedStartTimestamp + $duration;

            $quote->end_date = date('Y-m-d', $adjustedEndTimestamp);
            $quote->end_time = date('H:i:s', $adjustedEndTimestamp);

            // Save the updated quote
            $quote->save();
        }

        return $quote;


    }




}
