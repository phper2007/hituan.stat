<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserAddress;
use App\Services\ExpressService;

class updateExpress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateExpress';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update express';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $userList = (new UserAddress())->get();
        $expressService = new ExpressService();

        foreach ($userList as $user)
        {
            $expressService->searchExpress($user);
        }
        echo 'ok';
    }
}
