<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Http\Controllers\FollowupReasonController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\LeadStatusController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PackageController;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Artisan;

Route::get('/link-storage', function () {
    Artisan::call('storage:link');
    $output = Artisan::output();
    return back()->with('success', "Storage linked successfully! \n$output");
});

Route::get('/deploy', function () {
    $messages = [];

    $npm = new Process(['npm', 'run', 'build']);
    $npm->run();

    if (!$npm->isSuccessful()) {
        throw new ProcessFailedException($npm);
    }
    $messages[] = 'NPM build completed successfully.';

    // 2. Run optimize
    Artisan::call('optimize');
    $messages[] = 'Artisan optimize executed successfully.';

    // 3. Run migrate
    Artisan::call('migrate', ['--force' => true]);
    $messages[] = 'Database migrations executed successfully.';

    // 4. Run storage link
    Artisan::call('storage:link');
    $messages[] = 'Storage linked successfully.';

    // Join all messages into one flash message
    return back()->with('success', implode(' | ', $messages));
})->middleware('auth');

Route::get('/optimize-app', function () {
    Artisan::call('optimize');
    $output = Artisan::output();
    return back()->with('success', "App optimized successfully! \n$output");
});

Route::get('/run-npm-build', function () {
    $process = new Process(['npm', 'run', 'build']);
    $process->run();

    if (!$process->isSuccessful()) {
        throw new ProcessFailedException($process);
    }

    return back()->with('success', 'NPM Build completed successfully! Output: ' . $process->getOutput());
});

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(when(Features::canManageTwoFactorAuthentication() && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'), ['password.confirm'], []))
        ->name('two-factor.show');
});
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    // Admin routes group
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::resource('packages', PackageController::class);
Route::post('packages/{id}/restore', [PackageController::class, 'restore'])
        ->name('packages.restore');
        Route::resource('leads',LeadController::class);
            Route::resource('settings', \App\Http\Controllers\Admin\SettingController::class);
            Route::resource('roles', RoleController::class);

            Route::resource('followup-reasons', FollowupReasonController::class)->except(['create', 'show']);
            Route::resource('lead-statuses', LeadStatusController::class)->except(['create', 'show']);
        });
});
