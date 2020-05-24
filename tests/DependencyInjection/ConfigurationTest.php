<?php declare(strict_types=1);

namespace Gos\Bundle\WebSocketBundle\Tests\DependencyInjection;

use Gos\Bundle\WebSocketBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;

final class ConfigurationTest extends TestCase
{
    public function testDefaultConfig(): void
    {
        $config = (new Processor())->processConfiguration(new Configuration(), []);

        $this->assertEquals(self::getBundleDefaultConfig(), $config);
    }

    public function testConfigWithAServer(): void
    {
        $extraConfig = [
            'server' => [
                'host' => '127.0.0.1',
                'port' => 8080,
                'origin_check' => false,
                'keepalive_ping' => false,
                'keepalive_interval' => 30,
            ],
        ];

        $config = (new Processor())->processConfiguration(new Configuration(), [$extraConfig]);

        $this->assertEquals(
            array_merge(self::getBundleDefaultConfig(), $extraConfig),
            $config
        );
    }

    public function testConfigWithAServerAndPubSubRouterWithoutArrayResources(): void
    {
        $extraConfig = [
            'server' => [
                'host' => '127.0.0.1',
                'port' => 8080,
                'origin_check' => false,
                'keepalive_ping' => false,
                'keepalive_interval' => 30,
                'router' => [
                    'resources' => [
                        'example.yaml',
                    ],
                ],
            ],
        ];

        $normalizedExtraConfig = [
            'server' => [
                'host' => '127.0.0.1',
                'port' => 8080,
                'origin_check' => false,
                'keepalive_ping' => false,
                'keepalive_interval' => 30,
                'router' => [
                    'resources' => [
                        [
                            'resource' => 'example.yaml',
                            'type' => null,
                        ],
                    ],
                ],
            ],
        ];

        $config = (new Processor())->processConfiguration(new Configuration(), [$extraConfig]);

        $this->assertEquals(
            array_merge(self::getBundleDefaultConfig(), $normalizedExtraConfig),
            $config
        );
    }

    public function testConfigWithAServerAndPubSubRouterWithArrayResources(): void
    {
        $extraConfig = [
            'server' => [
                'host' => '127.0.0.1',
                'port' => 8080,
                'origin_check' => false,
                'keepalive_ping' => false,
                'keepalive_interval' => 30,
                'router' => [
                    'resources' => [
                        [
                            'resource' => 'example.yaml',
                            'type' => null,
                        ],
                    ],
                ],
            ],
        ];

        $config = (new Processor())->processConfiguration(new Configuration(), [$extraConfig]);

        $this->assertEquals(
            array_merge(self::getBundleDefaultConfig(), $extraConfig),
            $config
        );
    }

    public function testConfigWithPingServices(): void
    {
        $extraConfig = [
            'ping' => [
                'services' => [
                    [
                        'name' => 'doctrine_service',
                        'type' => Configuration::PING_SERVICE_TYPE_DOCTRINE,
                    ],
                    [
                        'name' => 'pdo_service',
                        'type' => Configuration::PING_SERVICE_TYPE_PDO,
                    ],
                ],
            ],
        ];

        $config = (new Processor())->processConfiguration(new Configuration(), [$extraConfig]);

        $this->assertEquals(
            array_merge(self::getBundleDefaultConfig(), $extraConfig),
            $config
        );
    }

    public function testConfigWithUnsupportedPingServiceType(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('The value "no_support" is not allowed for path "gos_web_socket.ping.services.0.type". Permissible values: "doctrine", "pdo"');

        $extraConfig = [
            'ping' => [
                'services' => [
                    [
                        'name' => 'no_support_service',
                        'type' => 'no_support',
                    ],
                ],
            ],
        ];

        (new Processor())->processConfiguration(new Configuration(), [$extraConfig]);
    }

    protected static function getBundleDefaultConfig(): array
    {
        return [
            'client' => [
                'firewall' => 'ws_firewall',
                'storage' => [
                    'driver' => 'gos_web_socket.client.driver.in_memory',
                    'ttl' => 900,
                ],
            ],
            'shared_config' => true,
            'origins' => [],
        ];
    }
}