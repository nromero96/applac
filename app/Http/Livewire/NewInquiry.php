<?php

namespace App\Http\Livewire;

use App\Enums\TypeInquiry;
use App\Models\Organization;
use App\Models\OrganizationContact;
use App\Services\InternalInquiryService;
use Livewire\Component;
use Livewire\WithFileUploads;

class NewInquiry extends Component
{
    use WithFileUploads;

    protected $listeners = [
        'clean_data_after_close',
    ];

    // org
    public $org_id;
    public $org_name;
    public $org_code;

    // org contact
    public $contact = [
        'id' => '',
        'name' => '',
        'job_title' => '',
        'email' => '',
        'phone' => '',
    ];

    // inquiry
    public $member;
    public $source;
    public $rating;
    public $shipping_date;
    public $recovered_account = false;
    public $cargo_description;
    public $attachments = [];
    public $type_inquiry;
    public $tier;
    public $score;
    public $mode_of_transport;
    public $location;
    public $network = [];
    public $referred_by = false;
    public $cargo_details = [];
    public $additional_info = [];

    // aux
    public $members;
    public $member_sales_role;
    public $attachments_added = [];
    public $org_selected = false;
    public $contacts = [];
    public $organizations = [];
    public $new_contact = false;
    public $update_contact = false;
    public $rating_label = 'Select Rating';
    public $rating_star_icon = '<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.00065 0.333344L9.06065 4.50668L13.6673 5.18001L10.334 8.42668L11.1207 13.0133L7.00065 10.8467L2.88065 13.0133L3.66732 8.42668L0.333984 5.18001L4.94065 4.50668L7.00065 0.333344Z" fill="#EDB10C"/></svg>';
    public $source_label = 'Select Source';
    public $sources_list = [
        [
            'Direct Client' => ['key' => 'DIR', 'label' => 'Direct Client', 'color' => '#CC0000'],
            'agt' => ['key' => 'AGT', 'label' => 'Agent', 'color' => '#FF5F1F'],
            'Referral' => ['key' => 'REF', 'label' => 'Referral', 'color' => '#FFCC00'],
        ],
        [
            'Search Engine' => ['key' => 'SEO', 'label' => 'Search Engine', 'color' => '#4CBB17'],
            'AI Assistant' => ['key' => 'AIA', 'label' => 'AI Assistant', 'color' => '#FF00FF'],
            'LinkedIn' => ['key' => 'LNK', 'label' => 'LinkedIn', 'color' => '#0077B5'],
            'Social Media' => ['key' => 'SOC', 'label' => 'Social Media', 'color' => '#1877F2'],
            'Industry Event' => ['key' => 'EVT', 'label' => 'Industry Event', 'color' => '#008080'],
            'Other' => ['key' => 'OTH', 'label' => 'Other', 'color' => '#595959'],
        ]
    ];
    public $stored = false;
    public $savedRouteTo = 'quotations.index';
    public $types_inquiries;
    public $network_options;
    public $location_list;
    public $mode_of_transport_options;
    public $cargo_details_options = [];
    public $additional_info_options = [];

    public function rules() {
        $rules = [
            'org_name' => 'required|max:255',
            'org_code' => 'nullable|max:50',
            'member' => 'required',
            'source' => 'nullable',
            'score' => 'nullable|numeric|between:0,500',
            'rating' => 'nullable',
            'shipping_date' => 'nullable',
            'contact.name' => 'required|max:255',
            'contact.job_title' => 'nullable|max:255',
            'contact.email' => 'nullable|max:255|email',
            'contact.phone' => 'nullable|max:20',
            'attachments.*' => 'max:2048',
            'type_inquiry' => 'required',
            'mode_of_transport' => 'required',
        ];

        if ($this->type_inquiry == TypeInquiry::INTERNAL_OTHER->value) {
            $rules['source'] = 'required';
            $rules['rating'] = 'required';
        }

        return $rules;
    }

    public function messages() {
        return [
            'attachments.*.max' => 'The attachments must not be greater than 2MB.'
        ];
    }

    public function validationAttributes() {
        return [
            'contact.name' => 'contact name',
            'contact.job_title' => 'contact job title',
            'contact.email' => 'contact email',
            'contact.phone' => 'contact phone',
            'attachments.*' => 'attachments',
        ];
    }

