<?php

namespace App\Http\Livewire;

use App\Models\FeaturedQuotation;
use App\Models\ScheduledQuotation;
use Livewire\Component;

class AgendaButton extends Component
{
    protected $listeners = ['agenda_button_update'];

    public $quotations_total = 0;

    public function render() {
        $this->agenda_button_update();
        return view('livewire.agenda-button');
    }

    public function agenda_button_update() {
        $flag = FeaturedQuotation::where('user_id', auth()->id())->get();
        $schedule = ScheduledQuotation::where('user_id', auth()->id())->get();
        $this->quotations_total = $flag->count() + $schedule->count();
    }
}
