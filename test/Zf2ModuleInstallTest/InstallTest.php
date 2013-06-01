<?php
namespace Zf2ModuleInstallTest;

use PHPUnit_Framework_TestCase;

use Zf2ModuleInstall\ComposerInstaller;

use Composer\Util\Filesystem;
use Composer\Package\Package;
use Composer\Package\RootPackage;
use Composer\Composer;
use Composer\Config;

class InstallTest extends PHPUnit_Framework_TestCase
{
    private $composer;
    private $config;
    private $vendorDir;
    private $binDir;
    private $dm;
    private $repository;
    private $io;
    private $fs;
    private $configDir;

    /**
     * setUp
     *
     * @return void
     */
    public function setUp()
    {
        $this->fs = new Filesystem;

        $this->composer = new Composer();
        $this->config = new Config();
        $this->composer->setConfig($this->config);

        $this->vendorDir = realpath(
            sys_get_temp_dir()) . DIRECTORY_SEPARATOR . 'baton-test-vendor';
        $this->ensureDirectoryExistsAndClear($this->vendorDir);

        $this->binDir = realpath(
            sys_get_temp_dir()) . DIRECTORY_SEPARATOR . 'baton-test-bin';
        $this->ensureDirectoryExistsAndClear($this->binDir);

        $this->config->merge(array(
            'config' => array(
                'vendor-dir' => $this->vendorDir,
                'bin-dir' => $this->binDir,
            ),
        ));

        $this->dm = $this->getMockBuilder('Composer\Downloader\DownloadManager')
            ->disableOriginalConstructor()
            ->getMock();
        $this->composer->setDownloadManager($this->dm);

        $this->repository = $this->getMock(
            'Composer\Repository\InstalledRepositoryInterface');
        $this->io = $this->getMock('Composer\IO\IOInterface');

        $this->configDir = $this->vendorDir . '/../config';
        $this->ensureDirectoryExistsAndClear($this->configDir);
    }

    /**
     * tearDown
     *
     * @return void
     */
    public function tearDown()
    {
        $this->fs->removeDirectory($this->vendorDir);
        $this->fs->removeDirectory($this->binDir);
        $this->fs->removeDirectory($this->configDir);
    }


    public function testSupports()
    {
        $installer = new ComposerInstaller($this->io, $this->composer);
        $this->assertSame(true, $installer->supports('zf2-module'));
    }

    public function testGetInstallPath()
    {
        $installer = new ComposerInstaller($this->io, $this->composer);
        $package = new Package('abc/time-line', '1.0', '1.0');
        $package->setType('zf2-module');

        $result = $installer->getInstallPath($package);
        $this->assertEquals('module/TimeLine', $result);
    }

    /**
     * @dataProvider installData
     */
    public function testInstall($configContent)
    {
        file_put_contents(
            $this->configDir . '/application.config.php',
            $configContent
        );

        $installer = new ComposerInstaller($this->io, $this->composer);
        $package = new Package('abc/time-line', '1.0', '1.0');
        $package->setType('zf2-module');
        $installer->install($this->repository, $package);

        $config = include $this->configDir . '/application.config.php';
        $this->assertTrue(in_array('TimeLine', $config['modules']));
    }

    public function installData()
    {
        return array(
            array("<?php \r\n 
            return array(
                'modules' => array()
            );"),
            array("<?php return array('modules'=>array());"),
            array("<?php return array('modules'=>array('abc',));")
        );
    }

    protected function ensureDirectoryExistsAndClear($directory)
    {
        $fs = new Filesystem();
        if (is_dir($directory)) {
            $fs->removeDirectory($directory);
        }
        mkdir($directory, 0777, true);
    }

}
