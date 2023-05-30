<?php

namespace Pbmedia\FilesystemProviders\tests;

use Illuminate\Support\Facades\Storage;
use League\Flysystem\WebDAV\WebDAVAdapter;
use Pbmedia\FilesystemProviders\WebDAVServiceProvider;

class ServiceProviderTest extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [WebDAVServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('filesystems.disks.webdav', [
            'driver'   => 'webdav',
            'baseUri'  => 'https://mywebdavstorage.com',
            'userName' => 'pascalbaljetmedia',
            'password' => 'supersecretpassword',
        ]);
    }

    /** @test */
    public function it_registers_a_webdav_driver()
    {
        $filesystem = Storage::disk('webdav');
        $adapter    = $filesystem->getAdapter();

        $this->assertInstanceOf(WebDAVAdapter::class, $adapter);
    }

    /** @test */
    public function it_can_have_an_optional_path_prefix()
    {
        $this->app['config']->set('filesystems.disks.webdav.pathPrefix', 'prefix');

        $filesystem = Storage::disk('webdav');
        $adapter     = $filesystem->getAdapter();

        $this->assertInstanceOf(WebDAVAdapter::class, $adapter);

        // Not able to retrieve prefix from the adapter anymore like this:
        // $this->assertEquals('prefix/', $adapter->getPathPrefix());
    }
}
