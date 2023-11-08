<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Mail;
use App\Mail\Reminder;

use Carbon\Carbon;

class AutoReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:Reminder';
  
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
  
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        date_default_timezone_set('Africa/Lagos');
        
        $users = \App\Models\User::where('payment_status','NOT_PAID')->get();
  
        if (count($users) > 0) {
            foreach($users as $user){
                $endDate = Carbon::parse(Carbon::now()->subHours(167));
                $startDate = Carbon::parse(Carbon::now()->subHours(168));
                $dateToCheck = Carbon::parse($user->created_at);

                // CHECK IF USERS REGISTERED BETWEEN 7 DAYS & 1 HOUR AGO
                if ($dateToCheck->between($startDate, $endDate)) {
                    Mail::to($user->email)->send(new Reminder($user, 'week'));
                } else {
                    $endDate = Carbon::parse(Carbon::now()->subHours(72));
                    $startDate = Carbon::parse(Carbon::now()->subHours(73));
                    $dateToCheck = Carbon::parse($user->created_at);

                    // CHECK IF USERS REGISTERED BETWEEN 3 DAYS AND 1 HOUR AGO
                    if ($dateToCheck->between($startDate, $endDate)) {
                        Mail::to($user->email)->send(new Reminder($user, '3days'));
                    } else {
                        $endDate = Carbon::parse(Carbon::now()->subHours(1));
                        $startDate = Carbon::parse(Carbon::now()->subHours(3));
                        $dateToCheck = Carbon::parse($user->created_at);

                        // CHECK IF USERS REGISTERED BETWEEN 3 HOURS & 1 HOUR AFTER
                        if ($dateToCheck->between($startDate, $endDate)) {
                            Mail::to($user->email)->send(new Reminder($user, '3hours'));
                        }
                    }
                }
            }
        }
  
        return 0;
    }
}
