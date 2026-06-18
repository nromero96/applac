<?php

namespace App\Http\Livewire;

use App\Enums\TypeInquiry;
use App\Models\Quotation;
use App\Models\QuotationNote;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class TransferDept extends Component
{
    public array $quotationData;
    public $showModal = false;
    public array $user_sales;
    public array $user_sales_dpto;
    public $new_dept = '';
    public $assignedUserId = 'auto';
    public $notes = '';

    public function mount(array $quotationData) {
        $this->quotationData = $quotationData;
        $current_assigned_user = User::find($this->quotationData['assigned_user_id']);
        $this->new_dept = $current_assigned_user->department_id === 1 ? 2 : 1;

        // listar miembros
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
                ->where('users.id', '!=', $this->quotationData['assigned_user_id'])
                ->select('users.id as id', 'name', 'lastname', 'users.department_id', 'users.priority_countries_ext')
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
                        'department_id' => $user['department_id'],
                        'countries' => $user['priority_countries_ext'],
                    ];
                    if (isset($user['department']['name'])) {
                        $user_sales_dpto_arr[$user['department']['name']][] = $user_data;
                    } else {
                        // $user_sales_dpto_arr['Other'][] = $user_data;
                    }
                }
            }
            $this->user_sales_dpto = $user_sales_dpto->toArray();
            $this->user_sales = $user_sales_dpto_arr ?? [];
    }

    public function render() {
        return view('livewire.transfer-dept');
    }

    public function rules() {
        return [
            'assignedUserId' => ['required'],
            'notes' => ['nullable'],
        ];
    }

    public function save() {
        $data = $this->validate();

        $assignedUserId = $this->assignedUserId;

        // buscar nuevo dpto
        if ($this->assignedUserId != 'auto') { // si es manual
            foreach ($this->user_sales_dpto as $item) {
                if ($this->assignedUserId == $item['id']) {
                    $this->new_dept = $item['department_id'];
                    break;
                }
            }
        } else { // si es automatico
            if ($this->new_dept == 2) { // agents dpt
                $agents = $this->user_sales['Agents'];
                $location = $this->quotationData['customer_location'];
                foreach ($agents as $agent) {
                    if (in_array($location, $agent['countries'])) {
                        $assignedUserId = $agent['id'];
                        break;
                    }
                }
            } else { // seo dept
                $users_auto_assigned_quotes = Setting::where('key', 'users_auto_assigned_quotes')->first()->value;
                $userIds = json_decode($users_auto_assigned_quotes);
                $indexFile = 'current_index.txt';
                $currentIndex = (int)Storage::get($indexFile);
                if ($currentIndex >= count($userIds)) {
                    $currentIndex = 0;
                }
                // Obtén el usuario en el índice actual
                $selectedUserId = $userIds[$currentIndex];
                $assignedUserId = $selectedUserId;
                $currentIndex++;
                Storage::put($indexFile, $currentIndex);
            }
        }

        // define type inquiry
        if ($this->new_dept == 2) {
            $new_type_inquiry = TypeInquiry::EXTERNAL_SEO_RFQ->value;
        } else {
            $new_type_inquiry = TypeInquiry::EXTERNAL_2->value;
        }

        $quotation = Quotation::find($this->quotationData['id']);
        $save_data = [
            // cambia dpto
            'department_id' => $this->new_dept,
            // cambia type inquiry
            'type_inquiry' => $new_type_inquiry,
            // cambia nuevo user asignado
            'assigned_user_id' => $assignedUserId,
        ];
        $quotation->update($save_data);

        // save log
        $name_new_dept = $this->new_dept == 1 ? 'SEO Dept.' : 'Agents Dept.'; // new dept name
        $new_owner = User::find($assignedUserId);
        $new_owner_fullname = $new_owner->name . ' ' . $new_owner->lastname;
        $log_data = [
            'type' => 'transfer',
            'quotation_id' => $quotation->id,
            'action' => 'Inquiry transferred to ' . $name_new_dept,
            'note' => $this->notes,
            'reason' => json_encode([
                'prev_owner' => $this->quotationData['member_name'] . ' ' . $this->quotationData['member_lastname'],
                'new_owner' => $new_owner_fullname,
            ]),
            'user_id' => auth()->user()->id,
        ];
        QuotationNote::create($log_data);
        // dd($log_data);

        return redirect(request()->header('Referer'));
    }

    public function close_modal() {
        $this->showModal = false;
        $this->reset('notes', 'assignedUserId');
    }
}
