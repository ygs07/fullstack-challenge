<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;

use function PHPUnit\Framework\throwException;

class UpdateWeatherData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = 10;

    protected $cachedUsers;


    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->cachedUsers = Redis::get('users.all');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $users = User::all();

        Redis::set("users.all", $users);
    }

    public function retryUntil()
    {
        return now()->addMinute();
    }

    public function failed($e)
    {
        Redis::set("users.all", $this->cachedUsers);
    }
}
