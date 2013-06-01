<?php
namespace Zf2ModuleInstall;

use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;
use Composer\Repository\InstalledRepositoryInterface;

class ComposerInstaller extends LibraryInstaller
{
    public function install(
        InstalledRepositoryInterface $repo, 
        PackageInterface $package)
    {
        parent::install($repo, $package);
        $this->registerModule($this->getModuleName($package->getName()));
    }

    public function getInstallPath(PackageInterface $package)
    {
        $moduleName = $this->getModuleName($package->getName());
        return "module/" . $moduleName;
    }

    public function supports($packageType)
    {
        return $packageType == 'zf2-module';
    }

    protected function registerModule($moduleName)
    {
        $applicationConfigFile = $this->vendorDir 
            . '/../config/application.config.php';
        if(file_exists($applicationConfigFile)) {
            $config = file_get_contents($applicationConfigFile);
            $config = preg_replace(
                '/(\'modules\'\s?\=\>\s?array\([^\)]*)(\))/', 
                sprintf("$1\r\n        '%s',)", $moduleName), 
                $config);
            file_put_contents($applicationConfigFile, $config);
        }
    }

    protected function getModuleName($packageName)
    {
        list($vendor, $name) = explode('/', $packageName);
        $names = explode('-', $name);
        foreach($names as $index=>$name) {
            $names[$index] = ucfirst($name);
        }
        return implode('', $names);
    }
}
