<?php

declare(strict_types=1);

namespace App\Tests;

use App\Kernel;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Kernel::class)]
final class KernelTest extends TestCase
{
    #[Test]
    public function kernelBootsSuccessfully(): void
    {
        $kernel = new Kernel('test', true);
        $kernel->boot();

        // Verify kernel has booted by checking it has a container
        $container = $kernel->getContainer();
        self::assertNotNull($container);
        self::assertTrue($container->has('translator'));

        $kernel->shutdown();
    }

    #[Test]
    public function kernelHasCorrectProjectDir(): void
    {
        $kernel = new Kernel('test', true);

        $projectDir = $kernel->getProjectDir();

        self::assertDirectoryExists($projectDir);
        self::assertDirectoryExists($projectDir . '/src');
        self::assertDirectoryExists($projectDir . '/config');
    }

    #[Test]
    public function kernelRunsInTestEnvironment(): void
    {
        $kernel = new Kernel('test', true);

        self::assertSame('test', $kernel->getEnvironment());
        self::assertTrue($kernel->isDebug());
    }
}
