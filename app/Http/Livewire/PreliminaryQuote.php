<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;

class PreliminaryQuote extends Component
{
    /**
     * UI
     */
    public $ui_show_details = false;
    public $ui_show_modal_email = false;

    /**
     * Data
     */
    public $details = [];
    private $new_detail = [
        // quote details
        'mode'              => '',
        'service_type'      => '',
        'origin_type'       => '',
        'origin'            => '',
        'destination_type'  => '',
        'destination'       => '',
        'commodity'         => [],
        'cargo_details'     => '',
        'special_notes'     => '',
        'transit_time'      => '',
        'validity'          => '',
        // contact info
        'contact_name'      => '',
        'business_email'    => '',
        // pricing estimate
        'freight'           => '',
        'insurance'         => '',
        'concepts'          => [],
        // predata
        'predata'           => [
            'mode' => [
                'Ground FTL',
                'Ground LTL',
                'Ocean FCL',
                'Ocean LCL',
                'Ocean RoRo',
                'Ocean Breakbulk',
                'Ocean LOLO',
                'Air Freight',
            ],
            'service_type' => ['Door to Door'],
            'origin_type' => ['Door'],
            'destination_type' => ['Door'],
            'commodity' => [
                'General Cargo',
                'Fragile / Non-Stackable',
                'Hazardous',
                'Oversized',
            ],
            'validity' => [
                '7 days',
                '15 days',
                '30 days',
                'spot',
            ],
            'concepts' => [
                'Freight',
                'Pick Up',
                'Delivery',
                'Origin Charges',
                'Destination Charges',
                'Export Doc',
                'Insurance',
                'Customs Clearance Export',
                'Customs Clearance Import',
                'Other (Specify)',
            ]
        ],
    ];
    private $new_concept = [
        'concept'           => '',
        'price'             => '',
    ];
    public $message_email = '';


    public function rules() {
        $rules = [
            // quote details
            'details.*.mode'                => 'required',
            'details.*.service_type'        => 'required',
            'details.*.origin_type'         => 'required',
            'details.*.origin'              => 'required',
            'details.*.destination_type'    => 'required',
            'details.*.destination'         => 'required',
            'details.*.commodity'           => 'required|array|min:1',
            'details.*.cargo_details'       => 'required',
            'details.*.special_notes'       => 'nullable',
            'details.*.transit_time'        => 'required',
            'details.*.validity'            => 'required',
            // contact info
            'details.*.contact_name'        => 'required',
            'details.*.business_email'      => 'required|email',
            // pricing estimate
            'details.*.freight'             => 'required',
            'details.*.insurance'           => 'nullable',
            'details.*.concepts.*.concept'  => 'required',
            'details.*.concepts.*.price'    => 'required|numeric|min:0.01',
        ];
        return $rules;
    }

    public function validationAttributes() {
        return [
            // quote details
            'details.*.mode'                => 'mode',
            'details.*.service_type'        => 'service type',
            'details.*.origin'              => 'origin',
            'details.*.destination'         => 'destination',
            'details.*.commodity'           => 'commodity',
            'details.*.cargo_details'       => 'cargo details',
            'details.*.special_notes'       => 'special_notes',
            'details.*.transit_time'        => 'transit time',
            'details.*.validity'            => 'validity',
            // contact info
            'details.*.contact_name'        => 'contact name',
            'details.*.business_email'      => 'business email',
            // pricing estimate
            'details.*.freight'             => 'freight',
            'details.*.insurance'           => 'insurance',
            'details.*.concepts.*.concept'  => 'concept',
            'details.*.concepts.*.price'    => 'price',
        ];
    }

    public function mount() {
        $this->details[] = $this->new_detail;
    }

    public function render() {
        return view('livewire.preliminary-quote');
    }

