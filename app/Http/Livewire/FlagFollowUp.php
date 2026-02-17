<?php

namespace App\Http\Livewire;

use App\Models\FeaturedQuotation;
use App\Models\ScheduledQuotation;
use App\Models\TaggedQuotation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class FlagFollowUp extends Component
{
    // ui
    public $ui_show_modal_flag = false;
    public $ui_show_modal_schedule = false;
    public $ui_show_modal_tag = false;

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

    public $tag = [
        'priority'  => '',
        'notes'     => '',
    ];
    public $isTag = false;

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
                    WHEN 'Hot Deal' THEN 'hot'
                    WHEN 'Potential Lead' THEN 'potential'
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
                    WHEN 'Hot Deal' THEN 'hot'
                    WHEN 'Potential Lead' THEN 'potential'
                    ELSE ''
                END as priority_class
            "),
        )->first();

        // tag
        $this->isTag = TaggedQuotation::where([
            ['user_id', auth()->id()],
            ['quotation_id', $this->quotationId],
        ])->select(
            '*',
            DB::raw("
                CASE priority
                    WHEN 'High Priority' THEN 'high'
                    WHEN 'Medium Priority' THEN 'medium'
                    WHEN 'Low Priority' THEN 'low'
                    WHEN 'Hot Deal' THEN 'hot'
                    WHEN 'Potential Lead' THEN 'potential'
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
                case 'hot': $priority = 'Hot Deal'; break;
                case 'potential': $priority = 'Potential Lead'; break;
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
        if (!empty($this->schedule['date']) &&
            Carbon::hasFormat($this->schedule['date'], 'd-m-Y')) {

            $this->schedule['date'] = Carbon::createFromFormat('d-m-Y', $this->schedule['date'])
                ->format('Y-m-d');
        }

        if ($this->quotationPriority) {
            $priority = '';
            switch ($this->quotationPriority) {
                case 'low': $priority = 'Low Priority'; break;
                case 'medium': $priority = 'Medium Priority'; break;
                case 'high': $priority = 'High Priority'; break;
                case 'hot': $priority = 'Hot Deal'; break;
                case 'potential': $priority = 'Potential Lead'; break;
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

    /**
     * TAG
     */
    public function open_modal_tag($new = true) {
        if (!$new) {
            $this->editingMode = true;
            $item = TaggedQuotation::where([
                ['user_id', auth()->id()],
                ['quotation_id', $this->quotationId],
            ])->first();
            if ($item) {
                $this->tag['notes'] = $item->notes;
                $this->tag['priority'] = $item->priority;
            }
        }
        $this->ui_show_modal_tag = true;
    }

    public function close_modal_tag() {
        $this->ui_show_modal_tag = false;
        $this->reset('tag', 'editingMode');
        $this->resetErrorBag('tag.*');
    }

    public function save_tag() {
        $rules = [
            'tag.priority' => 'required|string',
            'tag.notes'    => 'nullable|string',
        ];
        if ($this->quotationPriority) {
            $rules['tag.priority'] = 'nullable|string';
            $priority = '';
            switch ($this->quotationPriority) {
                case 'low': $priority = 'Low Priority'; break;
                case 'medium': $priority = 'Medium Priority'; break;
                case 'high': $priority = 'High Priority'; break;
                case 'hot': $priority = 'Hot Deal'; break;
                case 'potential': $priority = 'Potential Lead'; break;
                default: break;
            }
            $this->tag['priority'] = $priority;
        }
        $this->validate($rules, [], [
            'tag.priority' => 'priority',
            'tag.notes'    => 'notes',
        ]);
        if (!$this->editingMode) {
            TaggedQuotation::create([
                'user_id'       => auth()->id(),
                'quotation_id'  => $this->quotationId,
                'notes'         => $this->tag['notes'],
                'priority'      => $this->tag['priority'],
            ]);
        } else {
            TaggedQuotation::where([
                ['user_id', auth()->id()],
                ['quotation_id', $this->quotationId],
            ])->update([
                'notes'    => $this->tag['notes'],
                'priority' => $this->tag['priority'],
            ]);
        }
        $this->ui_show_modal_tag = false;
        $this->reset('tag', 'editingMode');
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

            case 'tag':
                TaggedQuotation::where([
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
