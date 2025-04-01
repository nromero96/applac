<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Quotation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use App\Helpers\helpers;
use App\Models\QuotationNote;
use App\Mail\QuoteUnqualifiedMail;
use App\Models\User;
use App\Models\GuestUser;

use Illuminate\Support\Facades\Log;

class UpdateQuoteStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quotes:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualizar el estado de cotizaciones con 0 estrella después de 4 horas';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        // Ajustar zona horaria a Toronto
        $now = Carbon::now('America/Toronto');
        $hour = $now->hour;

        // Verificar si está dentro del horario laboral (9 AM - 5 PM)
        if ($hour < 9 || $hour >= 17) {
            return;
        }

        $limitTime = Carbon::now()->subHours(4);

        $quotes = Quotation::where('status', 'Pending')
            ->where('rating', 0)
            ->where('created_at', '<=', $limitTime)
            ->whereNot(function ($query) {
                $query->where('mode_of_transport', 'RoRo')
                    ->where('cargo_type', 'Personal Vehicle');
            })
            ->get();

        foreach ($quotes as $quote) {

            //Obetener nombre y email del usuario guest
            $customer = $quote->customer_user_id 
                        ? User::find($quote->customer_user_id)
                        : GuestUser::find($quote->guest_user_id);

            if ($customer) {
                $customer_name = trim(($customer->name ?? '') . ' ' . ($customer->lastname ?? ''));
                $email = $customer->email ?? 'Correo no disponible';
            }
            
            //Registrar si el correo fue enviado
            QuotationNote::create([
                'quotation_id' => $quote->id,
                'type' => 'inquiry_status',
                'action' => "'{$quote->status}' to 'Unqualified'",
                'reason' => 'Low Rating Auto-Decline',
                'note' => 'Low Rating Request - Auto-Decline Email Sent',
                'user_id' => 1,
            ]);
            //registrar nota

            $quote->update(['status' => 'Unqualified']);

            // Enviar correo
            Mail::send(new QuoteUnqualifiedMail($quote, $customer_name, $email));
            //Log
            Log::info('Low Rating Request - Auto-Decline Email Sent for Quote ID: #' . $quote->id);
            
        }

        $this->info('Cotizaciones actualizadas correctamente.');
    }
}
