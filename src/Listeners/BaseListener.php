<?php

namespace p4scu41\BaseCRUDApi\Listeners;

use p4scu41\BaseCRUDEvents\BaseEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Listeners Base Class
 *
 * @category Listeners
 * @package  p4scu41\BaseCRUDApi\Listeners
 * @author   Pascual Pérez <pasperezn@gmail.com>
 * @created  2018-04-04
 */
class BaseListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param BaseEvent $event Event instance
     *
     * @return void
     */
    public function handle(BaseEvent $event)
    {
        //
    }
}
