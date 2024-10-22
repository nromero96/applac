<?php

namespace App\Http\Livewire;

use App\Models\Organization;
use Livewire\Component;

class OrganizationForm extends Component
{
    public $code;
    public $name;
    public $addresses = [];
    public $contacts = [];

    public $org_editing;
    public $showing = false;

    public $rules = [
        'name' => 'required|max:255',
        'addresses.*' => 'required',
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
            $this->code = $this->org_editing->code;
            $this->name = $this->org_editing->name;
            if ($this->org_editing->addresses == '') {
                $this->addresses = [];
            } else {
                $this->addresses = $this->org_editing->addresses;
            }
            $this->contacts = $this->org_editing->contacts->toArray();
            $this->rules['code'] = 'required|max:20|unique:organizations,id,'.$this->org_editing->id;
        } else {
            $this->rules['code'] = 'required|max:20|unique:organizations,code';
        }
    }

    public function render()
    {
        return view('livewire.organization-form');
    }

    public function store(){
        $data = $this->validate($this->rules, [], $this->attributes);

        if (sizeof($this->addresses) == 0) {
            $data['addresses'] = '';
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
