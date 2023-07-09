<?php

declare(strict_types=1);

namespace App\Constant;

enum CashFlowTypes
{
    public const INCOME = 'income';
    public const EXPENSE = 'expense';

    public const TYPES_ARRAY = [
        self::INCOME,
        self::EXPENSE
    ];
}