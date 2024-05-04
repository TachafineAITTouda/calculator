<?php

namespace App\Rule;

use App\Services\CalculatorService;
use Closure;
use Exception;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidMathExpression implements ValidationRule
{
    public string $expression;

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $this->expression = $value;
        try {
            $this->cleanExpression();
            $this->validateCharacters();
            $this->validateOperatorsPlacement();
            $this->validateParentheses();
        } catch (Exception $e) {
            $fail($e->getMessage());
        }
    }

    private function cleanExpression(): void
    {
        $this->expression = str_replace(' ', '', $this->expression);
    }

    private function validateParentheses(): bool
    {
        $expression = $this->expression;

        $expressionPrentheses = preg_replace('/[^\(\)]/', '', $expression);
        $openParentheses = substr_count($expressionPrentheses, '(');
        $closeParentheses = substr_count($expressionPrentheses, ')');
        if ($openParentheses !== $closeParentheses) {
            $errorPosition = strpos($expression, $openParentheses > $closeParentheses ? '(' : ')');
            $highlightedExpression = $this->ighlightedExpression($expression, $errorPosition);
            throw new Exception('Unbalanced parentheses: ' . $highlightedExpression);
        }

        $openParenthesesPositions = [];
        $closeParenthesesPositions = [];
        for ($i = 0; $i < strlen($expression); $i++) {
            if ($expression[$i] === '(') {
                $openParenthesesPositions[] = $i;
            } elseif ($expression[$i] === ')') {
                $closeParenthesesPositions[] = $i;
            }
        }
        foreach ($openParenthesesPositions as $key => $position) {
            if ($position > $closeParenthesesPositions[$key]) {
                $highlightedExpression = $this->ighlightedExpression($expression, $position);
                throw new Exception('Unbalanced parentheses: ' . $highlightedExpression);
            }
        }


        return true;
    }

    private function validateCharacters(): bool
    {
        if (preg_match('/[^0-9\+\-\*\/\(\)]/', $this->expression)) {
            $invalidCharacters = preg_replace('/[0-9\+\-\*\/\(\)]/', '', $this->expression);
            throw new Exception('Invalid characters: ' . $invalidCharacters);
        }else{
            return true;
        }
    }

    private function validateOperatorsPlacement():bool
    {
        $expression = $this->expression;
        $errorPosition = null;
        $operators = array_keys(CalculatorService::OPERATORS);

        for ($i = 0; $i < strlen($expression); $i++) {
            $char = $expression[$i];
            if (in_array($char, $operators)) {
                if ($i === 0 && $char !== '-') {
                    $errorPosition = $i;
                    break;
                } elseif ($i === strlen($expression) - 1) {
                    $errorPosition = $i;
                    break;
                } elseif (in_array($expression[$i + 1], $operators)) {
                    $errorPosition = $i + 1;
                    break;
                } elseif ($char !== '-' && $expression[$i + 1] === ')') {
                    $errorPosition = $i + 1;
                    break;
                } elseif ($char !== '-' && $expression[$i - 1] === '(') {
                    $errorPosition = $i - 1;
                    break;
                } elseif ($char !== '-' && in_array($expression[$i - 1], $operators)) {
                    $errorPosition = $i - 1;
                    break;
                }
            }
        }

        if ($errorPosition !== null) {
            $highlightedExpression = $this->ighlightedExpression($expression, $errorPosition);
            throw new Exception('Malformed expression: ' . $highlightedExpression);
        }

        return true;
    }

    public function ighlightedExpression(string $expression, int $position): string
    {
        $errorMessage = substr($expression, 0, $position) . '<' . substr($expression, $position, 1) . '>' . substr($expression, $position + 1);
        $errorMessage = str_replace(['<', '>'], ['<span style="color:red; font-weight:bold;">', '</span>'], $errorMessage);
        return $errorMessage;
    }

}
