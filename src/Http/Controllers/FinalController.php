<?php

namespace Gaurangadas\LaravelInstaller\Controllers;

use Illuminate\Routing\Controller;
use Gaurangadas\LaravelInstaller\Events\LaravelInstallerFinished;
use Gaurangadas\LaravelInstaller\Helpers\EnvironmentManager;
use Gaurangadas\LaravelInstaller\Helpers\FinalInstallManager;
use Gaurangadas\LaravelInstaller\Helpers\InstalledFileManager;

class FinalController extends Controller
{
    function __construct()
    {
        set_time_limit(300);
    }

    /**
     * Update installed file and display finished view.
     *
     * @param \Gaurangadas\LaravelInstaller\Helpers\InstalledFileManager $fileManager
     * @param \Gaurangadas\LaravelInstaller\Helpers\FinalInstallManager $finalInstall
     * @param \Gaurangadas\LaravelInstaller\Helpers\EnvironmentManager $environment
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function finish(InstalledFileManager $fileManager, FinalInstallManager $finalInstall, EnvironmentManager $environment)
    {
        $finalMessages = $finalInstall->runFinal();
        $finalStatusMessage = $fileManager->update();
        $finalEnvFile = $environment->getEnvContent();

        event(new LaravelInstallerFinished);

        return view('vendor.installer.finished', compact('finalMessages', 'finalStatusMessage', 'finalEnvFile'));
    }
}
