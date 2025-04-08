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
use App\Models\QuotePendingEmail;

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

        $limitTime = $now->subHours(4);  // Restar 4 horas

        
        $quotespendingemails = QuotePendingEmail::join('quotations', 'quote_pending_emails.quotation_id', '=', 'quotations.id')
            ->where('quote_pending_emails.type', 'Unqualified')
            ->where('quote_pending_emails.status', 'pending')
            ->where('quotations.created_at', '<=', $limitTime)
            ->select('quote_pending_emails.*')
            ->limit(8) // Limitar a 8 registros
            ->get();

        foreach ($quotespendingemails as $quote) {
            try {
                Mail::send(new QuoteUnqualifiedMail($quote, $quote->customer_name, $quote->email));
                $quote->status = 'sent';
                $quote->sent_at = Carbon::now();
                $quote->error_message = null;
                $quote->save();
            } catch (\Exception $e) {
                $quote->status = 'failed';
                $quote->error_message = $e->getMessage();
                $quote->save();
            }
        }

        $this->info('Cotizaciones actualizadas correctamente.');
    }
}
