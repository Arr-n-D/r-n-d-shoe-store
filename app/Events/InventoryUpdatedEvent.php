<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InventoryUpdatedEvent
{
    use Dispatchable, SerializesModels;

    public string $store;
    public string $shoe;
    public int $quantity;
   
    public function __construct(array $data)
    {
        $this->store = $data['store'];
        $this->shoe = $data['model'];
        $this->quantity = $data['inventory'];
    }
}
