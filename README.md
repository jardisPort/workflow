# Jardis Workflow Port

![Build Status](https://github.com/jardisPort/workflow/actions/workflows/ci.yml/badge.svg)
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D8.2-777BB4.svg)](https://www.php.net/)
[![PHPStan Level](https://img.shields.io/badge/PHPStan-Level%208-brightgreen.svg)](phpstan.neon)
[![PSR-12](https://img.shields.io/badge/Code%20Style-PSR--12-blue.svg)](phpcs.xml)

> Part of the **[Jardis Business Platform](https://jardis.io)** — Enterprise-grade PHP components for Domain-Driven Design

Workflow orchestration contracts with status-based transitions and a fluent builder. Five interfaces covering workflow execution, configuration, result handling, and graph construction. Define multi-step processes without coupling to a specific workflow engine.

---

## Interfaces

All interfaces live in the `JardisPort\Workflow` namespace.

### WorkflowInterface

Executes a configured workflow, passing parameters to each handler node.

| Method | Signature | Description |
|--------|-----------|-------------|
| `__invoke` | `__invoke(WorkflowConfigInterface $config, mixed ...$parameters): array` | Runs the workflow and returns collected results from all executed nodes |

### WorkflowConfigInterface

Configuration container for workflow nodes and their transitions.

| Method | Signature | Description |
|--------|-----------|-------------|
| `addNode` | `addNode(string $handlerClass, array $transitions = []): self` | Adds a handler node with its optional transition map |
| `getNodes` | `getNodes(): array` | Returns all configured nodes |
| `getTransitions` | `getTransitions(string $handlerClass): ?array` | Returns the transition map for a specific handler |

### WorkflowResultInterface

Returned by each handler node to control the next transition. Provides 2 status constants (`STATUS_SUCCESS`, `STATUS_FAIL`) and 8 transition constants (`ON_SUCCESS`, `ON_FAIL`, `ON_ERROR`, `ON_TIMEOUT`, `ON_RETRY`, `ON_SKIP`, `ON_PENDING`, `ON_CANCEL`).

| Method | Signature | Description |
|--------|-----------|-------------|
| `getStatus` | `getStatus(): string` | Returns the node's status or transition constant |
| `getData` | `getData(): mixed` | Returns the node's output data |
| `getTransition` | `getTransition(): ?string` | Returns the explicit transition constant, or null if status-based routing applies |
| `isSuccess` | `isSuccess(): bool` | Returns true if status is `STATUS_SUCCESS` |
| `isFail` | `isFail(): bool` | Returns true if status is `STATUS_FAIL` |
| `hasExplicitTransition` | `hasExplicitTransition(): bool` | Returns true if the node sets an explicit named transition |

### WorkflowBuilderInterface

Fluent API for composing a `WorkflowConfigInterface`.

| Method | Signature | Description |
|--------|-----------|-------------|
| `node` | `node(string $handlerClass): WorkflowNodeBuilderInterface` | Starts configuration for a handler node |
| `addTransition` | `addTransition(string $name, string $handlerClass): void` | Registers a named transition on the current node (called internally by `WorkflowNodeBuilderInterface`) |
| `build` | `build(): WorkflowConfigInterface` | Returns the assembled workflow configuration |

### WorkflowNodeBuilderInterface

Configures outgoing transitions for a single node.

| Method | Signature | Description |
|--------|-----------|-------------|
| `onSuccess` | `onSuccess(string $handlerClass): self` | Sets the handler for successful completion |
| `onFail` | `onFail(string $handlerClass): self` | Sets the handler for failure |
| `onError` | `onError(string $handlerClass): self` | Sets the handler for errors |
| `onTimeout` | `onTimeout(string $handlerClass): self` | Sets the handler for timeout |
| `onRetry` | `onRetry(string $handlerClass): self` | Sets the handler for retry |
| `onSkip` | `onSkip(string $handlerClass): self` | Sets the handler for skip |
| `onPending` | `onPending(string $handlerClass): self` | Sets the handler for pending state |
| `onCancel` | `onCancel(string $handlerClass): self` | Sets the handler for cancellation |
| `node` | `node(string $handlerClass): WorkflowNodeBuilderInterface` | Starts configuration for the next node in the workflow |
| `build` | `build(): WorkflowConfigInterface` | Returns the assembled workflow configuration |

---

## Installation

```bash
composer require jardisport/workflow
```

## Usage

```php
use JardisPort\Workflow\WorkflowInterface;
use JardisPort\Workflow\WorkflowBuilderInterface;

class CheckoutService
{
    public function __construct(
        private readonly WorkflowInterface $workflow,
        private readonly WorkflowBuilderInterface $builder,
    ) {}

    public function process(CheckoutRequest $request): array
    {
        $config = $this->builder
            ->node(PaymentHandler::class)
                ->onSuccess(ShippingHandler::class)
                ->onFail(CancelHandler::class)
            ->node(ShippingHandler::class)
                ->onSuccess(ConfirmHandler::class)
            ->build();

        return ($this->workflow)($config, $request);
    }
}
```

## Implemented by

- **[jardissupport/workflow](https://github.com/jardisSupport/workflow)** — Multi-step workflow orchestration with status-driven transitions, timeout handling, and pluggable handler instantiation

## Documentation

Full documentation, guides, and API reference:

**[jardis.io/docs/port/workflow](https://jardis.io/docs/port/workflow)**

## License

This package is licensed under the [MIT License](LICENSE).

---

**[Jardis](https://jardis.io)** · [Documentation](https://jardis.io/docs) · [Headgent](https://headgent.com)
