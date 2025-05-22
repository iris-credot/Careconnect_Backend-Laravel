<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ReportController;

// Routes with role-based JWT middleware applied per your example
Route::prefix('reports')->group(function () {

    // Only doctors and admins can create, list all, update, delete, and get reports by doctor
    Route::middleware(['jwt:doctor,admin'])->group(function () {
        Route::post('/create', [ReportController::class, 'createReport']);                  // POST /reports/create
        Route::get('/all', [ReportController::class, 'getAllReports']);                     // GET /reports/all
        Route::get('/doctor/{doctorId}', [ReportController::class, 'getReportsByDoctor']);  // GET /reports/doctor/{doctorId}
        Route::put('/update/{id}', [ReportController::class, 'updateReport']);                     // PUT /reports/{id}
        Route::delete('/delete/{id}', [ReportController::class, 'deleteReport']);           // DELETE /reports/delete/{id}
    });

    // Any authenticated user can get report by ID or get reports by patient
    Route::middleware(['jwt'])->group(function () {
        Route::get('/getOne/{id}', [ReportController::class, 'getReportById']);                    // GET /reports/{id}
        Route::get('/patient/{patientId}', [ReportController::class, 'getReportsByPatient']); // GET /reports/patient/{patientId}
    });
});
