<?php

namespace App\Contracts;

interface TenantContext
{
    public function id(): int;
}