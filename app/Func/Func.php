<?php

namespace App\Func;


class Func
{

    public $mail = null;

    public function __construct() {}



       

    public static function replaceContent($quote, $html)
    {
        // Convert quote object to an array
        $quoteArray = $quote->toArray();


        // Format and add new/updated variables
        $quoteArray['start_date'] = date('d-m-Y', strtotime($quote->start_date . ' ' . $quote->start_time));
        $quoteArray['start_time'] = date('H:i', strtotime($quote->start_date . ' ' . $quote->start_time));

        $quoteArray['end_date'] = date('d-m-Y', strtotime($quote->end_date . ' ' . $quote->end_time));
        $quoteArray['end_time'] = date('H:i', strtotime($quote->end_date . ' ' . $quote->end_time));

        $quoteArray['date_of_birth'] = date('d-m-Y', strtotime($quote->date_of_birth));

        $quoteArray['appn_name'] = config('app_name');

        $quoteArray['view_document_link'] = '<a href="'.url('/view-order/'.$quote->policy_number).'" class="center btn">View your documents</a>';

        $quoteArray['new_order_link'] = '<a href="'. url('/order/get-quote').'" class="button">Get a new order</a>';


        // Combine start date and time
        $start = new \DateTime($quote->start_date . " " . $quote->start_time);

        // Combine end date and time
        $end = new \DateTime($quote->end_date . " " . $quote->end_time);

        // Calculate the difference
        $interval = $start->diff($end);

        // Format the output
        $days = $interval->days; // Total days
        $hours = $interval->h;   // Remaining hours

        // Build the duration string
        $duration = '';
        if ($days > 0) {
            $duration .= $days . " day" . ($days > 1 ? "s" : "");
        }

        if ($hours > 0) {
            if (!empty($duration)) {
                $duration .= " and ";
            }
            $duration .= $hours . " hour" . ($hours > 1 ? "s" : "");
        }

        $sub_total =  number_format($quote->cpw, 2);
        $discoun_amount = number_format(($quote->cpw - $quote->update_price), 2);
        $total_cost = number_format($quote->update_price, 2);



        $quoteArray['duration'] = $duration;
        $quoteArray['sub_total'] = $sub_total;
        $quoteArray['discoun_amount'] = $discoun_amount;
        $quoteArray['total_cost'] = $total_cost;

        // echo json_encode($quoteArray); die();
        // Replace all placeholders in the HTML
        foreach ($quoteArray as $key => $value) {
            if(! is_array($value)){
            $html = str_replace("[$key]", $value, $html);
            }
        }

        return $html;
    }
}
