<?php

namespace App\Modules\Dormitory;

use App\Modules\ModuleServiceProvider;

class DormitoryServiceProvider extends ModuleServiceProvider
{
    protected function moduleAlias(): string
    {
        return 'dormitory';
    }
}
