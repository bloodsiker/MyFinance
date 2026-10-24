<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        $user = new User();
        $user->name = 'Dima';
        $user->email = 'maldini2@ukr.net';
        $user->role_id = 1;
        $user->password = Hash::make('germaniya88dd');
        $user->save();

        $user = new User();
        $user->name = 'Vika';
        $user->email = 'o.vika@gmail.com';
        $user->role_id = 1;
        $user->password = Hash::make('germaniya88dd');
        $user->save();
    }
}
