<?php

namespace App\Http\Livewire;

use App\Models\FeaturedQuotation;
use App\Models\ScheduledQuotation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Agenda extends Component
{
    protected $listeners = ['agenda_update'];

    public $modal_mode = true;
    public $ui_show_agenda = false;

    public $flagged;
    public $scheduled;
    public $scheduled_today;

    public $user_id = null;

    public function render() {
        if (!isset($this->user_id)) {
            $this->user_id = auth()->id();
        }
        $this->agenda_update();
        return view('livewire.agenda');
    }

    public function agenda_update() {
        $this->flagged = FeaturedQuotation::where('user_id', $this->user_id)
            ->select(
                '*',
                'featured_quotations.*',
                'quotations.type_inquiry',
                DB::raw("
                    CASE featured_quotations.priority
                        WHEN 'High Priority' THEN 'high'
                        WHEN 'Medium Priority' THEN 'medium'
                        WHEN 'Low Priority' THEN 'low'
                        ELSE ''
                    END as priority_class
                "),
                DB::raw('COALESCE(users.name, guest_users.name) as customer_name'),
                DB::raw('COALESCE(users.lastname, guest_users.lastname) as customer_lastname'),
                DB::raw('COALESCE(users.email, guest_users.email) as customer_email'),
            )
            ->join('quotations', 'quotations.id', '=', 'featured_quotations.quotation_id')
            ->leftJoin('users', 'quotations.customer_user_id', '=', 'users.id')
            ->leftJoin('guest_users', 'quotations.guest_user_id', '=', 'guest_users.id')
            ->orderBy('featured_quotations.created_at', 'DESC')
            ->get();

        $scheduled = ScheduledQuotation::where('user_id', $this->user_id)
            ->select(
                '*',
                'scheduled_quotations.*',
                'quotations.type_inquiry',
                DB::raw("
                    CASE scheduled_quotations.priority
                        WHEN 'High Priority' THEN 'high'
                        WHEN 'Medium Priority' THEN 'medium'
                        WHEN 'Low Priority' THEN 'low'
                        ELSE ''
                    END as priority_class
                "),
                DB::raw('COALESCE(users.name, guest_users.name) as customer_name'),
                DB::raw('COALESCE(users.lastname, guest_users.lastname) as customer_lastname'),
                DB::raw('COALESCE(users.email, guest_users.email) as customer_email'),
            )
            ->join('quotations', 'quotations.id', '=', 'scheduled_quotations.quotation_id')
            ->leftJoin('users', 'quotations.customer_user_id', '=', 'users.id')
            ->leftJoin('guest_users', 'quotations.guest_user_id', '=', 'guest_users.id')
            ->orderBy('scheduled_quotations.date');

        $today = Carbon::today();

        $this->scheduled = (clone $scheduled)->whereDate('date', '!=', $today)->get();
        $this->scheduled_today = (clone $scheduled)->whereBetween('date', [$today, $today->copy()->addDays(7)])->get();;
    }
}