    public function mount(InternalInquiryService $internalInquiryService) {
        $data = $internalInquiryService->mount_data();
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function render() {
        return view('livewire.new-inquiry');
    }

    public function updatedOrgName() {
        if ($this->org_name != '') {
            $this->organizations = Organization::where('name', 'LIKE', "%$this->org_name%")->select('id', 'name')->get();
        } else {
            $this->reset('org_code', 'organizations', 'contacts', 'contact');
        }
    }

    public function updatedTypeInquiry() {
        $this->reset('org_selected', 'org_id', 'org_name', 'org_code', 'contact', 'tier', 'score', 'location', 'network', 'referred_by', 'mode_of_transport');
        $this->emit('send-network-tom-select', $this->network);
    }

    public function updatedContact($value, $name){
        if ($name == 'email') {
            $this->contact['email'] = trim($value);
        }
    }

    public function updatedModeOfTransport($value) {
        $this->reset('cargo_details');
        // cargo_details_list
        $internalInquiryService = new InternalInquiryService();
        $internalInquiryService->relationTransportAndCargoDetails($this);
    }

    public function store(InternalInquiryService $internalInquiryService) {
        $internalInquiryService->store($this);
    }

    public function select_org(Organization $org){
        $this->org_id = $org->id;
        $this->org_name = $org->name;
        $this->org_code = $org->code;
        $this->contacts = $org->contacts;
        $this->tier = $org->tier;
        $this->score = $org->score;
        $this->location = $org->country_id;
        $this->network = $org->network;
        $this->recovered_account = $org->recovered_account;
        $this->referred_by = $org->referred_by;
        $this->contact = $org->contacts->first()->toArray();
        // activar actualizacion de contact sin data
        if ($this->contact['email'] == '' || $this->contact['phone'] == '' || $this->contact['job_title'] == '') {
            $this->update_contact = true;
        } else {
            $this->update_contact = false;
        }
        // clean errors
        $this->resetErrorBag(['org_name', 'org_code', 'contact.name', 'contact.job_title', 'contact.email', 'contact.phone']);
        //
        $this->org_selected = true;
        $this->organizations = [];
        $this->emit('send-network-tom-select', $this->network);
    }

    public function select_contact(OrganizationContact $contact){
        $this->contact = $contact->toArray();
        // activar actualizacion de contact sin data
        if ($this->contact['email'] == '' || $this->contact['phone'] == '' || $this->contact['job_title'] == '') {
            $this->update_contact = true;
        } else {
            $this->update_contact = false;
        }
    }

    public function add_new_contact(){
        $this->new_contact = true;
        $this->reset('contact');
    }

    public function cancel_new_contact(){
        $this->resetErrorBag(['contact.name', 'contact.job_title', 'contact.email', 'contact.phone']);
        $this->new_contact = false;
        $this->contact = $this->contacts[0]->toArray();
    }

    public function set_rating($value) {
        if ($value != '') {
            $this->rating_label = $this->rating_draw_stars($value);
        } else {
            $this->reset('rating_label');
        }
        $this->rating = $value;
    }

    public function rating_draw_stars($value) {
        $rating_value = '';
        for ($i = 1; $i <= $value; $i++) {
            $rating_value .= $this->rating_star_icon;
        }
        return '<span>' . $rating_value . '</span>' . ' <span style="transform: translateY(1px);">' . $value . ' star'. ($value > 1 ? 's' : '') .'</span>';
    }

    public function set_source($value, $group = null) {
        if ($value != '') {
            $this->source_label = $this->source_draw_label($value, $group);
        } else {
            $this->reset('source_label');
        }
        $this->source = $value;
    }

    public function source_draw_label($source_key, $group) {
        $source = $this->sources_list[$group][$source_key];
        $label = '';
        $label .= '<div class="newinquiry__source">';
        $label .= '<span style="color:'. $source['color'] .'; border-color:.' . $source['color'] . '">';
        $label .= $source['key'];
        $label .= '</span>';
        $label .= $source['label'];
        $label .= '</div>';
        return $label;
    }

    public function reset_data(){
        // // $this->reset('org_selected', 'org_id', 'org_name', 'org_code', 'contact', 'contacts', 'new_contact', 'update_contact');
        $this->reset('org_id', 'org_name', 'org_code', 'contact', 'source', 'rating', 'recovered_account', 'cargo_description', 'org_selected', 'contacts', 'organizations', 'new_contact', 'rating_label', 'source_label', 'attachments', 'attachments_added', 'shipping_date', 'tier', 'score', 'location', 'network', 'referred_by', 'mode_of_transport');
        $this->resetErrorBag();
        $this->emit('send-network-tom-select', $this->network);
    }

    public function clean_data_after_close(){
        $this->reset('org_id', 'org_name', 'org_code', 'contact', 'source', 'rating', 'recovered_account', 'cargo_description', 'org_selected', 'contacts', 'organizations', 'new_contact', 'rating_label', 'source_label', 'attachments', 'attachments_added', 'shipping_date', 'tier', 'score', 'location', 'network', 'referred_by', 'mode_of_transport');
        $this->resetErrorBag();
        $this->emit('send-network-tom-select', $this->network);
    }

    /**
     * Attachments
     */
    public $attach_dropping = false;

    public function updatedAttachmentsAdded(){
        $this->attachments = array_merge($this->attachments, $this->attachments_added);
    }

    public function formatSizeAttachment($size) {
        if ($size < 1024) {
            return $size . ' bytes';
        } elseif ($size < 1048576) {
            return round($size / 1024, 2) . ' KB';
        } else {
            return round($size / 1048576, 2) . ' MB';
        }
    }

    public function attach_toggleDropping($value) {
        $this->attach_dropping = $value;
    }

    public function attach_toggleDropped($value) {
        $this->attach_dropping = $value;
    }

    public function attach_remove($index) {
        array_splice($this->attachments, $index, 1);
    }
}
