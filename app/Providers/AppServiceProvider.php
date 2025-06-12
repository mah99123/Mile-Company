<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Campaign;
use App\Models\CarImport;
use App\Models\InstallmentPayment;
use App\Observers\SaleObserver;
use App\Observers\ProductObserver;
use App\Observers\CampaignObserver;
use App\Observers\CarImportObserver;
use App\Observers\InstallmentPaymentObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // تسجيل المراقبين
        Sale::observe(SaleObserver::class);
        Product::observe(ProductObserver::class);
        Campaign::observe(CampaignObserver::class);
        CarImport::observe(CarImportObserver::class);
        InstallmentPayment::observe(InstallmentPaymentObserver::class);
    }
}
