<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogicTestController extends Controller
{
    // This is the function that we want to test

    public function assignedQuote($quotation_id)
    {
        echo "Quotation ID: " . $quotation_id . "<br>";
        echo "--------------------------<br>";

        // \Log::info('Ejecutando rateQuotation para la cotización: ' . $quotation_id);
        // $rating = rateQuotation($quotation_id);
        // return $rating;

    }

}
