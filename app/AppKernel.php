<?php

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use AppBundle\AppBundle;
use Atico\Bundle\SpreadsheetTranslatorBundle\SpreadsheetTranslatorBundle;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles(): iterable
    {

        return [
            new FrameworkBundle(),
            new AppBundle(),
            new SpreadsheetTranslatorBundle(),
        ];
    }

    public function getRootDir(): string
    {
        return __DIR__;
    }

    public function getCacheDir(): string
    {
        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }

    public function getLogDir(): string
    {
        return dirname(__DIR__).'/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $path = sprintf(
            '%s/config/%2$s/config_%2$s.yml',
            __DIR__,
            $this->getEnvironment()
        );
        $loader->load($path);
    }
}
