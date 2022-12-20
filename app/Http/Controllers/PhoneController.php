<?php

namespace App\Http\Controllers;

use App\Models\Phone;
use Illuminate\Http\Request;

class PhoneController extends Controller
{
    /**
     * @param \GuzzleHttp\Psr7\Request $request
     *
     * @return mixed
     */
    public function getPhone(Request $request)
    {
        $phone = Phone::where('phone', $request->get('phone'))->firstOrFail();
        return ['success' => true, 'data' => $phone];
    }

    /**
     * @param \GuzzleHttp\Psr7\Request $request
     *
     * @return array
     */
    public function store(Request $request)
    {
        $phone = $request->get('phone');
        $orgName = $request->get('org_name');
        $orgSubDomain = $request->get('sub_domain');
        $patientApiUrl = $request->get('patient_api_url');
        $adminApiUrl = $request->get('admin_api_url');
        $therapistApiUrl = $request->get('therapist_api_url');
        $chatApiUrl = $request->get('chat_api_url');
        $chatWebsocketUrl = $request->get('chat_websocket_url');
        $clinicId = $request->get('clinic_id');
        Phone::create(
            [
                'phone' => $phone,
                'organization_name' => $orgName,
                'patient_api_url' => $patientApiUrl,
                'admin_api_url' => $adminApiUrl,
                'therapist_api_url' => $therapistApiUrl,
                'chat_api_url' => $chatApiUrl,
                'chat_websocket_url' => $chatWebsocketUrl,
                'clinic_id' => $clinicId,
                'sub_domain' => $orgSubDomain,
            ]
        );
        return ['success' => true, 'message' => 'success_message.phone_add'];
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Phone $phone
     *
     * @return array
     */
    public function update(Request $request, Phone $phone)
    {
        $phone->update([
            'phone' => $request->get('phone'),
        ]);
        return ['success' => true, 'message' => 'success_message.phone_update'];
    }

    /**
     * @param \App\Models\Phone $phone
     *
     * @return array
     */
    public function destroy(Phone $phone)
    {
        $phone->delete();
        return ['success' => true, 'message' => 'success_message.phone_delete'];
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function getPhoneByOrg(Request $request)
    {
        $subDomain = $request->get('sub_domain');
        $phone = $request->get('phone');
        $data = Phone::where('sub_domain', $subDomain)->where('phone', $phone)->first();
        return ['success' => true, 'data' => $data];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function deleteByClinicId(Request $request)
    {
        $clinicId = $request->get('clinic_id');
        $phones = Phone::where('clinic_id', $clinicId)->get();
        if (count($phones) > 0) {
            foreach ($phones as $phone) {
                $phone->delete();
            }
        }

        return ['success' => true, 'message' => 'success_message.deleted_account'];
    }
}
