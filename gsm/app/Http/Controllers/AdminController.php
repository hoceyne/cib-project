<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Appointment;
use App\Models\Bed;
use App\Models\Departement;
use App\Models\Discharge;
use App\Models\DischargeByAdmin;
use App\Models\DischargeByDoctor;
use App\Models\Doctor;
use App\Models\Hospitalization;
use App\Models\HospitalizationRequest;
use App\Models\Image;
use App\Models\Invoice;
use App\Models\MedicalRecord;
use App\Models\Medication;
use App\Models\Nurse;
use App\Models\Room;
use App\Models\RoomTicket;
use App\Models\Service;
use App\Models\ServiceActivity;
use App\Models\ServiceImages;
use App\Models\ServiceMainPhoto;
use App\Models\ServiceOutlook;
use App\Models\ServiceRecord;
use App\Models\ShuttleSheet;
use App\Models\Task;
use App\Models\Test;
use App\Models\TestNote;
use App\Models\TestRequest;
use App\Models\TestResult;
use App\Models\TestResultFile;
use App\Models\Transfer;
use App\Models\TransferRequest;
use App\Models\Patient;
use App\Models\PharmacyOrder;
use App\Models\Prescription;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{

    public function room_index()
    {
        $rooms = Room::all();
        return response()->json(['data' => $rooms]);
    }

    public function room_show($id)
    {
        $room = Room::with('beds')->findOrFail($id);
        return response()->json(['data' => $room]);
    }

    public function room_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'number' => 'required',
            'nb_bed' => 'required|integer',
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $room = Room::create($request->all());

        return response()->json(['data' => $room], 201);
    }

    public function room_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'number' => 'required',
            'nb_bed' => 'required|integer',
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $room = Room::findOrFail($id);
        $room->update($request->all());

        return response()->json(['data' => $room]);
    }

    public function room_destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->delete();

        return response()->json(['message' => 'Room deleted successfully']);
    }

    public function room_ticket_index()
    {
        $roomTickets = RoomTicket::all();
        return response()->json(['data' => $roomTickets]);
    }

    public function room_ticket_show($id)
    {
        $roomTicket = RoomTicket::with(['bed', 'room'])->findOrFail($id);
        return response()->json(['data' => $roomTicket]);
    }

    public function room_ticket_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Add validation rules for the fields if needed
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $roomTicket = RoomTicket::create($request->all());

        return response()->json(['data' => $roomTicket], 201);
    }

    public function room_ticket_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            // Add validation rules for the fields if needed
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $roomTicket = RoomTicket::findOrFail($id);
        $roomTicket->update($request->all());

        return response()->json(['data' => $roomTicket]);
    }

    public function room_ticket_destroy($id)
    {
        $roomTicket = RoomTicket::findOrFail($id);
        $roomTicket->delete();

        return response()->json(['message' => 'Room ticket deleted successfully']);
    }

    public function service_index()
    {
        $services = Service::all();
        return response()->json(['data' => $services]);
    }

    public function service_show($id)
    {
        $service = Service::with(['serviceMainPhotos', 'serviceOutlook'])->findOrFail($id);
        return response()->json(['data' => $service]);
    }

    public function service_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'medical_staff' => 'required',
            'paramedical_staff' => 'required',
            'bed_capacity' => 'required',
            'cheif_doctor_id' => 'required|exists:doctors,id',
            'departement_id' => 'required|exists:departements,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $file = $request->file('file');
        $name = $file->getClientOriginalName();
        $content = file_get_contents($file->getRealPath());
        $extension = $file->getClientOriginalExtension();



        $service = Service::create($request->all());

        $service->main_photo(new ServiceMainPhoto([
            'name' => $name,
            '$content' => $content,
            'extension' => $extension,
        ]));

        $files = $request->file('uploaded_images');

        foreach ($files as $file) {
            # code...
            $name = $file->getClientOriginalName(); // Get the original name of the file
            $content = file_get_contents($file->getRealPath()); // Get the content of the file
            $extension = $file->getClientOriginalExtension(); // Get the extension of the file

            $service->images()->save(new Image([
                'name' => $name,
                'content' => base64_encode($content),
                'extension' => $extension,
            ]));
        }

        return response()->json(['data' => $service], 201);
    }

    public function service_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'medical_staff' => 'required',
            'paramedical_staff' => 'required',
            'bed_capacity' => 'required',
            'cheif_doctor_id' => 'required|exists:doctors,id',
            'departement_id' => 'required|exists:departements,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $service = Service::findOrFail($id);
        $service->update($request->all());

        return response()->json(['data' => $service]);
    }

    public function service_destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return response()->json(['message' => 'Service deleted successfully']);
    }

    public function shuttle_sheet_index()
    {
        $shuttleSheets = ShuttleSheet::all();
        return response()->json(['data' => $shuttleSheets]);
    }

    public function shuttle_sheet_show($id)
    {
        $shuttleSheet = ShuttleSheet::with(['admission', 'hospitalization', 'patient', 'transfers'])->findOrFail($id);
        return response()->json(['data' => $shuttleSheet]);
    }

    public function shuttle_sheet_store(Request $request)
    {
        // Validation rules here

        $validator = Validator::make($request->all(), [
            // Define your validation rules for the ShuttleSheet model
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $shuttleSheet = ShuttleSheet::create($request->all());

        return response()->json(['data' => $shuttleSheet], 201);
    }

    public function shuttle_sheet_update(Request $request, $id)
    {
        // Validation rules here

        $validator = Validator::make($request->all(), [
            // Define your validation rules for the ShuttleSheet model
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $shuttleSheet = ShuttleSheet::findOrFail($id);
        $shuttleSheet->update($request->all());

        return response()->json(['data' => $shuttleSheet]);
    }

    public function shuttle_sheet_destroy($id)
    {
        $shuttleSheet = ShuttleSheet::findOrFail($id);
        $shuttleSheet->delete();

        return response()->json(['message' => 'Shuttle sheet deleted successfully']);
    }

    public function task_index()
    {
        $tasks = Task::all();
        return response()->json(['data' => $tasks]);
    }

    public function task_show($id)
    {
        $task = Task::with(['doctor', 'nurse'])->findOrFail($id);
        return response()->json(['data' => $task]);
    }

    public function task_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $task = Task::create($request->all());

        return response()->json(['data' => $task], 201);
    }

    public function task_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $task = Task::findOrFail($id);
        $task->update($request->all());

        return response()->json(['data' => $task]);
    }

    public function task_destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }

    public function test_index()
    {
        $tests = Test::all();
        return response()->json(['data' => $tests]);
    }

    public function test_show($id)
    {
        $test = Test::with(['doctor', 'service', 'patient', 'notes'])->findOrFail($id);
        return response()->json(['data' => $test]);
    }

    public function test_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'type' => 'required',
            'status' => 'required',
            'request_date' => 'required',
            'expected_result_date' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $test = Test::create($request->all());

        return response()->json(['data' => $test], 201);
    }

    public function test_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'type' => 'required',
            'status' => 'required',
            'request_date' => 'required',
            'expected_result_date' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $test = Test::findOrFail($id);
        $test->update($request->all());

        return response()->json(['data' => $test]);
    }

    public function test_destroy($id)
    {
        $test = Test::findOrFail($id);
        $test->delete();

        return response()->json(['message' => 'Test deleted successfully']);
    }

    public function test_request_index()
    {
        $testRequests = TestRequest::all();
        return response()->json(['data' => $testRequests]);
    }

    public function test_request_show($id)
    {
        $testRequest = TestRequest::with(['doctor', 'service_source', 'service_destination', 'test'])->findOrFail($id);
        return response()->json(['data' => $testRequest]);
    }

    public function test_request_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required',
            'response' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $testRequest = TestRequest::create($request->all());

        return response()->json(['data' => $testRequest], 201);
    }

    public function test_request_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required',
            'response' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $testRequest = TestRequest::findOrFail($id);
        $testRequest->update($request->all());

        return response()->json(['data' => $testRequest]);
    }

    public function test_request_destroy($id)
    {
        $testRequest = TestRequest::findOrFail($id);
        $testRequest->delete();

        return response()->json(['message' => 'Test request deleted successfully']);
    }

    public function test_result_index()
    {
        $testResults = TestResult::all();
        return response()->json(['data' => $testResults]);
    }

    public function test_result_show($id)
    {
        $testResult = TestResult::with(['doctor', 'service', 'test', 'notes', 'results'])->findOrFail($id);
        return response()->json(['data' => $testResult]);
    }

    public function test_result_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'type' => 'required',
            'summary' => 'required',
            'details' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $testResult = TestResult::create($request->all());

        return response()->json(['data' => $testResult], 201);
    }

    public function test_result_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'type' => 'required',
            'summary' => 'required',
            'details' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $testResult = TestResult::findOrFail($id);
        $testResult->update($request->all());

        return response()->json(['data' => $testResult]);
    }

    public function test_result_destroy($id)
    {
        $testResult = TestResult::findOrFail($id);
        $testResult->delete();

        return response()->json(['message' => 'Test result deleted successfully']);
    }

    public function transfer_index()
    {
        $transfers = Transfer::all();
        return response()->json(['data' => $transfers]);
    }

    public function transfer_show($id)
    {
        $transfer = Transfer::with(['bed', 'doctor', 'service', 'request'])->findOrFail($id);
        return response()->json(['data' => $transfer]);
    }

    public function transfer_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'enter_date' => 'required',
            'enter_time' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $transfer = Transfer::create($request->all());

        return response()->json(['data' => $transfer], 201);
    }

    public function transfer_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'enter_date' => 'required',
            'enter_time' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $transfer = Transfer::findOrFail($id);
        $transfer->update($request->all());

        return response()->json(['data' => $transfer]);
    }

    public function transfer_destroy($id)
    {
        $transfer = Transfer::findOrFail($id);
        $transfer->delete();

        return response()->json(['message' => 'Transfer deleted successfully']);
    }


    public function transfer_request_index()
    {
        $transferRequests = TransferRequest::all();
        return response()->json(['data' => $transferRequests]);
    }

    public function transfer_request_show($id)
    {
        $transferRequest = TransferRequest::with(['doctor', 'service_source', 'service_destination', 'transfer'])->findOrFail($id);
        return response()->json(['data' => $transferRequest]);
    }

    public function transfer_request_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'reason' => 'required',
            'status' => 'required',
            'request_date' => 'required',
            'transfer_date' => 'required',
            'response' => 'required',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $transferRequest = TransferRequest::create($request->all());

        return response()->json(['data' => $transferRequest], 201);
    }

    public function transfer_request_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'reason' => 'required',
            'status' => 'required',
            'request_date' => 'required',
            'transfer_date' => 'required',
            'response' => 'required',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $transferRequest = TransferRequest::findOrFail($id);
        $transferRequest->update($request->all());

        return response()->json(['data' => $transferRequest]);
    }

    public function transfer_request_destroy($id)
    {
        $transferRequest = TransferRequest::findOrFail($id);
        $transferRequest->delete();

        return response()->json(['message' => 'Transfer request deleted successfully']);
    }

    public function bed_index()
    {
        $beds = Bed::all();
        return response()->json($beds);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bed_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bed_id' => 'required',
            'status' => 'required',
            'room_id' => 'required|exists:rooms,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $bed = Bed::create($request->all());

        return response()->json($bed, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function bed_show($id)
    {
        $bed = Bed::findOrFail($id);
        return response()->json($bed);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function bed_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'bed_id' => 'required',
            'status' => 'required',
            'room_id' => 'required|exists:rooms,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $bed = Bed::findOrFail($id);
        $bed->update($request->all());

        return response()->json($bed, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function bed_destroy($id)
    {
        $bed = Bed::findOrFail($id);
        $bed->delete();

        return response()->json(['message' => 'Bed deleted successfully']);
    }

    public function departement_index()
    {
        $departements = Departement::all();
        return response()->json($departements);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function departement_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $departement = Departement::create($request->all());

        return response()->json($departement, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function departement_show($id)
    {
        $departement = Departement::findOrFail($id);
        return response()->json($departement);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function departement_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $departement = Departement::findOrFail($id);
        $departement->update($request->all());

        return response()->json($departement, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function departement_destroy($id)
    {
        $departement = Departement::findOrFail($id);
        $departement->delete();

        return response()->json(['message' => 'Departement deleted successfully']);
    }

    public function admission_index()
    {
        $admissions = Admission::all();
        return response()->json($admissions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function admission_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'admission_id' => 'required',
            'date' => 'required|date',
            'insurance_id' => 'required',
            'profession' => 'required',
            'profession_code' => 'required',
            'number_support' => 'required',
            'affiliate_fund' => 'required',
            'insurance_address.country' => 'required',
            'insurance_address.state' => 'required',
            'insurance_address.city' => 'required',
            'insurance_address.daira' => 'required',
            'insurance_address.street' => 'required',
            'patient_id' => 'required|exists:patients,id',
            'hospitalization_id' => 'required|exists:hospitalizations,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $admissionData = $request->all();
        $insuranceAddressData = $admissionData['insurance_address'];
        unset($admissionData['insurance_address']);

        $admission = Admission::create($admissionData);
        $admission->insurance_address()->create($insuranceAddressData);

        return response()->json($admission, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function admission_show($id)
    {
        $admission = Admission::findOrFail($id);
        return response()->json($admission);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function admission_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'admission_id' => 'required',
            'date' => 'required|date',
            'insurance_id' => 'required',
            'profession' => 'required',
            'profession_code' => 'required',
            'number_support' => 'required',
            'affiliate_fund' => 'required',
            'insurance_address.country' => 'required',
            'insurance_address.state' => 'required',
            'insurance_address.city' => 'required',
            'insurance_address.daira' => 'required',
            'insurance_address.street' => 'required',
            'patient_id' => 'required|exists:patients,id',
            'hospitalization_id' => 'required|exists:hospitalizations,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $admission = Admission::findOrFail($id);
        $admissionData = $request->all();
        $insuranceAddressData = $admissionData['insurance_address'];
        unset($admissionData['insurance_address']);

        $admission->update($admissionData);
        $admission->insurance_address()->update($insuranceAddressData);

        return response()->json($admission, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function admission_destroy($id)
    {
        $admission = Admission::findOrFail($id);
        $admission->delete();

        return response()->json(['message' => 'Admission deleted successfully']);
    }

    public function discharge_index()
    {
        $discharges = Discharge::all();

        return response()->json($discharges);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function discharge_store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
        ]);

        $discharge = Discharge::create($request->all());

        return response()->json($discharge, 201);
    }

    public function discharge_show($id)
    {
        $discharge = Discharge::findOrFail($id);

        return response()->json($discharge);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function discharge_update(Request $request, $id)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
        ]);

        $discharge = Discharge::findOrFail($id);
        $discharge->update($request->all());

        return response()->json($discharge);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function discharge_destroy($id)
    {
        $discharge = Discharge::findOrFail($id);
        $discharge->delete();

        return response()->json(null, 204);
    }

    public function discharge_by_admin_index()
    {
        $dischargesByAdmin = DischargeByAdmin::all();
        return response()->json($dischargesByAdmin);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function discharge_by_admin_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'service_total_cost' => 'required',
            'discharge_document_type' => 'required',
            'accompanied_on_leaving_by' => 'required',
            'invoice_id' => 'required|exists:invoices,id',
            'receipt_id' => 'required|exists:receipts,id',
            'discharge_file_id' => 'required|exists:discharges,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $dischargeByAdmin = DischargeByAdmin::create($request->all());

        return response()->json($dischargeByAdmin, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function discharge_by_admin_show($id)
    {
        $dischargeByAdmin = DischargeByAdmin::findOrFail($id);
        return response()->json($dischargeByAdmin);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function discharge_by_admin_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'service_total_cost' => 'required',
            'discharge_document_type' => 'required',
            'accompanied_on_leaving_by' => 'required',
            'invoice_id' => 'required|exists:invoices,id',
            'receipt_id' => 'required|exists:receipts,id',
            'discharge_file_id' => 'required|exists:discharge_files,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $dischargeByAdmin = DischargeByAdmin::findOrFail($id);
        $dischargeByAdmin->update($request->all());

        return response()->json($dischargeByAdmin, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function discharge_by_admin_destroy($id)
    {
        $dischargeByAdmin = DischargeByAdmin::findOrFail($id);
        $dischargeByAdmin->delete();

        return response()->json(['message' => 'DischargeByAdmin deleted successfully']);
    }


    public function discharge_by_doctor_index()
    {
        $dischargesByDoctor = DischargeByDoctor::all();
        return response()->json($dischargesByDoctor);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function discharge_by_doctor_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'time' => 'required',
            'discharge_mode' => 'required',
            'enter_reason' => 'required',
            'discharge_diagnosis' => 'required',
            'service_id' => 'required|exists:services,id',
            'discharge_file_id' => 'required|exists:discharges,id',
            'doctor_id' => 'required|exists:doctors,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $dischargeByDoctor = DischargeByDoctor::create($request->all());

        return response()->json($dischargeByDoctor, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function discharge_by_doctor_show($id)
    {
        $dischargeByDoctor = DischargeByDoctor::findOrFail($id);
        return response()->json($dischargeByDoctor);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function discharge_by_doctor_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'time' => 'required',
            'discharge_mode' => 'required',
            'enter_reason' => 'required',
            'discharge_diagnosis' => 'required',
            'service_id' => 'required|exists:services,id',
            'discharge_file_id' => 'required|exists:discharges,id',
            'doctor_id' => 'required|exists:doctors,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $dischargeByDoctor = DischargeByDoctor::findOrFail($id);
        $dischargeByDoctor->update($request->all());

        return response()->json($dischargeByDoctor, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function discharge_by_doctor_destroy($id)
    {
        $dischargeByDoctor = DischargeByDoctor::findOrFail($id);
        $dischargeByDoctor->delete();

        return response()->json(['message' => 'DischargeByDoctor deleted successfully']);
    }

    public function doctor_index()
    {
        $doctors = Doctor::all();
        return response()->json($doctors);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function doctor_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'specialization' => 'required',
            'birth_date' => 'required|date',
            'birth_place' => 'required',
            'phone_number' => 'required',
            'service_id' => 'required|exists:services,id',
            'service_responsible_id' => 'exists:services,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $doctor = Doctor::create($request->all());

        return response()->json($doctor, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function doctor_show($id)
    {
        $doctor = Doctor::findOrFail($id);
        return response()->json($doctor);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function doctor_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'specialization' => 'required',
            'birth_date' => 'required|date',
            'birth_place' => 'required',
            'phone_number' => 'required',
            'service_id' => 'required|exists:services,id',
            'service_responsible_id' => 'exists:services,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $doctor = Doctor::findOrFail($id);
        $doctor->update($request->all());

        return response()->json($doctor, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function doctor_destroy($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->delete();

        return response()->json(['message' => 'Doctor deleted successfully']);
    }


    public function hospitalization_index()
    {
        $hospitalizations = Hospitalization::all();
        return response()->json($hospitalizations);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function hospitalization_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'time' => 'required',
            'service_id' => 'required|exists:services,id',
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'nurse_id' => 'required|exists:nurses,id',
            'bed_id' => 'required|exists:beds,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $hospitalization = Hospitalization::create($request->all());

        return response()->json($hospitalization, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function hospitalization_show($id)
    {
        $hospitalization = Hospitalization::findOrFail($id);
        return response()->json($hospitalization);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function hospitalization_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'time' => 'required',
            'service_id' => 'required|exists:services,id',
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'nurse_id' => 'required|exists:nurses,id',
            'bed_id' => 'required|exists:beds,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $hospitalization = Hospitalization::findOrFail($id);
        $hospitalization->update($request->all());

        return response()->json($hospitalization, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function hospitalization_destroy($id)
    {
        $hospitalization = Hospitalization::findOrFail($id);
        $hospitalization->delete();

        return response()->json(['message' => 'Hospitalization deleted successfully']);
    }


    public function hospitalization_request_index()
    {
        $requests = HospitalizationRequest::all();
        return response()->json($requests);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function hospitalization_request_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'time' => 'required',
            'status' => 'required',
            'response' => 'required',
            'service_id' => 'required|exists:services,id',
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'nurse_id' => 'required|exists:nurses,id',
            'bed_id' => 'required|exists:beds,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $request = HospitalizationRequest::create($request->all());

        return response()->json($request, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function hospitalization_request_show($id)
    {
        $request = HospitalizationRequest::findOrFail($id);
        return response()->json($request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function hospitalization_request_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'time' => 'required',
            'status' => 'required',
            'response' => 'required',
            'service_id' => 'required|exists:services,id',
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'nurse_id' => 'required|exists:nurses,id',
            'bed_id' => 'required|exists:beds,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $request = HospitalizationRequest::findOrFail($id);
        $request->update($request->all());

        return response()->json($request, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function hospitalization_request_destroy($id)
    {
        $request = HospitalizationRequest::findOrFail($id);
        $request->delete();

        return response()->json(['message' => 'Hospitalization request deleted successfully']);
    }

    public function invoice_index()
    {
        $invoices = Invoice::all();
        return response()->json($invoices);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function invoice_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'tax' => 'required',
            'total' => 'required',
            'total_amount' => 'required',
            'patient_id' => 'required|exists:patients,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $invoice = Invoice::create($request->all());

        return response()->json($invoice, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function invoice_show($id)
    {
        $invoice = Invoice::findOrFail($id);
        return response()->json($invoice);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function invoice_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'tax' => 'required',
            'total' => 'required',
            'total_amount' => 'required',
            'patient_id' => 'required|exists:patients,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $invoice = Invoice::findOrFail($id);
        $invoice->update($request->all());

        return response()->json($invoice, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function invoice_destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();

        return response()->json(['message' => 'Invoice deleted successfully']);
    }



    public function medication_index()
    {
        $medications = Medication::all();
        return response()->json($medications);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function medication_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'medication' => 'required',
            'dosage' => 'required',
            'quantity' => 'required',
            'dosage_form' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $medication = Medication::create($request->all());

        return response()->json($medication, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function medication_show($id)
    {
        $medication = Medication::findOrFail($id);
        return response()->json($medication);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function medication_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'medication' => 'required',
            'dosage' => 'required',
            'quantity' => 'required',
            'dosage_form' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $medication = Medication::findOrFail($id);
        $medication->update($request->all());

        return response()->json($medication, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function medication_destroy($id)
    {
        $medication = Medication::findOrFail($id);
        $medication->delete();

        return response()->json(['message' => 'Medication deleted successfully']);
    }

    public function nurse_index()
    {
        $nurses = Nurse::all();
        return response()->json($nurses);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function nurse_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'natinal_id' => 'required',
            'natonality' => 'required',
            'blood_group' => 'required',
            'phone_number' => 'required',
            'gender' => 'required',
            'birth_date' => 'required',
            'birth_place' => 'required',
            'family_situation' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $nurse = Nurse::create($request->all());

        return response()->json($nurse, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function nurse_show($id)
    {
        $nurse = Nurse::findOrFail($id);
        return response()->json($nurse);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function nurse_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'natinal_id' => 'required',
            'natonality' => 'required',
            'blood_group' => 'required',
            'phone_number' => 'required',
            'gender' => 'required',
            'birth_date' => 'required',
            'birth_place' => 'required',
            'family_situation' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $nurse = Nurse::findOrFail($id);
        $nurse->update($request->all());

        return response()->json($nurse, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function nurse_destroy($id)
    {
        $nurse = Nurse::findOrFail($id);
        $nurse->delete();

        return response()->json(['message' => 'Nurse deleted successfully']);
    }

    public function patient_index()
    {
        $patients = Patient::all();
        return response()->json($patients);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function patient_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'natinal_id' => 'required',
            'natonality' => 'required',
            'blood_group' => 'required',
            'phone_number' => 'required',
            'gender' => 'required',
            'birth_date' => 'required',
            'birth_place' => 'required',
            'family_situation' => 'required',
            'person_contact' => 'required',
            'person_contact_phone' => 'required',
            'person_contact_wilaya' => 'required',
            'height' => 'required',
            'weight' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $patient = Patient::create($request->all());

        return response()->json($patient, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function patient_show($id)
    {
        $patient = Patient::findOrFail($id);
        return response()->json($patient);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function patient_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'natinal_id' => 'required',
            'natonality' => 'required',
            'blood_group' => 'required',
            'phone_number' => 'required',
            'gender' => 'required',
            'birth_date' => 'required',
            'birth_place' => 'required',
            'family_situation' => 'required',
            'person_contact' => 'required',
            'person_contact_phone' => 'required',
            'person_contact_wilaya' => 'required',
            'height' => 'required',
            'weight' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $patient = Patient::findOrFail($id);
        $patient->update($request->all());

        return response()->json($patient, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function patient_destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();

        return response()->json(['message' => 'Patient deleted successfully']);
    }



    public function order_index()
    {
        $pharmacyOrders = PharmacyOrder::all();
        return response()->json(['data' => $pharmacyOrders]);
    }

    public function order_show($id)
    {
        $pharmacyOrder = PharmacyOrder::findOrFail($id);
        return response()->json(['data' => $pharmacyOrder]);
    }

    public function order_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_date' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $pharmacyOrder = PharmacyOrder::create($request->all());

        return response()->json(['data' => $pharmacyOrder], 201);
    }

    public function order_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'order_date' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $pharmacyOrder = PharmacyOrder::findOrFail($id);
        $pharmacyOrder->update($request->all());

        return response()->json(['data' => $pharmacyOrder]);
    }

    public function order_destroy($id)
    {
        $pharmacyOrder = PharmacyOrder::findOrFail($id);
        $pharmacyOrder->delete();

        return response()->json(['message' => 'Pharmacy order deleted successfully']);
    }

    public function prescription_index()
    {
        $prescriptions = Prescription::all();
        return response()->json(['data' => $prescriptions]);
    }

    public function prescription_show($id)
    {
        $prescription = Prescription::findOrFail($id);
        return response()->json(['data' => $prescription]);
    }

    public function prescription_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $prescription = Prescription::create($request->all());

        return response()->json(['data' => $prescription], 201);
    }

    public function prescription_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $prescription = Prescription::findOrFail($id);
        $prescription->update($request->all());

        return response()->json(['data' => $prescription]);
    }

    public function prescription_destroy($id)
    {
        $prescription = Prescription::findOrFail($id);
        $prescription->delete();

        return response()->json(['message' => 'Prescription deleted successfully']);
    }

    public function report_index()
    {
        $reports = Report::all();
        return response()->json(['data' => $reports]);
    }

    public function report_show($id)
    {
        $report = Report::findOrFail($id);
        return response()->json(['data' => $report]);
    }

    public function report_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'chief_complaint' => 'required',
            'hpi' => 'required',
            'pmh' => 'required',
            'ros' => 'required',
            'physical_examination' => 'required',
            'ad' => 'required',
            'date' => 'required',
            'conclusion' => 'required',
            'treatment_plan' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $report = Report::create($request->all());

        return response()->json(['data' => $report], 201);
    }

    public function report_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'chief_complaint' => 'required',
            'hpi' => 'required',
            'pmh' => 'required',
            'ros' => 'required',
            'physical_examination' => 'required',
            'ad' => 'required',
            'date' => 'required',
            'conclusion' => 'required',
            'treatment_plan' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $report = Report::findOrFail($id);
        $report->update($request->all());

        return response()->json(['data' => $report]);
    }

    public function report_destroy($id)
    {
        $report = Report::findOrFail($id);
        $report->delete();

        return response()->json(['message' => 'Report deleted successfully']);
    }

    public function service_record_index()
    {
        $records = ServiceRecord::all();
        return response()->json(['data' => $records]);
    }

    public function service_record_show($id)
    {
        $record = ServiceRecord::findOrFail($id);
        return response()->json(['data' => $record]);
    }

    public function service_record_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'time' => 'required',
            'date' => 'required',
            'title' => 'required',
            'summary' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $record = ServiceRecord::create($request->all());

        return response()->json(['data' => $record], 201);
    }

    public function service_record_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'time' => 'required',
            'date' => 'required',
            'title' => 'required',
            'summary' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $record = ServiceRecord::findOrFail($id);
        $record->update($request->all());

        return response()->json(['data' => $record]);
    }

    public function service_record_destroy($id)
    {
        $record = ServiceRecord::findOrFail($id);
        $record->delete();

        return response()->json(['message' => 'Service record deleted successfully']);
    }
}