<?php

namespace App\Console\Commands;

use App\Models\User_status;
use Illuminate\Console\Command;

class setInactive extends Command
{
    
    protected $signature = 'app:set-user-inactive';

    protected $description = 'Command description';
  
    public function handle()
    {
        User_status::where("account_status","active")->where("last_activity","<=",now()->subDays(180))->update([
            "account_status"=>"inactive"
        ]);
    }
}
