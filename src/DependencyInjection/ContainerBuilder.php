<?php

declare(strict_types=1);

namespace Shared\Infrastructure\DependencyInjection;

use DI\ContainerBuilder as DiContainerBuilder;
use DI\Definition\Helper\AutowireDefinitionHelper;
use DI\Definition\Source\DefinitionSource;
use Exception;
use Psr\Container\ContainerInterface;
use ReflectionException;
use RuntimeException;
use Shared\Infrastructure\Resolver\Resolver;
use Shared\Infrastructure\Resolver\Types;
use Shared\Infrastructure\Settings\InMemorySettings;
use Shared\Infrastructure\Settings\SettingsInterface;
use function DI\autowire;

final class ContainerBuilder
{
    /** @var CompilerPass[]  */
    private array $passes = [];
    private ?string $rootPath = null;
    private ?string $rootNamespace = null;
    private ?string $resolverCachePathFile = null;

    private function __construct(
        private readonly DiContainerBuilder $containerBuilder,
        private readonly Resolver $resolver,
    ) {}

    public function addDefinitions(array|DefinitionSource|string ...$definitions): self
    {
        $this->containerBuilder->addDefinitions(...$definitions);

        return $this;
    }

    public function enableCompilation(string $directory): self
    {
        $this->containerBuilder->enableCompilation($directory);

        return $this;
    }

    public function addResolverCachePathFile(string $resolverCachePathFile): self
    {
        $this->resolverCachePathFile = $resolverCachePathFile;

        return $this;
    }

    public function addCompilerPasses(CompilerPass ...$compilerPasses): self
    {
        foreach ($compilerPasses as $compilerPass) {
            $this->addCompilerPass($compilerPass);
        }

        return $this;
    }

    public function addCompilerPass(CompilerPass $pass): self
    {
        if (array_key_exists($pass::class, $this->passes)) {
            $message = sprintf('CompilerPass %s already added. Cannot add the same pass twice', $pass::class);
            throw new RuntimeException($message);
        }

        $this->passes[$pass::class] = $pass;

        return $this;
    }

    public function findDefinition(string $id): AutowireDefinitionHelper
    {
        return autowire($id);
    }

    /**
     * @param class-string[] $ids
     *
     * @return AutowireDefinitionHelper[]
     */
    public function findDefinitions(array $ids): array
    {
        $definitions = [];
        foreach ($ids as $id) {
            $definitions[$id] = $this->findDefinition($id);
        }

        return $definitions;
    }

    /**
     * @param class-string $class
     *
     * @return string[]
     *
     * @throws ReflectionException
     */
    public function findClassesByResolver(string $class, Types $type, string $path): array
    {
        return $this->resolver->resolve(
            $class,
            $type,
            $path,
            $this->rootNamespace,
            $this->rootPath,
            $this->resolverCachePathFile
        );
    }

    /**
     * @throws Exception
     */
    public function build(): ContainerInterface
    {
        foreach ($this->passes as $pass) {
            $pass->process($this);
        }

        return $this->containerBuilder->build();
    }

    public static function create(): self
    {
        return new self(new DiContainerBuilder(), new Resolver());
    }

    public function addSettingsDefinition(array &$definitions): self
    {
        $definitions[SettingsInterface::class] = static function (ContainerInterface $container): InMemorySettings {
            return new InMemorySettings($container->get('settings'));
        };

        return $this;
    }

    public function addRootPath(string $rootPath): self
    {
        $this->rootPath = $rootPath;

        return $this;
    }

    public function addRootNamespace(string $rootNamespace): self
    {
        $this->rootNamespace = $rootNamespace;

        return $this;
    }

    public function useAutoWiring(bool $useAutoWiring = false): self
    {
        $this->containerBuilder->useAutowiring($useAutoWiring);

        return $this;
    }

    public function useAttributes(bool $useAttributes = false): self
    {
        $this->containerBuilder->useAttributes($useAttributes);

        return $this;
    }

    public function addSettingsArray(array $settingsArray): self
    {
        $this->containerBuilder->addDefinitions(['settings' => $settingsArray]);

        return $this;
    }
}
