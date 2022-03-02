<?php

namespace App\Console\Commands;

use App\Helpers\ApiHelper;
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
    protected $signature = 'hi:import-patient-data {stage : (local, demo, live)}';

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
    protected $help = 'php artisan hi:import-patient-data local';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $hosts = config('settings.hosting_country');
        $response = Http::get(env('GADMIN_SERVICE_URL') . '/get-org-by-name', ['orgName' => 'Humanity Inclusion']);
        $stage = $this->argument('stage');

        if ($response->successful()) {
            // Get patient from global to phone service.
            $patientData = json_decode(Http::get(env('PATIENT_SERVICE_URL') . '/patient/list/data-for-phone-service'));

            if (!empty($patientData)) {
                $domain = $patientData->domain;

                foreach ($patientData->data as $patient) {
                    Phone::create(
                        [
                            'phone' => $patient->phone,
                            'organization_name' => 'Humanity Inclusion',
                            'patient_api_url' => ApiHelper::createApiUrl($stage, 'patient', $domain),
                            'admin_api_url' => ApiHelper::createApiUrl($stage, 'admin', $domain),
                            'therapist_api_url' => ApiHelper::createApiUrl($stage, 'therapist', $domain),
                            'chat_api_url' => ApiHelper::createApiUrl($stage, 'chat', $domain),
                            'chat_websocket_url' => ApiHelper::createApiUrl($stage, 'websocket', $domain),
                            'clinic_id' => $patient->clinic_id,
                        ]
                    );
                }
            }

            // Get patient from vn db or other country to phone service
            foreach ($hosts as $host) {
                $patientData = json_decode(Http::withHeaders([
                    'country' => $host
                ])->get(env('PATIENT_SERVICE_URL') . '/patient/list/data-for-phone-service'));

                if (!empty($patientData)) {
                    $domain = $patientData->domain;

                    foreach ($patientData->data as $patient) {
                        Phone::create(
                            [
                                'phone' => $patient->phone,
                                'organization_name' => 'Humanity Inclusion',
                                'patient_api_url' => ApiHelper::createApiUrl($stage, 'patient', $domain),
                                'admin_api_url' => ApiHelper::createApiUrl($stage, 'admin', $domain),
                                'therapist_api_url' => ApiHelper::createApiUrl($stage, 'therapist', $domain),
                                'chat_api_url' => ApiHelper::createApiUrl($stage, 'chat', $domain),
                                'chat_websocket_url' => ApiHelper::createApiUrl($stage, 'websocket', $domain),
                                'clinic_id' => $patient->clinic_id,
                            ]
                        );
                    }
                }
            }
        }

        $this->info('Patient data has been imported successfully');

        return 0;
    }
}
