<?php

namespace App\Http\Livewire;

use App\Models\Organization;
use Livewire\Component;
use Livewire\WithPagination;

class OrganizationsList extends Component
{
    use WithPagination;

    public $per_page = 30;
    public $query = '';

    public function render()
    {
        $organizations = Organization::with('contacts')
            ->when($this->query != '', function ($q) {
                $q->where('name', 'LIKE', "%$this->query%")
                    ->orWhere('code', 'LIKE', "%$this->query%")
                    ->orWhere('addresses', 'LIKE', "%$this->query%");
            })
            ->latest()
            // ->orderBy('name', 'ASC')
            ->paginate($this->per_page);
        $data['organizations'] = $organizations;
        return view('livewire.organizations-list', $data);
    }

    public function updatingQuery(){
        $this->resetPage();
    }

    public function destroy(Organization $organization){
        $organization->delete();
    }
}
