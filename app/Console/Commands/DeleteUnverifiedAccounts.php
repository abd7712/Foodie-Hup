<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteUnverifiedAccounts extends Command
{
    
    protected $signature = 'app:delete-unverified-accounts';
   
    protected $description = 'Command description';
    
    public function handle()
    {
        $now=Carbon::now();
        User::where("email_verified_at",null)->where("created_at","<=",$now->subDays(2))->delete();
    }
}
