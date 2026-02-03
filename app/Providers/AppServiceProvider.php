<?php

namespace App\Providers;

use App\Models\Prestasi;
use App\Models\StrukturOrganisasi;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
// Import Interface dan Service
use App\Contracts\Services\PendaftaranServiceInterface;
use App\Services\PendaftaranService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // ======================================================
        // TAMBAHKAN BINDING INI DI DALAM METHOD REGISTER()
        // ======================================================
        $this->app->bind(
            PendaftaranServiceInterface::class, // Jika ada yang minta interface ini...
            PendaftaranService::class         // ...berikan implementasi ini.
        );

        // Binding lain bisa ditambahkan di sini...
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Kode di dalam boot() biarkan saja, sudah benar untuk fungsinya
        // if (env('APP_ENV') === 'production') {
        //     URL::forceScheme('https');
        // }
        $this->loadViewsFrom(resource_path('views/ppdb'), 'ppdb');
        $this->loadViewsFrom(resource_path('views/murid'), 'murid');
        $this->loadViewsFrom(resource_path('views/spp'), 'spp');
        
        // View Composer untuk Header
        View::composer('frontend.content.header', function ($view) {
            $prestasiM = Prestasi::all(); 
            $strukturM = StrukturOrganisasi::latest()->get(); 
            $view->with(compact('prestasiM', 'strukturM'));
        });
    }
}