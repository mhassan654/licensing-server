<?php

namespace Mhassan654\LicenseServer;

use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use Mhassan654\LicenseServer\Events\LicenseChecked;
use Mhassan654\LicenseServer\Exceptions\ListenerNotFoundException;

final class EventServiceProvider extends ServiceProvider
{
    public function __construct($app)
    {
        parent::__construct($app);

        $eventListeners = [];

        $licenseCheckedListener = Config::get('license-server.event_listeners.license_checked');

        if ($licenseCheckedListener) {
            if (!class_exists($licenseCheckedListener)) {
                throw new ListenerNotFoundException('LicenseChecked listener class not found');
            }

            $eventListeners[LicenseChecked::class] = [
                $licenseCheckedListener
            ];
        }

        $this->listen = $eventListeners;
    }

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
