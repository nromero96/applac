<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogicTestController extends Controller
{
    // This is the function that we want to test

    public function assignedQuote()
    {
        $quotation_id = 0;

        echo "Quotation ID: " . $quotation_id . "<br>";
        echo "--------------------------<br>";

        if($quotation_id > 0 ){
            $rating = rateQuotation($quotation_id);
            return $rating;
        }

    }

}
