<?php

namespace App\Services;

use Exception;

interface CalculatorServiceInterface
{
    public function calculate(string $expression): float;

    public static function cleanExpression(string $expression): string;
}
