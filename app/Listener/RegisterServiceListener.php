<?php

declare(strict_types=1);

namespace App\Listener;

use http\Exception\InvalidArgumentException;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Framework\Event\MainWorkerStart;
use Hyperf\Nacos\Exception\RuntimeException;
use Hyperf\ServiceGovernance\IPReaderInterface;
use Hyperf\ServiceGovernanceNacos\NacosDriver;
use Psr\Container\ContainerInterface;

/**
 * @Listener
 */
class RegisterServiceListener implements ListenerInterface
{
    /**
     * @var StdoutLoggerInterface
     */
    protected $logger;

    /**
     * @var ConfigInterface
     */
    protected $config;

    /**
     * @var IPReaderInterface
     */
    protected $ipReader;

    /**
     * @var NacosDriver
     */
    protected $nacosDriver;

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(StdoutLoggerInterface::class);
        $this->config = $container->get(ConfigInterface::class);
        $this->ipReader = $container->get(IPReaderInterface::class);
        $this->nacosDriver = $container->get(NacosDriver::class);
    }

    public function listen(): array
    {
        return [
            MainWorkerStart::class,
        ];
    }

    public function process(object $event)
    {
        $continue = true;
        while ($continue) {
            try {
                $services = $this->getServices();
                $servers = $this->getServers();
                foreach ($services as $serviceName => $paths) {
                    foreach ($paths as $service) {
                        if (! isset($service['publishTo'], $service['server'])) {
                            continue;
                        }
                        [$address, $port] = $servers[$service['server']];
                        if (! $this->nacosDriver->isRegistered($serviceName, $address, (int) $port, $service)) {
                            $this->nacosDriver->register($serviceName, $address, (int) $port, $service);
                        }
                    }
                }
                $continue = false;
            } catch (RuntimeException $throwable) {
                if (strpos($throwable->getMessage(), 'Connection failed') !== false) {
                    $this->logger->warning('Cannot register service, connection of service center failed, re-register after 10 seconds.');
                    sleep(10);
                } else {
                    throw $throwable;
                }
            }
        }
    }

    protected function getServices(): array
    {
        $files = scandir(BASE_PATH . '/grpc');
        $services = [];
        $metadata = ['grpc' => ['server' => 'grpc', 'protocol' => 'grpc', 'publishTo' => 'nacos']];
        foreach ($files as $file) {
            if (preg_match('/.*.proto/', $file)) {
                $str = file_get_contents(BASE_PATH . '/grpc/' . $file);
                if (empty($str)) {
                    continue;
                }
                preg_match_all('/package\s(.*);\s*service\s(.*)\s{/', $str, $matches);
                if (empty($matches[1]) || empty($matches[2])) {
                    continue;
                }
                $services["{$matches[1][0]}.{$matches[2][0]}"] = $metadata;
            }
        }
        return $services;
    }

    protected function getServers(): array
    {
        $result = [];
        $servers = $this->config->get('server.servers', []);
        foreach ($servers as $server) {
            if (! isset($server['name'], $server['host'], $server['port'])) {
                continue;
            }
            if (! $server['name']) {
                throw new InvalidArgumentException('Invalid server name');
            }
            $host = env('HOST') ?? $server['host'];
            if (in_array($host, ['0.0.0.0', 'localhost'])) {
                $host = $this->ipReader->read();
            }
            if (! filter_var($host, FILTER_VALIDATE_IP)) {
                throw new InvalidArgumentException(sprintf('Invalid host %s', $host));
            }
            $port = env('HOST') ? env('DOCKER_HOST_PORT') : $server['port'];
            if (! is_numeric($port) || ($port < 0 || $port > 65535)) {
                throw new InvalidArgumentException(sprintf('Invalid port %s', $port));
            }
            $port = (int) $port;
            $result[$server['name']] = [$host, $port];
        }
        return $result;
    }
}
