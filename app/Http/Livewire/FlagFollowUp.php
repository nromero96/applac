<?php

namespace App\Http\Livewire;

use App\Models\FeaturedQuotation;
use App\Models\ScheduledQuotation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class FlagFollowUp extends Component
{
    // ui
    public $ui_show_modal_flag = false;
    public $ui_show_modal_schedule = false;

    // app
    public $quotationId;
    public $quotationPriority;
    public $flag = [
        'priority'  => '',
        'notes'     => '',
    ];
    public $isFlag = false;
    public $schedule = [
        'date'      => '',
        'priority'  => '',
        'notes'     => '',
    ];
    public $isScheduled = false;

    // states
    public $editingMode = false;

    public function render() {
        // flag
        $this->isFlag = FeaturedQuotation::where([
            ['user_id', auth()->id()],
            ['quotation_id', $this->quotationId],
        ])->select(
            '*',
            DB::raw("
                CASE priority
                    WHEN 'High Priority' THEN 'high'
                    WHEN 'Medium Priority' THEN 'medium'
                    WHEN 'Low Priority' THEN 'low'
                    ELSE ''
                END as priority_class
            "),
        )->first();

        // scheduled
        $this->isScheduled = ScheduledQuotation::where([
            ['user_id', auth()->id()],
            ['quotation_id', $this->quotationId],
        ])->select(
            '*',
            DB::raw("
                CASE priority
                    WHEN 'High Priority' THEN 'high'
                    WHEN 'Medium Priority' THEN 'medium'
                    WHEN 'Low Priority' THEN 'low'
                    ELSE ''
                END as priority_class
            "),
        )->first();

        $this->emit('agenda_button_update');
        $this->emit('agenda_update');

        return view('livewire.flag-follow-up');
    }

    /**
     * FLAG
     */
    public function open_modal_flag($new = true) {
        if (!$new) {
            $this->editingMode = true;
            $item = FeaturedQuotation::where([
                ['user_id', auth()->id()],
                ['quotation_id', $this->quotationId],
            ])->first();
            if ($item) {
                $this->flag['notes'] = $item->notes;
                $this->flag['priority'] = $item->priority;
            }
        }
        $this->ui_show_modal_flag = true;
    }

    public function close_modal_flag() {
        $this->ui_show_modal_flag = false;
        $this->reset('flag', 'editingMode');
        $this->resetErrorBag('flag.*');
    }

    public function save_flag() {
        $rules = [
            'flag.priority' => 'required|string',
            'flag.notes'    => 'nullable|string',
        ];
        if ($this->quotationPriority) {
            $rules['flag.priority'] = 'nullable|string';
            $priority = '';
            switch ($this->quotationPriority) {
                case 'low': $priority = 'Low Priority'; break;
                case 'medium': $priority = 'Medium Priority'; break;
                case 'high': $priority = 'High Priority'; break;
                default: break;
            }
            $this->flag['priority'] = $priority;
        }
        $this->validate($rules, [], [
            'flag.priority' => 'priority',
            'flag.notes'    => 'notes',
        ]);
        if (!$this->editingMode) {
            FeaturedQuotation::create([
                'user_id'       => auth()->id(),
                'quotation_id'  => $this->quotationId,
                'notes'         => $this->flag['notes'],
                'priority'      => $this->flag['priority'],
            ]);
        } else {
            FeaturedQuotation::where([
                ['user_id', auth()->id()],
                ['quotation_id', $this->quotationId],
            ])->update([
                'notes'    => $this->flag['notes'],
                'priority' => $this->flag['priority'],
            ]);
        }
        $this->ui_show_modal_flag = false;
        $this->reset('flag', 'editingMode');
    }

    /**
     * SCHEDULE
     */
    public function open_modal_schedule($new = true) {
        if (!$new) {
            $this->editingMode = true;
            $item = ScheduledQuotation::where([
                ['user_id', auth()->id()],
                ['quotation_id', $this->quotationId],
            ])->first();
            if ($item) {
                $this->schedule['date'] = $item->date;
                $this->schedule['notes'] = $item->notes;
                $this->schedule['priority'] = $item->priority;
            }
        }
        $this->ui_show_modal_schedule = true;
    }

    public function close_modal_schedule() {
        $this->ui_show_modal_schedule = false;
        $this->reset('schedule', 'editingMode');
        $this->resetErrorBag('schedule.*');
    }

    public function save_schedule() {

        // Convertir formato dd-mm-yyyy a yyyy-mm-dd
        if (!empty($this->schedule['date'])) {
            $this->schedule['date'] = Carbon::createFromFormat('d-m-Y', $this->schedule['date'])->format('Y-m-d');
        }

        if ($this->quotationPriority) {
            $priority = '';
            switch ($this->quotationPriority) {
                case 'low': $priority = 'Low Priority'; break;
                case 'medium': $priority = 'Medium Priority'; break;
                case 'high': $priority = 'High Priority'; break;
                default: break;
            }
            $this->schedule['priority'] = $priority;
        }

        $this->validate([
            'schedule.date'     => 'required|date|after_or_equal:today',
            'schedule.priority' => 'nullable|string',
            'schedule.notes'    => 'nullable|string',
        ], [], [
            'schedule.date'     => 'date',
            'schedule.priority' => 'priority',
            'schedule.notes'    => 'notes',
        ]);
        if (!$this->editingMode) {
            ScheduledQuotation::create([
                'user_id'       => auth()->id(),
                'quotation_id'  => $this->quotationId,
                'date'          => $this->schedule['date'],
                'notes'         => $this->schedule['notes'],
                'priority'      => $this->schedule['priority'],
            ]);
        } else {
            ScheduledQuotation::where([
                ['user_id', auth()->id()],
                ['quotation_id', $this->quotationId],
            ])->update([
                'date'      => $this->schedule['date'],
                'notes'     => $this->schedule['notes'],
                'priority'  => $this->schedule['priority'],
            ]);
        }

        $this->ui_show_modal_schedule = false;
        $this->reset('schedule', 'editingMode');
    }

    //
    public function remove_chin($mode) {
        switch ($mode) {
            case 'flag':
                FeaturedQuotation::where([
                    ['user_id', auth()->id()],
                    ['quotation_id', $this->quotationId],
                ])->delete();
                break;

            case 'schedule':
                ScheduledQuotation::where([
                    ['user_id', auth()->id()],
                    ['quotation_id', $this->quotationId],
                ])->delete();
                break;

            default:
                break;
        }
        $this->reset('flag', 'editingMode', 'editingMode');
    }
}
