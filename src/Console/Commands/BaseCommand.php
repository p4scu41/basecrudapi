<?php

namespace p4scu41\BaseCRUDApi\Console\Commands;

use Illuminate\Console\Command;

/**
 * Commands Base Class
 *
 * @category Commands
 * @package  p4scu41\BaseCRUDApi\Console\Commands
 * @author   Pascual Pérez <pasperezn@gmail.com>
 * @created  2018-04-04
 */
class BaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

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
     * @return mixed
     */
    public function handle()
    {
        //
    }
}
