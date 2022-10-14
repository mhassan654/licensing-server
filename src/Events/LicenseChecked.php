<?php

namespace Mhassan654\LicenseServer\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use Mhassan654\LicenseServer\Models\License;

class LicenseChecked
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(License $license, array|null $data = [])
    {
        $this->license = $license;
        $this->data = $data;
    }
}
