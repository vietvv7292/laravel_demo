<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderShippedTest
{
    use Dispatchable, SerializesModels;

    public $orderId;

    /**
     * Create a new event instance.
     *
     * @param  string  $orderId
     * @return void
     */
    public function __construct($orderId)
    {
        $this->orderId = $orderId;
    }
}
