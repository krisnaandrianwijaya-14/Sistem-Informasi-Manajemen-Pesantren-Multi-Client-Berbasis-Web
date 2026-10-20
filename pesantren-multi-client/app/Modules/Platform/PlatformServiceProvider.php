<?php

namespace App\Modules\Platform;

use App\Modules\ModuleServiceProvider;

class PlatformServiceProvider extends ModuleServiceProvider
{
    protected function moduleAlias(): string
    {
        return 'platform';
    }
}