    public function updating($property, $value) {
        // extrae index
        preg_match('/details\.(\d+)\.mode/', $property, $matches);
        $index = $matches[1] ?? null;

        if (Str::is('details.*.mode', $property)) {

            // comparaciones y asignaciones
            if (!is_null($index)) {
                // reset
                $this->details[$index]['service_type'] = '';
                $this->details[$index]['origin_type'] = '';
                $this->details[$index]['destination_type'] = '';
                $this->details[$index]['commodity'] = array_values(array_diff($this->details[$index]['commodity'], ['Self Propelled', 'Towable']));
                $this->details[$index]['predata']['concepts'] = array_values(array_diff($this->details[$index]['predata']['concepts'], ['Title Validation']));

                // default
                $predata_service_type       = ['Door to Door'];
                $predata_commodity          = ['General Cargo', 'Fragile / Non-Stackable', 'Hazardous', 'Oversized'];
                $predata_origin_type        = ['Door'];
                $predata_destination_type   = ['Door'];

                if ($value === 'Ground FTL' or $value === 'Ground LTL') {
                    $predata_service_type = array_merge($predata_service_type, ['Door to Border', 'Border to Door']);
                    $predata_origin_type = array_merge($predata_origin_type, ['Border', 'Ramp (Rail)']);
                    $predata_destination_type = array_merge($predata_destination_type, ['Border', 'Ramp (Rail)']);
                } elseif ($value === 'Ocean FCL') {
                    $predata_service_type = array_merge($predata_service_type, ['Door to Port', 'Port to Port', 'Port to Door']);
                    $predata_origin_type = array_merge($predata_origin_type, ['Port', 'Ramp (Rail)']);
                    $predata_destination_type = array_merge($predata_destination_type, ['Port', 'Ramp (Rail)']);
                } elseif ($value === 'Ocean LCL') {
                    $predata_service_type = array_merge($predata_service_type, ['Door to CFS', 'CFS to Door', 'CFS to CFS']);
                    $predata_origin_type = array_merge($predata_origin_type, ['CFS']);
                    $predata_destination_type = array_merge($predata_destination_type, ['CFS']);
                } elseif ($value === 'Air Freight') {
                    $predata_service_type = array_merge($predata_service_type, ['Door to Airport', 'Airport to Door', 'Airport to Airport']);
                    $predata_origin_type = array_merge($predata_origin_type, ['Airport']);
                    $predata_destination_type = array_merge($predata_destination_type, ['Airport']);
                } elseif ($value === 'Ocean RoRo') {
                    $predata_commodity = array_merge($predata_commodity, ['Self Propelled', 'Towable']);
                    array_unshift($this->details[$index]['predata']['concepts'], 'Title Validation');
                }

                $this->details[$index]['predata']['service_type'] = $predata_service_type;
                $this->details[$index]['predata']['commodity'] = $predata_commodity;
                $this->details[$index]['predata']['origin_type'] = $predata_origin_type;
                $this->details[$index]['predata']['destination_type'] = $predata_destination_type;
            }
        }
    }

    public function uiToggleDetails() {
        $this->ui_show_details = !$this->ui_show_details;
    }

    public function uiCloseModalEmail() {
        $this->ui_show_modal_email = false;
        $this->message_email = '';
        $this->resetErrorBag('message_email');
    }

    public function addDetail() {
        $this->details[] = $this->new_detail;
    }

    public function removeDetail($index) {
        unset($this->details[$index]);
        $this->details = array_values($this->details);
    }

    public function addDetailConcept($index) {
        $this->details[$index]['concepts'][] = $this->new_concept;
    }

    public function removeDetailConcept($index, $index_concept) {
        unset($this->details[$index]['concepts'][$index_concept]);
        $this->details[$index]['concepts'] = array_values($this->details[$index]['concepts']);
    }

    public function processDetails($type) {
        $data = $this->validate();

        switch ($type) {
            case 'send_email':
                $this->ui_show_modal_email = true;
                break;

            case 'download_pdf':
                $data = $this->details;
                $this->reset('details');
                $this->details[] = $this->new_detail;
                dd($data);
                break;

            default:
                break;
        }
    }

    public function send_email() {
        $this->validate([
            'message_email' => 'required',
        ], [], [
            'message_email' => 'message',
        ]);
        $this->ui_show_modal_email = false;
        $this->message_email = '';
        $this->reset('details');
        $this->details[] = $this->new_detail;
    }
}
