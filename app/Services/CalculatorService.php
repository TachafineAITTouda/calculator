<?php

namespace App\Services;

class CalculatorService
{
    public const OPERATORS = [
        '+' => 1, '-' => 1, '*' => 2, '/' => 2
    ];

    public function calculate(string $expression): float
    {
        $expression = self::cleanExpression($expression);
        return $this->evaluate($expression);
    }

    public static function cleanExpression(string $expression): string
    {
        $expression = str_replace([' ', 'x', '÷'], ['', '*', '/'], $expression);
        $expression = str_replace(['(-', ',-'], ['(0-', ',0-'], $expression);
        return preg_replace('/(?<=[0-9)])(\()/i', '*(', $expression);
    }

    private function isOperator(string $char): bool
    {
        return isset(self::OPERATORS[$char]);
    }

    private function precedence(string $operator): int
    {
        return self::OPERATORS[$operator] ?? 0;
    }

    private function shuntingYard(string $expression): array
    {
        $output = [];
        $stack = [];
        $tokens = preg_split('/(\d+|\+|\-|\*|\/|\(|\))/', $expression, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

        foreach ($tokens as $token) {
            if (is_numeric($token)) {
                $output[] = $token;
            } elseif ($token === '(') {
                array_push($stack, $token);
            } elseif ($token === ')') {
                while (($top = array_pop($stack)) !== '(') {
                    $output[] = $top;
                }
            } elseif ($this->isOperator($token)) {
                while (!empty($stack) && $this->precedence(end($stack)) >= $this->precedence($token)) {
                    $output[] = array_pop($stack);
                }
                array_push($stack, $token);
            }
        }

        while (!empty($stack)) {
            $output[] = array_pop($stack);
        }

        return $output;
    }

    private function evaluate(string $expression): float
    {
        $postfix = $this->shuntingYard($expression);
        $stack = [];

        foreach ($postfix as $token) {
            if (is_numeric($token)) {
                array_push($stack, (float) $token);
            } elseif ($this->isOperator($token)) {
                $operand2 = array_pop($stack);
                $operand1 = array_pop($stack);
                $result = $this->applyOperation($operand1, $operand2, $token);
                array_push($stack, $result);
            }
        }

        return end($stack);
    }

    private function applyOperation(float $operand1, float $operand2, string $operator): float
    {
        switch ($operator) {
            case '+':
                return $operand1 + $operand2;
            case '-':
                return $operand1 - $operand2;
            case '*':
                return $operand1 * $operand2;
            case '/':
                if ($operand2 == 0) {
                    throw new \InvalidArgumentException('NAN');
                }
                return $operand1 / $operand2;
            default:
                throw new \InvalidArgumentException('Invalid operator ' . $operator);
        }
    }
}
