<?php

namespace App\Console\Commands;

use App\Models\Phone;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ImportPatientData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hi:import-patient-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import all existing patient data';

    /**
     * The console command example helper.
     *
     * @var string
     */
    protected $help = 'php artisan hi:import-patient-data';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $hosts = config('settings.hosting_country');
        $response = Http::get(env('GADMIN_SERVICE_URL') . '/get-org-by-name', ['orgName' => 'Humanity Inclusion']);
        if ($response->successful()) {
            $org = $response->json();

            // Get patient from global to phone service
            $patientData = json_decode(Http::get(env('PATIENT_SERVICE_URL') . '/patient/list/data-for-phone-service'));
            $patientApiUrl = 'https://' . $org['sub_domain_name'] . '-patient.' . $patientData->domain . '/api/patient';
            $adminApiUrl = 'https://' . $org['sub_domain_name'] . '-admin.' . $patientData->domain . '/api/admin';
            $therapistApiUrl = 'https://' . $org['sub_domain_name'] . '-therapist.' . $patientData->domain . '/api/therapist';
            $chatApiUrl = 'https://' . $org['sub_domain_name'] . '-chat.' . $patientData->domain;
            $chatWebsocketUrl = 'wss://' . $org['sub_domain_name'] . '-chat.' . $patientData->domain . '/websocket';
            foreach ($patientData->data as $patient) {
                Phone::create(
                    [
                        'phone' => $patient->phone,
                        'organization_name' => 'Humanity Inclusion',
                        'patient_api_url' => $patientApiUrl,
                        'admin_api_url' => $adminApiUrl,
                        'therapist_api_url' => $therapistApiUrl,
                        'chat_api_url' => $chatApiUrl,
                        'chat_websocket_url' => $chatWebsocketUrl,
                        'clinic_id' => $patient->clinic_id,
                    ]
                );
            }

            // Get patient from vn db or other country to phone service
            foreach ($hosts as $host) {
                $patientData = json_decode(Http::withHeaders([
                    'country' => $host
                ])->get(env('PATIENT_SERVICE_URL') . '/patient/list/data-for-phone-service'));
                foreach ($patientData->data as $patient) {
                    Phone::create(
                        [
                            'phone' => $patient->phone,
                            'organization_name' => 'Humanity Inclusion',
                            'patient_api_url' => $patientApiUrl,
                            'admin_api_url' => $adminApiUrl,
                            'therapist_api_url' => $therapistApiUrl,
                            'chat_api_url' => $chatApiUrl,
                            'chat_websocket_url' => $chatWebsocketUrl,
                            'clinic_id' => $patient->clinic_id,
                        ]
                    );
                }
            }
        }
        $this->info('Patient data has been imported successfully');
    }
}
