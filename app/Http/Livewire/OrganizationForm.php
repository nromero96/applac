<?php

namespace App\Http\Livewire;

use App\Enums\TypeNetwork;
use App\Models\Country;
use App\Models\Organization;
use Livewire\Component;

class OrganizationForm extends Component
{
    public $code;
    public $name;
    public $addresses = [];
    public $contacts = [];
    public $tier;
    public $score;
    public $country_id;
    public $network = [];
    public $recovered_account = false;
    public $referred_by = false;

    public $org_editing;
    public $showing = false;
    public $countries_options;
    public $network_options;
    public $location_label;
    public $network_label;

    public $rules = [
        'name' => 'required|max:255',
        'addresses.*' => 'required',
        'tier' => 'nullable',
        'score' => 'nullable|numeric|between:0,500',
        'country_id' => 'nullable',
        'network' => 'nullable',
        'recovered_account' => 'nullable',
        'referred_by' => 'nullable',
        'contacts' => 'required',
        'contacts.*.name' => 'required|max:255',
        'contacts.*.job_title' => 'nullable|max:255',
        'contacts.*.email' => 'required|max:255|email',
        'contacts.*.phone' => 'required|max:20',
        'contacts.*.fax' => 'nullable|max:20',
    ];

    public $attributes = [
        'addresses.*' => 'address',
        'contacts.*.name' => 'name',
        'contacts.*.job_title' => 'job title',
        'contacts.*.email' => 'email',
        'contacts.*.phone' => 'phone',
        'contacts.*.fax' => 'fax',
    ];

    public $changed = false;

    public function mount(){
        if ($this->org_editing) {
            // meta
            $this->countries_options = Country::orderBy('name')->get();
            $this->network_options = TypeNetwork::options();
            // data
            $this->code = $this->org_editing->code;
            $this->name = $this->org_editing->name;
            if ($this->org_editing->addresses == '') {
                $this->addresses = [];
            } else {
                $this->addresses = $this->org_editing->addresses;
            }
            $this->tier = $this->org_editing->tier;
            $this->score = $this->org_editing->score;
            $this->country_id = $this->org_editing->country_id;
            $this->location_label = $this->org_editing->country?->name;
            $this->network = $this->org_editing->network;
            $this->network_label = collect($this->org_editing->network)
                                ->map(fn($item) => TypeNetwork::tryFrom($item)?->meta('label') ?? $item)
                                ->toArray();
            $this->recovered_account = $this->org_editing->recovered_account;
            $this->referred_by = $this->org_editing->referred_by;

            $this->contacts = $this->org_editing->contacts->toArray();
            $this->rules['code'] = 'nullable|max:20|unique:organizations,id,'.$this->org_editing->id;
        } else {
            $this->rules['code'] = 'nullable|max:20|unique:organizations,code';
        }
    }

    public function render()
    {
        return view('livewire.organization-form');
    }

    public function store(){
        $data = $this->validate($this->rules, [], $this->attributes);

        if (sizeof($this->addresses) == 0) {
            $data['addresses'] = null;
        }
        if (sizeof($this->network) == 0) {
            $data['network'] = null;
        }
        $org = Organization::create($data);
        $org->contacts()->createMany($this->contacts);

        return redirect()->route('organization.show', $org->id);
    }

    public function update(){
        $this->changed = false;
        $data = $this->validate($this->rules, [], $this->attributes);
        $this->org_editing->update($data);
        $this->org_editing->contacts()->whereNotIn('id', array_column($this->contacts, 'id'))->delete();
        foreach ($this->contacts as $item) {
            if (isset($item['id'])) {
                $contact = $this->org_editing->contacts()->find($item['id']);
                if ($contact) {
                    $contact->update($item);
                }
            } else {
                $this->org_editing->contacts()->create($item);
            }
        }
        $this->changed = true;
    }

    public function add_contact(){
        $this->contacts[] = [
            'name' => '',
            'job_title' => '',
            'email' => '',
            'phone' => '',
            'fax' => '',
        ];
    }

    public function remove_contact($index) {
        unset($this->contacts[$index]);
        array_values($this->contacts);
    }

    public function add_address(){
        $this->addresses[] = '';
    }

    public function remove_address($index) {
        unset($this->addresses[$index]);
        array_values($this->addresses);
    }
}
