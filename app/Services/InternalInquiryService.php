<?php
namespace App\Services;

use App\Enums\TypeAdditionalInfo;
use App\Enums\TypeDepartment;
use App\Enums\TypeInquiry;
use App\Enums\TypeModeTransport;
use App\Enums\TypeNetwork;
use App\Enums\TypePriorityInq;
use App\Models\Country;
use App\Models\GuestUser;
use App\Models\Organization;
use App\Models\OrganizationContact;
use App\Models\Quotation;
use App\Models\QuotationDocument;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InternalInquiryService {

    public function mount_data() {
        // members
        $setting_users_quoted = Setting::where('key', 'users_selected_dropdown_quotes')->first();
        $setting_users_quoted_ids = array_map('intval', json_decode($setting_users_quoted->value));
        $members = User::whereIn('id', $setting_users_quoted_ids)
                        ->where('department_id', auth()->user()->department_id)
                        ->select('id', 'name', 'lastname')
                        ->get();

        // member adm or sales
        $member_sales_role = '';
        if (Auth::user()->hasRole('Sales')) {
            $member = Auth::user()->id;
            $member_sales_role = Auth::user()->name . ' ' . Auth::user()->lastname;
        }

        // type inquiry
        $user_dept = auth()->user()->department_id;
        switch ($user_dept) {
            case TypeDepartment::SEO_HOUSE_DEPT->value:
                $types_inquiries[] = ['id' => TypeInquiry::INTERNAL->value, 'label' => TypeInquiry::INTERNAL->label()];
                $types_inquiries[] = ['id' => TypeInquiry::INTERNAL_OTHER->value, 'label' => TypeInquiry::INTERNAL_OTHER->label()];
                $type_inquiry = TypeInquiry::INTERNAL->value;
                break;
            case TypeDepartment::AGENTS_DEPT->value:
                $types_inquiries[] = ['id' => TypeInquiry::INTERNAL_LEGACY->value, 'label' => TypeInquiry::INTERNAL_LEGACY->label()];
                $types_inquiries[] = ['id' => TypeInquiry::INTERNAL_OTHER_AGT->value, 'label' => TypeInquiry::INTERNAL_OTHER_AGT->label()];
                $type_inquiry = TypeInquiry::INTERNAL_LEGACY->value;
                break;
            default: break;
        }

        // locations
        $location_list = Country::all();

        // network_options
        $network_options = TypeNetwork::options();

        // additional_info_options
        $additional_info_options = TypeAdditionalInfo::options();

        // mode of transport
        $mode_of_transport_options = TypeModeTransport::options_internal();

        return [
            'member' => $member,
            'members' => $members,
            'member_sales_role' => $member_sales_role,
            'types_inquiries' => $types_inquiries,
            'type_inquiry' => $type_inquiry,
            'location_list' => $location_list,
            'network_options' => $network_options,
            'additional_info_options' => $additional_info_options,
            'mode_of_transport_options' => $mode_of_transport_options,
        ];

    }

    // ----------------------------------------------

    public function store($component): void {
        $component->validate();

        DB::transaction(function () use ($component) {
            // 🧱 Preparar datos
            [$orgData, $userData] = $this->prepareData($component);

            // 🏢 Crear o actualizar organización
            $orgId = $this->handleOrganization($component, $orgData);

            // 👤 Crear GuestUser
            $guestUser = GuestUser::create($userData);

            // 🧾 Crear cotización
            $quotation = $this->createInquiry($component, $guestUser->id);

            // 📎 Guardar adjuntos
            $this->handleAttachments($component, $quotation->id);

            // 🎉 Emitir evento Livewire
            $component->emit('add_stored_class_to_internal_inquiry');
            $component->stored = true;
        });
    }

    // ----------------------------------------------

    private function prepareData($component): array {
        $orgData = [];
        $userData = [
            'name' => $component->contact['name'],
            'lastname' => '',
            'company_name' => strtoupper($component->org_name),
            'email' => $component->contact['email'],
            'phone_code' => '',
            'phone' => $component->contact['phone'],
            'subscribed_to_newsletter' => 'no',
        ];

        switch ($component->type_inquiry) {
            case TypeInquiry::INTERNAL->value:
                $orgData = [
                    'name' => strtoupper($component->org_name),
                    'code' => strtoupper($component->org_code),
                    'tier' => $component->tier,
                    'score' => $component->score,
                    'recovered_account' => $component->recovered_account,
                ];
                $userData += [
                    'tier' => $component->tier,
                    'score' => $component->score,
                    'recovered_account' => $component->recovered_account,
                ];
                break;

            case TypeInquiry::INTERNAL_OTHER->value:
                $orgData['name'] = strtoupper($component->org_name);
                $userData['source'] = $component->source;
                break;

            case TypeInquiry::INTERNAL_LEGACY->value:
                $orgData = [
                    'name' => strtoupper($component->org_name),
                    'tier' => $component->tier,
                    'score' => $component->score,
                    'country_id' => $component->location,
                    'network' => $component->network,
                ];
                $userData += [
                    'location' => $component->location,
                    'network' => $component->network,
                    'tier' => $component->tier,
                    'score' => $component->score,
                ];
                break;

            case TypeInquiry::INTERNAL_OTHER_AGT->value:
                $orgData = [
                    'name' => strtoupper($component->org_name),
                    'country_id' => $component->location,
                    'network' => $component->network,
                    'referred_by' => $component->referred_by,
                ];
                $userData += [
                    'location' => $component->location,
                    'network' => $component->network,
                    'referred_by' => $component->referred_by,
                ];
                break;
        }

        return [$orgData, $userData];
    }

    // ----------------------------------------------

    private function handleOrganization($component, array $orgData): int {
        if ($component->org_selected) {
            $org = Organization::find($component->org_id);
            $org->update($orgData);

            if ($component->new_contact) {
                OrganizationContact::create([
                    'name' => $component->contact['name'],
                    'job_title' => $component->contact['job_title'],
                    'email' => $component->contact['email'],
                    'phone' => $component->contact['phone'],
                    'organization_id' => $org->id,
                ]);
            } elseif ($component->update_contact) {
                OrganizationContact::where('id', $component->contact['id'])
                    ->update([
                        'job_title' => $component->contact['job_title'],
                        'email' => $component->contact['email'],
                        'phone' => $component->contact['phone'],
                    ]);
            }

            return $org->id;
        }

        // Crear organización nueva
        $org = Organization::create($orgData);

        OrganizationContact::create([
            'name' => $component->contact['name'],
            'job_title' => $component->contact['job_title'],
            'email' => $component->contact['email'],
            'phone' => $component->contact['phone'],
            'organization_id' => $org->id,
        ]);

        return $org->id;
    }

    // ----------------------------------------------

    private function createInquiry($component, int $guestUserId) {
        $data = [
            'guest_user_id' => $guestUserId,
            'mode_of_transport' => $component->mode_of_transport,
            'service_type' => '',
            'origin_country_id' => 38, // Canada
            'destination_country_id' => 38, // Canada
            'no_shipping_date' => 'no',
            'declared_value' => 0,
            'insurance_required' => 'no',
            'currency' => 'USD - US Dollar',
            'rating' => $component->rating,
            'shipping_date' => $component->shipping_date,
            'rating_modified' => 0,
            'status' => 'Pending',
            'assigned_user_id' => $component->member,
            'is_internal_inquiry' => true,
            'recovered_account' => $component->recovered_account,
            'cargo_description' => $component->cargo_description,
            'type_inquiry' => $component->type_inquiry,
            'department_id' => Auth::user()->department_id,
            'created_at' => now(),
        ];

        if ($component->type_inquiry === TypeInquiry::INTERNAL_LEGACY->value) {
            $data['priority'] = TypePriorityInq::HIGH->value;
        } elseif ($component->type_inquiry === TypeInquiry::INTERNAL_OTHER_AGT->value) {
            $calculate_priority = $this->priorityInternalOtherAgent($component);
            $data['priority'] = $calculate_priority['priority'];
            $data['points'] = $calculate_priority['points'];
        }

        return Quotation::create($data);
    }

    // ----------------------------------------------

    private function handleAttachments($component, int $quotationId): void {
        foreach ($component->attachments ?? [] as $attach) {
            $filename = uniqid() . '_' . $attach->getClientOriginalName();
            $attach->storeAs('public/uploads/quotation_documents', $filename);
            QuotationDocument::create([
                'quotation_id' => $quotationId,
                'document_path' => $filename,
            ]);
        }
    }

    public function priorityInternalOtherAgent($_this){
        $points = 0;

        // location

        // cargo details
        if (sizeof($_this->cargo_details) > 0) {
            $points = $points + 1;
        }

        // have shipping date
        if ($_this->shipping_date != '') {
            $points = $points + 1;
        }

        // agent network
        if (in_array(TypeNetwork::WCA->value, $_this->network) || in_array(TypeNetwork::TWIG->value, $_this->network)) {
            $points = $points + 2;
        } else if (!in_array(TypeNetwork::NONE->value, $_this->network)) {
            $points = $points + 1;
        }

        // referred by
        if ($_this->referred_by) {
            $points = $points + 1;
        }

        // additional_info
        if (in_array(TypeAdditionalInfo::CARGO_VALUE->value, $_this->additional_info)) {
            $points = $points + 1;
        }
        if (in_array(TypeAdditionalInfo::SHIPPING_VOLUME->value, $_this->additional_info)) {
            $points = $points + 1;
        }
        if (in_array(TypeAdditionalInfo::AGENTS_EXISTING_CLIENT->value, $_this->additional_info)) {
            $points = $points + 2;
        }

        $priority = TypePriorityInq::MEDIUM->value;
        if ($points >= 0 and $points <= 5) {
            $priority = TypePriorityInq::LOW->value;
        }

        return [
            'points' => $points,
            'priority' => $priority,
        ];
    }

    // ----------------------------------------------
    public function relationTransportAndCargoDetails($_this){
        switch ($_this->mode_of_transport) {
            case TypeModeTransport::GROUND_FTL->value:
            case TypeModeTransport::OCEAN_FCL->value:
                $_this->cargo_details_options = ["HS Code", "Cargo Weight", "DG Details (MSDS/UN/Class)", "Reefer (Temp)"];
                break;
            case TypeModeTransport::GROUND_LTL->value:
            case TypeModeTransport::OCEAN_LCL->value:
            case TypeModeTransport::AIR_FREIGHT->value:
            case TypeModeTransport::OCEAN_BREAKBULK->value:
                $_this->cargo_details_options = ["Dimensions/Weight"];
                break;
            case TypeModeTransport::OCEAN_RORO->value:
                $_this->cargo_details_options = ["Vehicle Type/Dimensions/Weight"];
                break;
            default:
                $_this->cargo_details_options = [];
                break;
        }
    }

}
