<?php

namespace App\Http\Livewire;

use App\Enums\TypeInquiry;
use App\Enums\TypeStatus;
use App\Models\GuestUser;
use App\Models\Quotation;
use App\Models\QuotationNote;
use App\Models\QuotePendingEmail;
use App\Models\UnreadQuotation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        'qualified'     => '<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M23.3333 3.33333H9.99999C9.11593 3.33333 8.26809 3.68452 7.64297 4.30964C7.01785 4.93476 6.66666 5.78261 6.66666 6.66666V33.3333C6.66666 34.2174 7.01785 35.0652 7.64297 35.6903C8.26809 36.3155 9.11593 36.6667 9.99999 36.6667H30C30.884 36.6667 31.7319 36.3155 32.357 35.6903C32.9821 35.0652 33.3333 34.2174 33.3333 33.3333V13.3333L23.3333 3.33333Z" stroke="#0A6AB7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M23.3333 3.33333V13.3333H33.3333" stroke="#0A6AB7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M26.6667 21.6667H13.3333" stroke="#0A6AB7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M26.6667 28.3333H13.3333" stroke="#0A6AB7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M16.6667 15H15H13.3333" stroke="#0A6AB7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        'quote_sent'    => '<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M44 4L22 26" stroke="#6200EE" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M44 4L30 44L22 26L4 18L44 4Z" stroke="#6200EE" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
    ];
    public $filters_data;

    public $statuses;
    public $results = [
        'under_review'  => ['label' => 'Under Review', 'result' => 'Under Review'],
        'won'           => ['label' => 'Won', 'result' => 'Won'],
        'lost'          => ['label' => 'Lost', 'result' => 'Lost'],
    ];

    protected function queryString() {
        $query = [
            'filters' => ['except' => []],
        ];

        if (auth()->user()->hasRole('Administrator') || auth()->user()->hasRole('Leader')) {
            $query[] = 'assignedUserId';
        }

        return $query;
    }

    public function mount() {
        $this->statuses = [
            'pending'       => ['label' => TypeStatus::PENDING->meta('label'), 'status' => TypeStatus::PENDING->value],
            'contacted'     => ['label' => TypeStatus::CONTACTED->meta('label'), 'status' => TypeStatus::CONTACTED->value],
            'stalled'       => ['label' => TypeStatus::STALLED->meta('label'), 'status' => TypeStatus::STALLED->value],
            'qualified'     => ['label' => TypeStatus::QUALIFIED->meta('label'), 'status' => TypeStatus::QUALIFIED->value],
            'quote_sent'    => ['label' => TypeStatus::QUOTE_SENT->meta('label'), 'status' =>  TypeStatus::QUOTE_SENT->value],
        ];
        $this->filters_data = [
            'readiness' => [
                ['label' => 'ready now', 'style' => 'color: #4CBB17; border-color: #4CBB17', 'key' => 'Ready to ship now'],
                ['label' => '1-3 months', 'style' => 'color: #EB6200; border-color: #EB6200', 'key' => 'Ready within 1-3 months'],
                ['label' => 'budgeting', 'style' => 'color: #B28600; border-color: #B28600', 'key' => 'Not yet ready, just exploring options/budgeting'],
                // ['label' => 'N/A', 'style' => 'color: #686868; border-color: #686868', 'key' => 'null'],
            ],
            'statuses' => TypeStatus::deals_change_status_list(),
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

        if (!Auth::user()->hasRole('Administrator')) {
            Log::info('-- debug department_id ' . auth()->user()->department_id);
            if (auth()->user()->department_id === 1) { // SEO / House
                $this->filters_data['inquiry_type'] = [
                    'Internal' => [
                        ['label' => TypeInquiry::INTERNAL->label(), 'key' => TypeInquiry::INTERNAL->value],
                        ['label' => TypeInquiry::INTERNAL_OTHER->label(), 'key' => TypeInquiry::INTERNAL_OTHER->value],
                    ],
                    'External' => [
                        ['label' => TypeInquiry::EXTERNAL_2->label(), 'key' => TypeInquiry::EXTERNAL_2->value],
                        ['label' => TypeInquiry::EXTERNAL_1->label(), 'key' => TypeInquiry::EXTERNAL_1->value],
                    ]
                ];
            } elseif (auth()->user()->department_id === 2) { // Agents
                $this->filters_data['inquiry_type'] = [
                    'Internal' => [
                        ['label' => TypeInquiry::INTERNAL_LEGACY->label(), 'key' => TypeInquiry::INTERNAL_LEGACY->value],
                        ['label' => TypeInquiry::INTERNAL_OTHER_AGT->label(), 'key' => TypeInquiry::INTERNAL_OTHER_AGT->value],
                    ],
                    'External' => [
                        ['label' => TypeInquiry::EXTERNAL_SEO_RFQ->label(), 'key' => TypeInquiry::EXTERNAL_SEO_RFQ->value],
                    ]
                ];
                Log::info('-- debug filters inquiry type ' . $this->filters_data['inquiry_type']);
            }
        } else {
            $this->filters_data['inquiry_type'] = [
                'Internal' => [
                    ['label' => TypeInquiry::INTERNAL->label(), 'key' => TypeInquiry::INTERNAL->value],
                    ['label' => TypeInquiry::INTERNAL_OTHER->label(), 'key' => TypeInquiry::INTERNAL_OTHER->value],
                    ['label' => TypeInquiry::INTERNAL_LEGACY->label(), 'key' => TypeInquiry::INTERNAL_LEGACY->value],
                    ['label' => TypeInquiry::INTERNAL_OTHER_AGT->label(), 'key' => TypeInquiry::INTERNAL_OTHER_AGT->value],
                ],
                'External' => [
                    ['label' => TypeInquiry::EXTERNAL_2->label(), 'key' => TypeInquiry::EXTERNAL_2->value],
                    ['label' => TypeInquiry::EXTERNAL_1->label(), 'key' => TypeInquiry::EXTERNAL_1->value],
                    ['label' => TypeInquiry::EXTERNAL_SEO_RFQ->label(), 'key' => TypeInquiry::EXTERNAL_SEO_RFQ->value],
                ]
            ];
        }

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
        if (Auth::user()->hasRole('Administrator') or Auth::user()->hasRole('Leader')) {
            $user_sales_dpto = User::whereHas('roles', function($query) {
                    if (Auth::user()->hasRole('Administrator')) {
                        $query->whereIn('role_id', [1, 2]); // admin, sales
                    } else {
                        $query->whereIn('role_id', [6]); // quoter
                    }
                })
                ->join('quotations', 'quotations.assigned_user_id', '=', 'users.id')
                ->groupBy('users.id')
                ->where('users.status', 'active')
                ->select('users.id as id', 'name', 'lastname', 'users.department_id')
                ->with('department');

            if (Auth::user()->hasRole('Leader')) {
                $user_sales_dpto->where('users.department_id', auth()->user()->department_id); // del mismo dpto
            }

            $user_sales_dpto = $user_sales_dpto->get();
            if (Auth::user()->hasRole('Administrator')) {
                $user_sales_dpto_arr = [];
            } else {
                if (Auth::user()->hasRole('Leader')) {
                    $user_sales_dpto_arr[auth()->user()->department->name][] = [
                        'id' => auth()->id(),
                        'name' => auth()->user()->name,
                        'lastname' => auth()->user()->lastname,
                    ];
                }
            }

            if (count($user_sales_dpto->toArray()) > 0) {
                foreach ($user_sales_dpto->toArray() as $user) {
                    $user_data = [
                        'id' => $user['id'],
                        'name' => $user['name'],
                        'lastname' => $user['lastname'],
                    ];
                    if (isset($user['department']['name'])) {
                        $user_sales_dpto_arr[$user['department']['name']][] = $user_data;
                    } else {
                        $user_sales_dpto_arr['Other'][] = $user_data;
                    }
                }
            }
            $this->user_sales = $user_sales_dpto_arr;
            if (empty($this->assignedUserId)) {
                if (Auth::user()->hasRole('Administrator')) {
                    $this->assignedUserId = $user_sales_dpto[0]->id ?? null;
                } else {
                    if (Auth::user()->hasRole('Leader')) {
                        $this->assignedUserId = auth()->id();
                    }
                }
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
        'process_for'   => '', // Qualified
    ];

    public function save_modal_data(){
        $validation = [
            'modal_deal_data.status'        => 'required|different:modal_deal_data.old_status',
            'modal_deal_data.old_status'    => 'required',
            'modal_deal_data.notes'         => 'nullable|string',
        ];

        if ($this->modal_deal_data['status']['keyValue'] != 'Unqualified') {
            $validation['modal_deal_data.reason'] = 'nullable|string';
        } else {
            $validation['modal_deal_data.reason'] = 'required|string';
        }

        if ($this->modal_deal_data['status']['keyValue'] != 'Contacted') {
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
        $form['status'] = $form['status']['keyValue'];

        DB::transaction(function () use ($form) {
            $quotation = Quotation::findOrFail($form['quotation_id']);

            // remove unread quotation
            $quotation_unread = UnreadQuotation::where('quotation_id', $quotation->id)->where('user_id', auth()->id())->first();
            if ($quotation_unread and $quotation_unread->user_id == auth()->id()) {
                // verificar si ya tiene un read (si tiene hay que reemplazarlo) -> subsanación de error
                $quotation_unread->delete();
                $have_read = QuotationNote::where([
                        ['type', 'read'],
                        ['quotation_id', $quotation->id],
                        ['user_id', auth()->id()],
                    ])->first();
                if (!$have_read) {
                    // register as read
                    QuotationNote::create([
                        'quotation_id' => $quotation->id,
                        'type' => 'read',
                        'action' => '',
                        'reason' => '',
                        'note' => '',
                        'user_id' => auth()->id(),
                    ]);
                } else {
                    $have_read->update(['user_id' => auth()->id()]);
                }
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
            if ($form['status'] == TypeStatus::QUALIFIED->value) {
                $q_note['process_for'] = $form['process_for'];
                $quotation->update([
                    'process_for' => $form['process_for'],
                ]);
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
