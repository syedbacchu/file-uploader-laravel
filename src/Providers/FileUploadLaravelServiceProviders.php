<?php


namespace Sdtech\FileUploaderLaravel\Providers;


use Illuminate\Support\ServiceProvider;

class FileUploadLaravelServiceProviders extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @param
     */
    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../Config/fileuploaderlaravel.php', 'fileuploaderlaravel'
        );
        $this->publishFiles();
    }


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        
    }

    /**
     * Publish config file for the installer.
     *
     * @return void
     */
    protected function publishFiles()
    {
        $this->publishes([
            __DIR__ . '/../Config/fileuploaderlaravel.php' => config_path('fileuploaderlaravel.php'),
        ], 'fileuploaderlaravel');
    }

}
