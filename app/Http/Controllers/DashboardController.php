<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//DashboardModel
use App\Models\User;
use App\Models\Quotation;

use Carbon\Carbon;

class DashboardController extends Controller{
    public function index(){
        // $category_name = '';
        $data = [
            'category_name' => '',
            'page_name' => 'dashboard',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
        ];
        
        // Obtener todos los usuarios con roles 'Employee' y 'Administrator'
        $users = User::role(['Employee', 'Administrator'])->get();

        // Inicializar el array de usuarios con cotizaciones
        $usersQuotes = [];

        // Iterar sobre los usuarios
        foreach ($users as $user) {
            // Inicializar el array de cotizaciones por estado
            $quotes = [
                'Processing' => 0,
                'Attended' => 0,
                'Quote Sent' => 0,
                'Total' => 0,
                '4 stars' => 0,
                '4 stars last day' => 0
            ];

            // Obtener las cotizaciones asignadas al usuario actual
            $userQuotes = Quotation::where('assigned_user_id', $user->id)->get();

            // Verificar si el usuario tiene cotizaciones
            if ($userQuotes->isEmpty()) {
                continue; // Saltar usuarios sin cotizaciones
            }

            // Iterar sobre las cotizaciones del usuario
            foreach ($userQuotes as $quote) {
                // Incrementar el conteo del estado correspondiente
                if (isset($quotes[$quote->status])) {
                    $quotes[$quote->status]++;
                } else {
                    // Manejar estados inesperados si es necesario
                    $quotes[$quote->status] = 1;
                }

                // Incrementar el conteo total
                $quotes['Total']++;

                // Incrementar el conteo de cotizaciones con 4 estrellas
                if ($quote->rating == 4) {
                    $quotes['4 stars']++;
                }

                // Incrementar el conteo de cotizaciones con 4 estrellas ultimas 24 horas
                // Obtener la fecha actual Carbon
                $now = Carbon::now();
                $yesterday = $now->copy()->subDay();

                // Verificar si la cotización fue creada en las últimas 24 horas
                if ($quote->created_at >= $yesterday && $quote->rating == 4) {
                    $quotes['4 stars last day']++;
                }



            }

            // Añadir el usuario al array de usuarios con sus cotizaciones
            $usersQuotes[] = [
                'id' => $user->id,
                'name' => $user->name,
                'lastname' => $user->lastname,
                'email' => $user->email,
                'quotes' => $quotes
            ];
        }

        // Pasar los datos a la vista
        $data['usersQuotes'] = $usersQuotes;
        

        return view('dashboard.index')->with($data);
    }


}
