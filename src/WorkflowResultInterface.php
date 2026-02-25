<?php

declare(strict_types=1);

namespace JardisPort\Workflow;

/**
 * Interface for workflow handler result objects.
 *
 * Provides explicit control over workflow transitions by requiring handlers
 * to return a WorkflowResult instead of arbitrary values. This eliminates
 * ambiguity in truthy/falsy evaluation and enables named transitions.
 *
 * Implementations should support:
 * - Status-based routing (success/fail)
 * - Named transitions (retry, pending, cancel, etc.)
 * - Data payload from handler execution
 */
interface WorkflowResultInterface
{
    // Status constants for basic success/fail routing
    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAIL = 'fail';

    // Standard transition constants for named routing
    public const ON_SUCCESS = 'onSuccess';
    public const ON_FAIL = 'onFail';
    public const ON_ERROR = 'onError';
    public const ON_TIMEOUT = 'onTimeout';
    public const ON_RETRY = 'onRetry';
    public const ON_SKIP = 'onSkip';
    public const ON_PENDING = 'onPending';
    public const ON_CANCEL = 'onCancel';

    /**
     * Returns the result status.
     *
     * Can be either a status constant (STATUS_SUCCESS, STATUS_FAIL)
     * or a transition constant (ON_RETRY, ON_PENDING, etc.).
     */
    public function getStatus(): string;

    /**
     * Returns the optional data payload from the handler.
     */
    public function getData(): mixed;

    /**
     * Returns the explicit transition name, or null if status-based routing should be used.
     *
     * When a transition constant (ON_*) is used as status, this returns that constant.
     * When a status constant (STATUS_*) is used, this returns null.
     */
    public function getTransition(): ?string;

    /**
     * Checks if the result indicates success.
     *
     * Only returns true if status is explicitly STATUS_SUCCESS.
     */
    public function isSuccess(): bool;

    /**
     * Checks if the result indicates failure.
     *
     * Only returns true if status is explicitly STATUS_FAIL.
     */
    public function isFail(): bool;

    /**
     * Checks if this result has an explicit named transition.
     *
     * Returns true when a transition constant (ON_*) was used,
     * which should take precedence over status-based routing.
     */
    public function hasExplicitTransition(): bool;
}
