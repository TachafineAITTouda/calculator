<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalculatorRequest;
use Illuminate\Http\Request;

class CalculatorController extends Controller
{
    public function index()
    {
        return view('calculator');
    }

    public function calculate(CalculatorRequest $request)
    {
        $result = 0;
        $expression = $request->input('expression');

        return view('calculator', [
            'expression' => $expression,
            'result' => $result
        ]);
    }


}
