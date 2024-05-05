<?php

use PHPUnit\Framework\TestCase;
use App\Rule\ValidMathExpression;

class ValidMathExpressionTest extends TestCase
{
    public static function expressionProvider()
    {
        return [
            ['3+2', true],
            ['-3', true],
            ['(3+2)', true],
            ['3*5', true],
            ['-3+2', true],
            ['3+(2-5)', true],
            ['3(2-5)', true],
            ['3+(2-5)*4', true],
            ['3+(2-5)*4/(3+2)', true],
            ['3+', false],
            ['3+2)', false],
            ['(3+2', false],
            ['3(2-5)()', false],
            ['(3+2))', false],
            ['3**5', false],
            ['3_+2', false],
            ['3+(2-5)*4)', false],
        ];
    }

    /**
     * @dataProvider expressionProvider
     */
    public function testValidateMathExpression($expression, $expectedResult)
    {
        $validator = new ValidMathExpression();
        $exceptionThrown = false;
        try {
            $validator->validate('expression', $expression, function ($message) {
                throw new Exception($message);
            });
        } catch (Exception $e) {
            $exceptionThrown = true;
        }

        if ($expectedResult) {
            $this->assertFalse($exceptionThrown, "Did not expect an exception but one was thrown for expression: $expression");
        } else {
            $this->assertTrue($exceptionThrown, "Expected an exception to be thrown for expression: $expression");
        }
    }
}
