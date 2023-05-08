<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'scopes' => \Laravel\Passport\Http\Middleware\CheckScopes::class,
        'scope' => \Laravel\Passport\Http\Middleware\CheckForAnyScope::class,
        'is-sales-manager' => \App\Http\Middleware\isSalesManager::class,
        'is-mechanism-coordinator' => \App\Http\Middleware\isMechanismCoordinator::class,
        'is-truck-exist' => \App\Http\Middleware\isTruckExist::class,
        'is-driver-exist' => \App\Http\Middleware\isDriverExist::class,
        'is-deleted-farm-exist' => \App\Http\Middleware\isDeletedFarmExist::class,
        'check-reciept-id' => \App\Http\Middleware\checkRecieptId::class,
        'check-reciept-weighted' => \App\Http\Middleware\isRecieptWeighted::class,
        'check-reciept-not-weighted' => \App\Http\Middleware\isRecieptNotWeighted::class,
        'is-libra-commander-exist' => \App\Http\Middleware\isLibraCommander::class,
        'is-deleted-truck-exist' => \App\Http\Middleware\isDeletedTruckExist::class,
        'is-deleted-driver-exist' => \App\Http\Middleware\isDeletedDriverExist::class,
        'is-trip-exist' => \App\Http\Middleware\isTripExist::class,
        'is-sales-purchase-exist' => \App\Http\Middleware\isSalesPurchaseExist::class,
        'is-note-exist' => \App\Http\Middleware\isNoteExist::class,
        'is-selling-port-exist' => \App\Http\Middleware\isSellingPortExist::class,
        'is-deleted-selling-port-exist' => \App\Http\Middleware\isDeletedSellingPortExist::class,
        'is-farm-exist' => \App\Http\Middleware\isFarmExist::class,
        'is-selling-port-order' => \App\Http\Middleware\isSellingPortOrderExist::class,
        'is-selling-port-order-delete' => \App\Http\Middleware\isSellingPortOrderDelete::class,
        'is-request-accept' => \App\Http\Middleware\isAcceptFromCeo::class,
        'is-production-manager' => \App\Http\Middleware\isProductionManager::class,
        'is-deleted-offer' => \App\Http\Middleware\isDeletedOffer::class,
        'check-scope-selling-port' => \App\Http\Middleware\checkScopeSellingPort::class,
        'check-scope-managers' => \App\Http\Middleware\checkScopeManagers::class,
        'check-scope-farms' => \App\Http\Middleware\checkScopeFarms::class,
        'check-offer-exist' => \App\Http\Middleware\isOfferExist::class,
        'is-request-exist' => \App\Http\Middleware\isRequestExist::class,
        'is-accounting-manager' => \App\Http\Middleware\isAccountingManager::class,
        'is-approved-material' => \App\Http\Middleware\isApprovedMaterial::class,
        'is-slaughter-supervisor' => \App\Http\Middleware\isSlaughterSupervisor::class,
        'is-deleted-type' => \App\Http\Middleware\isDeletedType::class,
        'is-current-weight-and-id' => \App\Http\Middleware\isCurrentWeightAndId::class,
        'is-exist-input-slaughters' => \App\Http\Middleware\isExistIdInInputSlaughters::class,
        'is-exist-type-id-input-slaughters' => \App\Http\Middleware\isExistTypeIdInInputSlaughters::class,
        'is-exist-input-production' => \App\Http\Middleware\isExistInputProduction::class,
        'is-already-direct-to' => \App\Http\Middleware\isAlreadyDirectTo::class,
        'is-cutting-supervisor' => \App\Http\Middleware\isCuttingSupervisor::class,
        'is-exist-type-id-input-cutting' => \App\Http\Middleware\isExistTypeIdInputCutting::class,
        'is-exist-input-cutting' => \App\Http\Middleware\isExistInputCutting::class,
        'is-manufacturing-supervisor' => \App\Http\Middleware\isManufacturingSupervisor::class,
        'is-exist-type-id-input-munufacturing' => \App\Http\Middleware\isExistTypeIdInputMunufacturingn::class,
        'is-exist-input-munufacturing' => \App\Http\Middleware\isExistInputMunufacturing::class,
        'is-user-has-permission-to-read-poultry-detection' => \App\Http\Middleware\checkReadPoultryRecieptPermission::class,


    ];
}
