# JardisPort Workflow

![Build Status](https://github.com/jardisport/workflow/actions/workflows/ci.yml/badge.svg)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.2-blue.svg)](https://www.php.net/)
[![PHPStan Level](https://img.shields.io/badge/PHPStan-Level%208-success.svg)](phpstan.neon)
[![PSR-4](https://img.shields.io/badge/autoload-PSR--4-blue.svg)](https://www.php-fig.org/psr/psr-4/)
[![PSR-12](https://img.shields.io/badge/code%20style-PSR--12-orange.svg)](phpcs.xml)

**Interface library** for workflow orchestration in PHP 8.2+ applications. Provides strictly typed interfaces for multi-step process execution with configurable transitions and conditional branching.

**Zero dependencies** - can be used standalone or integrated with any framework/container.

## Installation

```bash
composer require jardisport/workflow
```

## Requirements

- PHP >= 8.2

## Architecture Overview

This library provides contracts for implementing workflow patterns with a focus on type safety and flexible transition handling.

### Core Interfaces

#### WorkflowInterface
Executes workflows with configured nodes:
```php
public function __invoke(
    WorkflowConfigInterface $workflowConfig,
    mixed ...$parameters  // Request, context, data - passed to each handler
): array;
```

Handler instantiation is an implementation detail - use a factory, container, or direct instantiation.

#### WorkflowConfigInterface
Configuration container for workflow nodes and their transitions:
- `addNode(string $handlerClass, array $transitions = [])` - Add a workflow node
- `getNodes()` - Get all configured nodes with transitions
- `getTransitions(string $handlerClass)` - Get transitions for a specific handler

#### WorkflowBuilderInterface
Fluent API for building workflow configurations:
```php
$config = (new WorkflowBuilder())
    ->node(PaymentHandler::class)
        ->onSuccess(ShippingHandler::class)
        ->onFail(NotifyHandler::class)
    ->node(ShippingHandler::class)
        ->onSuccess(ConfirmHandler::class)
    ->build();
```

#### WorkflowNodeBuilderInterface
Node-level transition configuration with support for:
- `onSuccess()` - Handler for successful completion
- `onFail()` - Handler for failure
- `onError()` - Handler for errors
- `onTimeout()` - Handler for timeouts
- `onRetry()` - Handler for retry logic
- `onSkip()` - Handler for skip conditions
- `onPending()` - Handler for pending state
- `onCancel()` - Handler for cancellation

#### WorkflowResultInterface
Explicit control over workflow transitions:
- Status constants: `STATUS_SUCCESS`, `STATUS_FAIL`
- Transition constants: `ON_SUCCESS`, `ON_FAIL`, `ON_ERROR`, `ON_TIMEOUT`, `ON_RETRY`, `ON_SKIP`, `ON_PENDING`, `ON_CANCEL`
- Methods: `getStatus()`, `getData()`, `getTransition()`, `isSuccess()`, `isFail()`, `hasExplicitTransition()`

### Typical Workflow Flow

```
$workflow($config, $request, $context, ...)
                  ↓
            Node 1: PaymentHandler($request, $context, ...) → WorkflowResult
                  ↓ (onSuccess)
            Node 2: ShippingHandler($request, $context, ...) → WorkflowResult
                  ↓ (onSuccess)
            Node 3: ConfirmHandler($request, $context, ...) → WorkflowResult
                  ↓
            Results Array
```

### Usage Examples

**Standalone (no dependencies):**
```php
$workflow = new Workflow();  // Instantiates handlers directly
$result = $workflow($config, $request);
```

**With Factory/Container:**
```php
$workflow = new Workflow(fn($class) => $container->get($class));
$result = $workflow($config, $request);
```

**With Domain Context:**
```php
$workflow = new Workflow(fn($class) => $boundedContext->handle($class));
$result = $workflow($config, $domainRequest);
```

## Development

All development commands run inside Docker containers for consistent environments.

### Available Commands

```bash
make install     # Install Composer dependencies
make update      # Update Composer dependencies
make autoload    # Regenerate autoload files
make phpstan     # Run PHPStan static analysis (Level 8)
make phpcs       # Run PHP_CodeSniffer (PSR-12)
make shell       # Access Docker container shell
make help        # Show all available commands
```

### Code Quality Standards

- **PHPStan Level 8** - Maximum strictness with full type coverage
- **PSR-12** coding standard with 120-character line limit
- **Strict types** required in all PHP files (`declare(strict_types=1)`)
- **Pre-commit hooks** validate branch naming and run phpcs on staged files

### Branch Naming Convention

Branches must follow this pattern:
```
(feature|fix|hotfix)/[1-7 digits]_[alphanumeric-_]+
```

Example: `feature/123_add-workflow-timeout`

## License

MIT License - see [LICENSE](LICENSE) file for details.

## Support

- **Issues:** [GitHub Issues](https://github.com/JardisPort/workflow/issues)
- **Email:** jardisDev@headgent.com

## Authors

Jardis Development
