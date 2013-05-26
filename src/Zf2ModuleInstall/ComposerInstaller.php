<?php
namespace Zf2ModuleInstall;

use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;

class ComposerInstaller extends LibraryInstaller
{

    public function getInstallPath(PackageInterface $package) {

        list($vendor, $name) = explode('/', $package->getName());
        $names = explode('-', $name);
        foreach($names as $index=>$name) {
            $names[$index] = ucfirst($name);
        }

        return "module/".implode('', $names);
    }

    public function supports($packageType) {
        return $packageType == 'zf2-module';
    }
}
