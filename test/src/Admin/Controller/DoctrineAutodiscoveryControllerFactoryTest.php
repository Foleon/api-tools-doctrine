<?php

declare(strict_types=1);

namespace LaminasTest\ApiTools\Doctrine\Admin\Controller;

use interop\container\containerinterface;
use Laminas\ApiTools\Doctrine\Admin\Controller\DoctrineAutodiscoveryController;
use Laminas\ApiTools\Doctrine\Admin\Controller\DoctrineAutodiscoveryControllerFactory;
use Laminas\ApiTools\Doctrine\Admin\Model\DoctrineAutodiscoveryModel;
use Laminas\ServiceManager\AbstractPluginManager;
use LaminasTest\ApiTools\Doctrine\DeprecatedAssertionsTrait;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ProphecyInterface;

class DoctrineAutodiscoveryControllerFactoryTest extends TestCase
{
    use DeprecatedAssertionsTrait;
    use ProphecyTrait;

    /** @var ProphecyInterface|containerinterface */
    private $container;

    /** @var DoctrineAutodiscoveryModel */
    private $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model     = $this->prophesize(DoctrineAutodiscoveryModel::class)->reveal();
        $this->container = $this->prophesize(containerinterface::class);
        $this->container->get(DoctrineAutodiscoveryModel::class)->willReturn($this->model);
    }

    public function testInvokableFactoryReturnsDoctrineAutodiscoveryController(): void
    {
        $factory    = new DoctrineAutodiscoveryControllerFactory();
        $controller = $factory($this->container->reveal(), DoctrineAutodiscoveryController::class);

        $this->assertInstanceOf(DoctrineAutodiscoveryController::class, $controller);
        $this->assertAttributeSame($this->model, 'model', $controller);
    }

    public function testLegacyFactoryReturnsDoctrineAutodiscoveryController(): void
    {
        $controllers = $this->prophesize(AbstractPluginManager::class);
        $controllers->getServiceLocator()->willReturn($this->container->reveal());

        $factory    = new DoctrineAutodiscoveryControllerFactory();
        $controller = $factory->createService($controllers->reveal());

        $this->assertInstanceOf(DoctrineAutodiscoveryController::class, $controller);
        $this->assertAttributeSame($this->model, 'model', $controller);
    }
}
