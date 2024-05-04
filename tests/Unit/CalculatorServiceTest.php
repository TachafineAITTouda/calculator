<?php

use PHPUnit\Framework\TestCase;
use App\Services\CalculatorService;

class CalculatorServiceTest extends TestCase
{
    public static function calculationProvider()
    {
        return [
            ['3+2', 5.0],
            ['3-2', 1.0],
            ['3*2', 6.0],
            ['3/2', 1.5],
            ['(3+2)*5', 25.0],
            ['(3+2)*5/5', 5.0],
            ['3+2*2', 7.0],
            ['(3+2)*2', 10.0],
            ['3+(2*2)', 7.0],
            ['3+(2*2)/2', 5.0],
            ['3+(2*(2+1))', 9.0],
            ['3/0', null]
        ];
    }

    /**
     * @dataProvider calculationProvider
     */
    public function testCalculate($expression, $expectedResult)
    {
        $calculator = new CalculatorService();
        if ($expectedResult === null) {
            $this->expectException(\InvalidArgumentException::class);
        }

        $result = $calculator->calculate($expression);
        $this->assertEquals($expectedResult, $result, "Expression evaluated incorrectly: $expression");
    }
}
