<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\Quote;
use App\Mail\OrderExpiresMail;

class SendPolicyExpirationReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'policy:send-expiry-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send policy expiration reminders 10 minutes before the end time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dateTimeNow = date("Y-m-d H:i:s");
        $dateTimeNow10MinLater = date("Y-m-d H:i:s", strtotime("+10 minutes"));
        $dateTimeNow20MinLater = date("Y-m-d H:i:s", strtotime("+20 minutes"));

        
        DB::table('quotes')
            ->whereRaw("CONCAT(end_date, ' ', end_time) < ?", [$dateTimeNow])
            ->update(['expired_state' => 'expired']);

        // Fetch quotes expiring in the next 10 minutes and where email hasn't been sent yet
        $expireQuotes = Quote::where(function ($query) use ($dateTimeNow) {
            $query->whereNull('mail_sent')
                  ->orWhere('mail_sent', '<', $dateTimeNow);
        })
        ->where("payment_status", "1")
        ->whereNot("expired_state", "expired")
        ->whereNot("expired_state", "cancelled")
        ->whereRaw("CONCAT(end_date, ' ', end_time) <= ?", [$dateTimeNow10MinLater])
        ->get();

        foreach ($expireQuotes as $quote) {
            try {
                Mail::to($quote->user->email)->send(new OrderExpiresMail($quote));
                
                // Mark the quote as email sent
                $quote->mail_sent = $dateTimeNow20MinLater;
                $quote->save();

                $this->info("Reminder email sent to: {$quote->user->email} for quote ID: {$quote->id}");
            } catch (\Exception $e) {
                $this->error("Failed to send email for quote ID: {$quote->id}. Error: " . $e->getMessage());
            }
        }

        $this->info("Policy expiration reminders process completed.");
    }
}
