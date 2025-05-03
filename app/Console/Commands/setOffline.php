<?php

namespace App\Console\Commands;

use App\Models\User_status;
use Illuminate\Console\Command;

class setOffline extends Command
{
   
    protected $signature = 'app:set-user-offline';

    protected $description = 'Command description';
   
    public function handle()
    {
        User_status::where("account_status","active")->where("last_activity","<=",now()->subMinute(5))->update([
            "status"=>"offline"
        ]);
    }
}
