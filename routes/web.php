<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CompanyAdminController;
use App\Http\Controllers\Admin\InboxController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\App\AppPageController;
use App\Http\Controllers\App\CompanyController;
use App\Http\Controllers\App\PolicyAcceptanceController;
use App\Http\Controllers\App\SettingsController;
use App\Http\Controllers\App\TeamController;
use App\Http\Controllers\JoinController;
use App\Http\Controllers\Public\ConsentController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\PageController;
use App\Http\Controllers\Public\PrivacyRequestController;
use App\Http\Controllers\Public\SeoController;
use App\Http\Controllers\ShareController;
use Illuminate\Support\Facades\Route;

/*
| Public website
*/
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/tungkol', [PageController::class, 'about'])->name('about');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
Route::get('/paano-gamitin', [PageController::class, 'help'])->name('help');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/cookies', [PageController::class, 'cookies'])->name('cookies');
Route::get('/accessibility', [PageController::class, 'accessibility'])->name('accessibility');

Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:contact')->name('contact.store');
Route::get('/privacy-request', [PrivacyRequestController::class, 'show'])->name('privacy-request');
Route::post('/privacy-request', [PrivacyRequestController::class, 'store'])->middleware('throttle:privacy-request')->name('privacy-request.store');
Route::post('/consent', [ConsentController::class, 'store'])->middleware('throttle:consent')->name('consent.store');

Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots']);

// Public read-only share page: Available / Wala only.
Route::get('/u/{token}', [ShareController::class, 'show'])
    ->middleware(['throttle:share-page', 'noindex'])
    ->where('token', '[A-Za-z0-9]{32,64}')
    ->name('share.show');

// Invite link: guests go to sign-up, signed-in users confirm.
Route::get('/join/{token}', [JoinController::class, 'show'])->where('token', '[A-Za-z0-9]{40}')->middleware('noindex')->name('join.link');
Route::post('/join/{token}', [JoinController::class, 'accept'])->where('token', '[A-Za-z0-9]{40}')
    ->middleware(['auth', 'verified', 'throttle:join-code'])->name('join.accept');

if (app()->environment('local')) {
    Route::view('/styleguide', 'styleguide')->name('styleguide');
}

/*
| Policy re-acceptance (outside the "policies" middleware on purpose)
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/app/policies', [PolicyAcceptanceController::class, 'show'])->name('policies.show');
    Route::post('/app/policies', [PolicyAcceptanceController::class, 'accept'])->name('policies.accept');
});

/*
| App
*/
Route::middleware(['auth', 'verified', 'policies'])->prefix('app')->group(function () {
    Route::get('/', [AppPageController::class, 'home'])->name('app.home');
    Route::get('/kalendaryo', [AppPageController::class, 'myCalendar'])->name('app.calendar');
    Route::get('/team-kalendaryo', [AppPageController::class, 'teamCalendar'])->name('app.team-calendar');

    Route::get('/team', [TeamController::class, 'index'])->name('app.team');
    Route::post('/team/regenerate', [TeamController::class, 'regenerate'])->name('app.team.regenerate');
    Route::post('/team/{membership}/promote', [TeamController::class, 'promote'])->name('app.team.promote');
    Route::post('/team/{membership}/demote', [TeamController::class, 'demote'])->name('app.team.demote');
    Route::delete('/team/{membership}', [TeamController::class, 'remove'])->name('app.team.remove');

    Route::get('/settings', [SettingsController::class, 'show'])->name('settings');
    Route::post('/settings/sessions', [SettingsController::class, 'logoutOtherSessions'])->name('settings.sessions.destroy');
    Route::post('/settings/share', [SettingsController::class, 'toggleShare'])->name('settings.share');
    Route::post('/settings/share/regenerate', [SettingsController::class, 'regenerateShare'])->name('settings.share.regenerate');
    Route::post('/settings/shortcuts', [SettingsController::class, 'shortcuts'])->name('settings.shortcuts');
    Route::get('/settings/export', [SettingsController::class, 'export'])->middleware('throttle:export')->name('settings.export');
    Route::delete('/settings/account', [SettingsController::class, 'destroyAccount'])->name('settings.account.destroy');

    Route::post('/settings/company', [CompanyController::class, 'store'])->name('company.store');
    Route::post('/settings/company/join', [CompanyController::class, 'join'])->middleware('throttle:join-code')->name('company.join');
    Route::post('/settings/company/leave', [CompanyController::class, 'leave'])->name('company.leave');
    Route::put('/settings/company', [CompanyController::class, 'update'])->name('company.update');
    Route::delete('/settings/company', [CompanyController::class, 'destroy'])->name('company.destroy');
});

/*
| Super Admin
*/
Route::middleware(['auth', 'verified', 'super-admin', 'noindex'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/audit', [AdminController::class, 'audit'])->name('audit');

    Route::get('/companies', [CompanyAdminController::class, 'index'])->name('companies');
    Route::post('/companies/{company}/approve', [CompanyAdminController::class, 'approve'])->name('companies.approve');
    Route::post('/companies/{company}/suspend', [CompanyAdminController::class, 'suspend'])->name('companies.suspend');
    Route::post('/companies/{company}/reactivate', [CompanyAdminController::class, 'approve'])->name('companies.reactivate');
    Route::delete('/companies/{company}', [CompanyAdminController::class, 'destroy'])->name('companies.destroy');

    Route::get('/users', [UserAdminController::class, 'index'])->name('users');
    Route::post('/users/{user}/suspend', [UserAdminController::class, 'suspend'])->name('users.suspend');
    Route::post('/users/{user}/reactivate', [UserAdminController::class, 'reactivate'])->name('users.reactivate');

    Route::get('/messages', [InboxController::class, 'messages'])->name('messages');
    Route::post('/messages/{message}/read', [InboxController::class, 'markRead'])->name('messages.read');
    Route::get('/privacy-requests', [InboxController::class, 'privacyRequests'])->name('privacy-requests');
    Route::post('/privacy-requests/{privacyRequest}/done', [InboxController::class, 'markDone'])->name('privacy-requests.done');
});
