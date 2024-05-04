<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalculatorRequest;
use App\Services\CalculatorService;

class CalculatorController extends Controller
{
    public function index()
    {
        return view('calculator');
    }

    public function calculate(CalculatorRequest $request, CalculatorService $calculator)
    {
        $result = '';
        $expression = $request->input('expression');
        try {
            $result = $calculator->calculate($expression);
        } catch (\Exception $e) {
            $result = $e->getMessage();
        }
        return view('calculator', [
            'expression' => $expression,
            'result' => $result
        ]);
    }


}
