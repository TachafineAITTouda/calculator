<?php

namespace App\Services;

use Exception;

interface CalculatorServiceInterface
{
    public function calculate(string $expression): float;

    public function cleanExpression(string $expression): string;
}
