<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogicTestController extends Controller
{
    // This is the function that we want to test

    public function assignedQuote()
    {
        $quotation_id = 13;

        echo "Quotation ID: " . $quotation_id . "<br>";
        echo "--------------------------<br>";

        //$rating = rateQuotation($quotation_id);

        //return $rating;

    }

}
