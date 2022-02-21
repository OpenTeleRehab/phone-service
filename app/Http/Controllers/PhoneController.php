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
        $phone = Phone::where('phone', $request->get('phone'))->first();
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
        $org = $request->get('org_name');
        $phone = $request->get('phone');
        $data = Phone::where('organization_name', $org)->where('phone', $phone)->first();
        return ['success' => true, 'data' => $data];
    }
}
