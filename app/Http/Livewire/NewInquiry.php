<?php

namespace App\Http\Livewire;

use App\Models\GuestUser;
use App\Models\Organization;
use App\Models\OrganizationContact;
use App\Models\Quotation;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class NewInquiry extends Component
{
    protected $listeners = ['clean_data_after_close'];

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
    public $recovered_account = false;
    public $cargo_description;

    // aux
    public $org_selected = false;
    public $contacts = [];
    public $organizations = [];
    public $new_contact = false;
    public $rating_label = 'Select Rating';
    public $rating_star_icon = '<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.00065 0.333344L9.06065 4.50668L13.6673 5.18001L10.334 8.42668L11.1207 13.0133L7.00065 10.8467L2.88065 13.0133L3.66732 8.42668L0.333984 5.18001L4.94065 4.50668L7.00065 0.333344Z" fill="#EDB10C"/></svg>';
    public $source_label = 'Select Source';
    public $sources_list = [
        [
            'I am an existing customer' => ['key' => 'DIR', 'label' => 'Direct Client', 'color' => '#CC0000'],
            'agt' => ['key' => 'AGT', 'label' => 'Agent', 'color' => '#FF5F1F'],
            'Referral' => ['key' => 'REF', 'label' => 'Referral', 'color' => '#FFCC00'],
            'Other' => ['key' => 'OTH', 'label' => 'Other', 'color' => '#595959'],
        ],
        [
            'Google Search' => ['key' => 'SEO', 'label' => 'Google Search', 'color' => '#4CBB17'],
            'Linkedin' => ['key' => 'LNK', 'label' => 'Linkedin', 'color' => '#0077B5'],
            'Social Media' => ['key' => 'SOC', 'label' => 'Social Media', 'color' => '#1877F2'],
        ]
    ];
    public $stored = false;

    public $rules = [
        'org_name' => 'required|max:255',
        'org_code' => 'required|max:50',
        'member' => 'required',
        'source' => 'required',
        'rating' => 'required',
        'contact.name' => 'required|max:255',
        'contact.job_title' => 'nullable|max:255',
        'contact.email' => 'required|max:255|email',
        'contact.phone' => 'required|max:20',
    ];

    public $attributes = [
        'contact.name' => 'contact name',
        'contact.job_title' => 'contact job title',
        'contact.email' => 'contact email',
        'contact.phone' => 'contact phone',
    ];

    public function render()
    {
        // members
        $setting_users_quoted = Setting::where('key', 'users_selected_dropdown_quotes')->first();
        $setting_users_quoted_ids = array_map('intval', json_decode($setting_users_quoted->value));
        $members = User::whereIn('id', $setting_users_quoted_ids)->select('id', 'name', 'lastname')->get();

        // member adm or employee
        $member_employee_role = '';
        if (Auth::user()->hasRole('Employee')) {
            $this->member = Auth::user()->id;
            $member_employee_role = Auth::user()->name . ' ' . Auth::user()->lastname;
        }

        $data['members'] = $members;
        $data['member_employee_role'] = $member_employee_role;

        return view('livewire.new-inquiry', $data);
    }

    public function updatedOrgName() {
        if ($this->org_name != '') {
            $this->organizations = Organization::where('name', 'LIKE', "%$this->org_name%")->select('id', 'name')->get();
        } else {
            $this->reset('org_code', 'organizations', 'contacts', 'contact');
        }
    }

    public function store(){
        if (true) {
            $this->validate($this->rules, [], $this->attributes);

            DB::transaction(function () {
                // Org
                $id_org = null;
                if ($this->org_selected) { // Org existe
                    // capturar id
                    $id_org = $this->org_id;
                    if ($this->new_contact) { // si deseo agregar un contacto diferente a los que figuran
                        OrganizationContact::create([
                            'name' => $this->contact['name'],
                            'job_title' => $this->contact['job_title'],
                            'email' => $this->contact['email'],
                            'phone' => $this->contact['phone'],
                            'organization_id' => $id_org,
                        ]);
                    }
                } else { // Org es nuevo
                    // crear org
                    $org_new = Organization::create([
                        'code' => $this->org_code,
                        'name' => $this->org_name,
                    ]);
                    // asignar id de org creado
                    $id_org = $org_new->id;
                    // crear contacto
                    OrganizationContact::create([
                        'name' => $this->contact['name'],
                        'job_title' => $this->contact['job_title'],
                        'email' => $this->contact['email'],
                        'phone' => $this->contact['phone'],
                        'organization_id' => $id_org,
                    ]);
                }

                // crear guest_user
                $guest_user = GuestUser::create([
                    'name' => $this->contact['name'],
                    'lastname' => '',
                    'company_name' => $this->org_name,
                    'email' => $this->contact['email'],
                    'phone_code' => '',
                    'phone' => $this->contact['phone'],
                    'source' => $this->source,
                    'subscribed_to_newsletter' => 'no',
                ]);

                // create quote con el id del org /
                $quotation = Quotation::create([
                    // 'customer_user_id' => '',
                    'guest_user_id' => $guest_user->id,
                    'mode_of_transport' => '',
                    // 'cargo_type' => '',
                    'service_type' => '',
                    'origin_country_id' => 38, // Canada
                    // 'origin_address' => '',
                    // 'origin_city' => '',
                    // 'origin_state_id' => '',
                    // 'origin_zip_code' => '',
                    // 'origin_airportorport' => '',
                    'destination_country_id' => 38, // Canada
                    // 'destination_address' => '',
                    // 'destination_city' => '',
                    // 'destination_state_id' => '',
                    // 'destination_zip_code' => '',
                    // 'destination_airportorport' => '',
                    // 'total_qty' => '',
                    // 'total_actualweight' => '',
                    // 'total_volum_weight' => '',
                    // 'tota_chargeable_weight' => '',
                    // 'shipping_date' => '',
                    'no_shipping_date' => 'no',
                    'declared_value' => 0,
                    'insurance_required' => 'no',
                    'currency' => 'USD - US Dollar',
                    'rating' => $this->rating,
                    'rating_modified' => 0,
                    'status' => 'Pending',
                    // 'result' => '',
                    'assigned_user_id' => $this->member,
                    'is_internal_inquiry' => true,
                    'recovered_account' => $this->recovered_account,
                    'cargo_description' => $this->cargo_description,
                    'created_at' => now(),
                ]);

                // Subir adjuntos

                // Mostrar Mensaje de Gracias
                $this->emit('add_stored_class_to_internal_inquiry');
                $this->stored = true;
            });
        }

    }

    public function select_org(Organization $org){
        $this->org_id = $org->id;
        $this->org_name = $org->name;
        $this->org_code = $org->code;
        $this->contacts = $org->contacts;
        $this->contact = $org->contacts->first()->toArray();
        // clean errors
        $this->resetErrorBag(['org_name', 'org_code', 'contact.name', 'contact.job_title', 'contact.email', 'contact.phone']);
        //
        $this->org_selected = true;
        $this->organizations = [];
    }

    public function select_contact(OrganizationContact $contact){
        $this->contact = $contact->toArray();
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
        return '<span>' . $rating_value . '</span>' . ' <span>' . $value . ' star'. ($value > 1 ? 's' : '') .'</span>';
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
        $this->reset('org_selected', 'org_id', 'org_name', 'org_code', 'contact', 'contacts', 'new_contact');
    }

    public function clean_data_after_close(){
        $this->resetErrorBag();
        $this->reset('org_id', 'org_name', 'org_code', 'contact', 'member', 'source', 'rating', 'recovered_account', 'cargo_description', 'org_selected', 'contacts', 'organizations', 'new_contact', 'rating_label', 'source_label');
    }
}
