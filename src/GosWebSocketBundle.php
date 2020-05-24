<?php declare(strict_types=1);

namespace Gos\Bundle\WebSocketBundle;

use Gos\Bundle\WebSocketBundle\DependencyInjection\CompilerPass\PeriodicCompilerPass;
use Gos\Bundle\WebSocketBundle\DependencyInjection\CompilerPass\RegisterTwigGlobalsCompilerPass;
use Gos\Bundle\WebSocketBundle\DependencyInjection\CompilerPass\RegisterWebsocketRouterResourcesCompilerPass;
use Gos\Bundle\WebSocketBundle\DependencyInjection\CompilerPass\RpcCompilerPass;
use Gos\Bundle\WebSocketBundle\DependencyInjection\CompilerPass\ServerCompilerPass;
use Gos\Bundle\WebSocketBundle\DependencyInjection\CompilerPass\TopicCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Johann Saunier <johann_27@hotmail.fr>
 */
class GosWebSocketBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container
            ->addCompilerPass(new ServerCompilerPass())
            ->addCompilerPass(new RpcCompilerPass())
            ->addCompilerPass(new TopicCompilerPass())
            ->addCompilerPass(new PeriodicCompilerPass())
            ->addCompilerPass(new RegisterTwigGlobalsCompilerPass())
            ->addCompilerPass(new RegisterWebsocketRouterResourcesCompilerPass())
        ;
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}