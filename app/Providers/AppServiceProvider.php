<?php

namespace App\Providers;

use App\Models\Collection;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\TreasurerRecord;
use App\Models\User;
use App\Models\Vendor;
use App\Policies\CollectionPolicy;
use App\Policies\ProductPolicy;
use App\Policies\TransactionPolicy;
use App\Policies\TreasurerRecordPolicy;
use App\Policies\VendorPolicy;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Throwable;

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
        Paginator::useBootstrapFive();

        Gate::before(function (User $user) {
            return $user->hasRole('Admin') ? true : null;
        });

        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(Vendor::class, VendorPolicy::class);
        Gate::policy(Collection::class, CollectionPolicy::class);
        Gate::policy(Transaction::class, TransactionPolicy::class);
        Gate::policy(TreasurerRecord::class, TreasurerRecordPolicy::class);

        Gate::define('manage-users', fn (User $user): bool => $user->hasRole('Admin'));
        Gate::define('manage-settings', fn (User $user): bool => $user->hasRole('Admin'));
        Gate::define('manage-vendors', fn (User $user): bool => $user->hasAnyRole(['Admin', 'Manager']));
        Gate::define('view-analytics', fn (User $user): bool => $user->hasAnyRole(['Admin', 'Manager']));
        Gate::define('manage-products', fn (User $user): bool => $user->hasRole('Vendor'));
        Gate::define('collect-payments', fn (User $user): bool => $user->hasAnyRole(['Admin', 'Collector']));
        Gate::define('verify-collections', fn (User $user): bool => $user->hasAnyRole(['Admin', 'Treasurer']));

        View::composer('*', function ($view): void {
            static $settings = null;

            try {
                if (! Schema::hasTable('settings')) {
                    $view->with('appSettings', collect());

                    return;
                }

                $settings ??= Setting::query()->pluck('value', 'key');

                $view->with('appSettings', $settings);
            } catch (Throwable) {
                $view->with('appSettings', collect());
            }
        });
    }
}
