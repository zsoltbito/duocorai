<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OpsDashboardController;
use App\Http\Controllers\SchedulerController;

Route::get("/", function () {
    return view("welcome");
});

Route::get("/dashboard", function () {
    return view("dashboard");
})
    ->middleware(["auth", "verified"])
    ->name("dashboard");

Route::middleware("auth")->group(function () {
    Route::get("/profile", [ProfileController::class, "edit"])->name(
        "profile.edit",
    );
    Route::patch("/profile", [ProfileController::class, "update"])->name(
        "profile.update",
    );
    Route::delete("/profile", [ProfileController::class, "destroy"])->name(
        "profile.destroy",
    );
});
Route::middleware(["auth"])->group(function () {
    Route::get("/ops", [OpsDashboardController::class, "index"])->name(
        "ops.dashboard",
    );
    Route::get("/ops/api/snapshot", [
        OpsDashboardController::class,
        "snapshot",
    ])->name("ops.snapshot");
});
Route::middleware(["auth"])->group(function () {
    Route::get("/scheduler", [SchedulerController::class, "index"])->name(
        "scheduler.index",
    );
    Route::get("/scheduler/create", [
        SchedulerController::class,
        "create",
    ])->name("scheduler.create");
    Route::post("/scheduler", [SchedulerController::class, "store"])->name(
        "scheduler.store",
    );
    Route::get("/scheduler/{task}/edit", [
        SchedulerController::class,
        "edit",
    ])->name("scheduler.edit");
    Route::put("/scheduler/{task}", [
        SchedulerController::class,
        "update",
    ])->name("scheduler.update");
    Route::post("/scheduler/{task}/toggle", [
        SchedulerController::class,
        "toggle",
    ])->name("scheduler.toggle");
    Route::post("/scheduler/{task}/run-now", [
        SchedulerController::class,
        "runNow",
    ])->name("scheduler.runNow");
});
require __DIR__ . "/auth.php";
