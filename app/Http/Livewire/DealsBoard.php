<?php

namespace App\Http\Livewire;

use App\Models\GuestUser;
use App\Models\Quotation;
use App\Models\QuotationNote;
use App\Models\QuotePendingEmail;
use App\Models\UnreadQuotation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DealsBoard extends Component
{
    public $refreshKey = 0;
    public $user_sales;
    public $assignedUserId;
    public $filters = [
        'rating'        => [],
        'readiness'     => [],
        'inquiry_type'  => [],
        'source'        => [],
    ];

    public $show_modal = false;
    public $show_filters = false;
    public $is_filtering = false;
    public $board_active = 'open'; // open | awaiting

    public $icons = [
        'pending'       => '<svg width="49" height="49" viewBox="0 0 49 49" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M24.5 44.5C35.5457 44.5 44.5 35.5457 44.5 24.5C44.5 13.4543 35.5457 4.5 24.5 4.5C13.4543 4.5 4.5 13.4543 4.5 24.5C4.5 35.5457 13.4543 44.5 24.5 44.5Z" stroke="#EB6200" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M24.5 12.5V24.5L32.5 28.5" stroke="#EB6200" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        'contacted'     => '<svg width="48" height="49" viewBox="0 0 48 49" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M42 23.5001C42.0069 26.1398 41.3901 28.7438 40.2 31.1001C38.7889 33.9235 36.6195 36.2984 33.9349 37.9586C31.2503 39.6188 28.1565 40.4988 25 40.5001C22.3603 40.5069 19.7562 39.8902 17.4 38.7001L6 42.5001L9.8 31.1001C8.60986 28.7438 7.99312 26.1398 8 23.5001C8.00122 20.3436 8.88122 17.2498 10.5414 14.5652C12.2017 11.8806 14.5765 9.71119 17.4 8.30006C19.7562 7.10992 22.3603 6.49317 25 6.50006H26C30.1687 6.73004 34.1061 8.48958 37.0583 11.4418C40.0105 14.394 41.77 18.3314 42 22.5001V23.5001Z" stroke="#B28600" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        'stalled'       => '<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M19.9987 36.6666C29.2034 36.6666 36.6654 29.2047 36.6654 19.9999C36.6654 10.7952 29.2034 3.33325 19.9987 3.33325C10.794 3.33325 3.33203 10.7952 3.33203 19.9999C3.33203 29.2047 10.794 36.6666 19.9987 36.6666Z" stroke="#68C0FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M16.668 25V15" stroke="#68C0FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M23.332 25V15" stroke="#68C0FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        'qualified'     => '<svg width="49" height="49" viewBox="0 0 49 49" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M44.5 22.66V24.5C44.4975 28.8128 43.101 33.0093 40.5187 36.4636C37.9363 39.9179 34.3066 42.4449 30.1707 43.6678C26.0349 44.8906 21.6145 44.7438 17.5689 43.2491C13.5234 41.7545 10.0693 38.9922 7.72192 35.3741C5.37453 31.756 4.25958 27.4761 4.54335 23.1726C4.82712 18.8691 6.49441 14.7726 9.29656 11.4941C12.0987 8.21561 15.8856 5.93074 20.0924 4.98026C24.2992 4.02979 28.7005 4.46465 32.64 6.21997" stroke="#0A6AB7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M44.5 8.5L24.5 28.52L18.5 22.52" stroke="#0A6AB7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        'quote_sent'    => '<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M44 4L22 26" stroke="#6200EE" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M44 4L30 44L22 26L4 18L44 4Z" stroke="#6200EE" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
    ];
    public $filters_data = [
        'readiness' => [
            ['label' => 'ready now', 'style' => 'color: #4CBB17; border-color: #4CBB17', 'key' => 'Ready to ship now'],
            ['label' => '1-3 months', 'style' => 'color: #EB6200; border-color: #EB6200', 'key' => 'Ready within 1-3 months'],
            ['label' => 'budgeting', 'style' => 'color: #B28600; border-color: #B28600', 'key' => 'Not yet ready, just exploring options/budgeting'],
            // ['label' => 'N/A', 'style' => 'color: #686868; border-color: #686868', 'key' => 'null'],
        ],
        'statuses' => [
            'Pending'       => ['style' => 'color: #EB6200; background-color: #FFF2E8', 'label' => 'Pending'],
            'Contacted'     => ['style' => 'color: #B28600; background-color: #FCF4D6', 'label' => 'Contacted'],
            'Stalled'       => ['style' => 'color: #68C0FF; background-color: #EEF8FF', 'label' => 'Stalled'],
            'Qualified'     => ['style' => 'color: #0A6AB7; background-color: #D3EAFD', 'label' => 'Qualified'],
            'Quote Sent'    => ['style' => 'color: #1D813A; background-color: #E9F6ED', 'label' => 'Quote Sent'],
            'Unqualified'   => ['style' => 'color: #686868; background-color: #E8E8E8', 'label' => 'Unqualified'],
        ],
        'inquiry_type' => [
            'Manual' => [
                ['label' => 'Internal', 'key' => 'internal'],
            ],
            'Inbound' => [
                ['label' => 'Business', 'key' => 'external 2'],
                ['label' => 'Personal', 'key' => 'external 1'],
            ]
        ],
        'source' => [
            'External' => [
                ['label' => 'SEO', 'style' => 'color: #4CBB17; border-color: #4CBB17', 'key' => 'Search Engine'],
                ['label' => 'AIA', 'style' => 'color: #FF00FF; border-color: #FF00FF', 'key' => 'AI Assistant'],
                ['label' => 'LNK', 'style' => 'color: #0077B5; border-color: #0077B5', 'key' => 'LinkedIn'],
                ['label' => 'SOC', 'style' => 'color: #1877F2; border-color: #1877F2', 'key' => 'Social Media'],
                ['label' => 'PPC', 'style' => 'color: #6200EE; border-color: #6200EE', 'key' => 'ppc'],
                ['label' => 'EVT', 'style' => 'color: #008080; border-color: #008080', 'key' => 'Industry Event'],
                ['label' => 'REF', 'style' => 'color: #FFCC00; border-color: #FFCC00', 'key' => 'Referral'],
                ['label' => 'OTH', 'style' => 'color: #595959; border-color: #595959', 'key' => 'Other'],
            ],
            'Internal' => [
                ['label' => 'DIR', 'style' => 'color: #CC0000; border-color: #CC0000', 'key' => 'Direct Client'],
                ['label' => 'AGT', 'style' => 'color: #FF5F1F; border-color: #FF5F1F', 'key' => 'agt'],
            ],
        ],
    ];

    public $statuses = [
        'pending'       => ['label' => 'Pending', 'status' => 'Pending'],
        'contacted'     => ['label' => 'Contacted', 'status' => 'Contacted'],
        'stalled'       => ['label' => 'Stalled', 'status' => 'Stalled'],
        'qualified'     => ['label' => 'Qualified', 'status' => 'Qualified'],
        'quote_sent'    => ['label' => 'Quote Sent', 'status' => 'Quote Sent'],
    ];
    public $results = [
        'under_review'  => ['label' => 'Under Review', 'result' => 'Under Review'],
        'won'           => ['label' => 'Won', 'result' => 'Won'],
        'lost'          => ['label' => 'Lost', 'result' => 'Lost'],
    ];

    protected function queryString() {
        $query = [
            'filters' => ['except' => []],
        ];

        if (auth()->user()->hasRole('Administrator')) {
            $query[] = 'assignedUserId';
        }

        return $query;
    }

    public function mount() {
        // Asegurar todas las llaves
        $this->filters = array_merge([
            'rating'        => [],
            'readiness'     => [],
            'inquiry_type'  => [],
            'source'        => [],
        ], $this->filters ?? []);

        if (
            !empty($this->filters['rating']) ||
            !empty($this->filters['readiness']) ||
            !empty($this->filters['inquiry_type']) ||
            !empty($this->filters['source'])
        ) {
            $this->show_filters = true;
            $this->is_filtering = true;
        }

        // get first user result
        if (Auth::user()->hasRole('Administrator')) {
            $this->user_sales = User::whereHas('roles', function($query){
                    $query->whereIn('role_id', [1, 2]);
                })
                ->join('quotations', 'quotations.assigned_user_id', '=', 'users.id')
                ->groupBy('users.id')
                ->where('users.status', 'active')
                ->select('users.id as id', 'name', 'lastname')
                ->get();
            if (empty($this->assignedUserId)) {
                $this->assignedUserId = $this->user_sales[0]->id ?? null;
            }
        } else {
            $this->assignedUserId = auth()->id();
        }
        // $this->assignedUserId = 2731; // felipe testing
    }

    public function render() {
        return view('livewire.deals-board');
    }

    public function updating($property){
        if ($property == 'assignedUserId') {
            $this->updateParent();
        }
        if ($property == 'filters') {
            $this->is_filtering = true;
        }
    }

    public function updateParent() {
        $this->refreshKey++;
    }

    public function clearFilters() {
        $this->reset('filters', 'is_filtering');
        $this->refreshKey++;
    }

    public $modal_deal_data = [
        'quotation_id'  => '',
        'old_status'    => '',
        'status'        => '',
        'notes'         => '',
        'reason'        => '', // Unqualified
        'contacted_via' => '', // Contacted
    ];

    public function save_modal_data(){
        $validation = [
            'modal_deal_data.status'        => 'required|different:modal_deal_data.old_status',
            'modal_deal_data.old_status'    => 'required',
            'modal_deal_data.notes'         => 'nullable|string',
        ];

        if ($this->modal_deal_data['status']['label'] != 'Unqualified') {
            $validation['modal_deal_data.reason'] = 'nullable|string';
        } else {
            $validation['modal_deal_data.reason'] = 'required|string';
        }

        if ($this->modal_deal_data['status']['label'] != 'Contacted') {
            $validation['modal_deal_data.contacted_via'] = 'nullable|string';
        } else {
            $validation['modal_deal_data.contacted_via'] = 'required|string';
        }

        $this->validate($validation, [
            'modal_deal_data.status.different' => 'Choose a different status',
        ], [
            'modal_deal_data.status' => 'status',
            'modal_deal_data.reason' => 'reason',
            'modal_deal_data.notes' => 'notes',
            'modal_deal_data.contacted_via' => 'contacted via',
        ]);

        $form = $this->modal_deal_data;
        $form['status'] = $form['status']['label'];

        DB::transaction(function () use ($form) {
            $quotation = Quotation::findOrFail($form['quotation_id']);

            // remove unread quotation
            $quotation_unread = UnreadQuotation::where('quotation_id', $quotation->id)->first();
            if ($quotation_unread and $quotation_unread->user_id == auth()->id()) {
                $quotation_unread->delete();
            }

            if ($form['status'] == 'Unqualified'){
                if ($form['notes'] == '') {
                    $form['notes'] = 'Auto-Decline Email Sent';
                } else {
                    $form['notes'] = $form['notes'] . ' - Auto-Decline Email Sent';
                }
            } else {
                $form['notes'] = $form['notes'] ?? 'N/A';
            }

            // Insertar la nota de estado
            $q_note = [
                'quotation_id' => $quotation->id,
                'type' => 'inquiry_status',
                'action' => "'{$quotation->status}' to '{$form['status']}'",
                'reason' => $form['reason'] ?? '',
                'note' => $form['notes'],
                'user_id' => auth()->id(),
            ];
            if ($form['status'] == 'Contacted') {
                $q_note['contacted_via'] = $form['contacted_via'];
            }
            QuotationNote::create($q_note);

            // Actualizar el estado de la inscripción después de registrar la nota
            $quotation->update([
                'status' => $form['status'],
                'updated_at' => now(),
            ]);

            // if action 'Quote Sent' update 'result'
            if ($form['status'] == 'Quote Sent') {
                $quotation->update([
                    'result' => 'Under Review',
                    'updated_at' => now(),
                ]);
                QuotationNote::create([
                    'quotation_id' => $quotation->id,
                    'type' => 'result_status',
                    'action' => "'' to 'Under Review'",
                    'reason' => '',
                    'note' => 'Result status auto-updated',
                    'user_id' => '1',
                ]);
            }

            // Unqualified
            if ($form['status'] == 'Unqualified') {
                //Obtener el usuario (guest o registrado)
                $customer = $quotation->customer_user_id
                    ? User::find($quotation->customer_user_id)
                    : GuestUser::find($quotation->guest_user_id);

                if ($customer && !empty($customer->email)) {
                    $customer_name = trim(($customer->name ?? '') . ' ' . ($customer->lastname ?? ''));

                    // Registrar a la tabla QuotePendingEmail
                    QuotePendingEmail::create([
                        'quotation_id' => $quotation->id,
                        'type' => 'Unqualified',
                        'customer_name' => $customer_name,
                        'email' => $customer->email,
                        'status' => 'Pending',
                        'created_at' => now(),
                    ]);
                }
            }
        });

        // finish
        $this->show_modal = false;
        $this->reset('modal_deal_data');
        $this->refreshKey++;
    }
}
