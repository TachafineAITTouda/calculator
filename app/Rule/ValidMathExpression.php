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
        $operators = CalculatorService::OPERATORS;

        $unwantedSequences = ['**', '*/', '/*', '//'];
        if (in_array($expression[0], $operators) || in_array($expression[strlen($expression) - 1], $operators)) {
            $errorPosition = in_array($expression[0], $operators) ? 0 : strlen($expression) - 1;
        }

        if ($errorPosition === null) {
            foreach ($operators as $operator) {
                $unwantedSequences = $operator . ')';
                if (strpos($expression, $unwantedSequences) !== false) {
                    $errorPosition = strpos($expression, $unwantedSequences);
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
