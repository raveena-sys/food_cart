<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\JobRepository;

class DeleteImageJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:DeleteImageJob';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for delete image in every thirty minutes';

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
    JobRepository::deleteImageInThirtyMinuteByCron();
 }
}
