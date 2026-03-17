<?php

declare(strict_types=1);

namespace JardisPort\Workflow;

/**
 * Interface for configuring transitions on a workflow node.
 *
 * Provides fluent methods for defining all possible transitions
 * from a workflow node (success, fail, retry, pending, etc.).
 */
interface WorkflowNodeBuilderInterface
{
    /**
     * Sets the handler to execute on successful completion.
     *
     * @param string $handlerClass The fully qualified class name of the handler
     * @return self Returns instance for method chaining
     */
    public function onSuccess(string $handlerClass): self;

    /**
     * Sets the handler to execute on failure.
     *
     * @param string $handlerClass The fully qualified class name of the handler
     * @return self Returns instance for method chaining
     */
    public function onFail(string $handlerClass): self;

    /**
     * Sets the handler to execute on error.
     *
     * @param string $handlerClass The fully qualified class name of the handler
     * @return self Returns instance for method chaining
     */
    public function onError(string $handlerClass): self;

    /**
     * Sets the handler to execute on timeout.
     *
     * @param string $handlerClass The fully qualified class name of the handler
     * @return self Returns instance for method chaining
     */
    public function onTimeout(string $handlerClass): self;

    /**
     * Sets the handler to execute on retry.
     *
     * @param string $handlerClass The fully qualified class name of the handler
     * @return self Returns instance for method chaining
     */
    public function onRetry(string $handlerClass): self;

    /**
     * Sets the handler to execute on skip.
     *
     * @param string $handlerClass The fully qualified class name of the handler
     * @return self Returns instance for method chaining
     */
    public function onSkip(string $handlerClass): self;

    /**
     * Sets the handler to execute when pending.
     *
     * @param string $handlerClass The fully qualified class name of the handler
     * @return self Returns instance for method chaining
     */
    public function onPending(string $handlerClass): self;

    /**
     * Sets the handler to execute on cancel.
     *
     * @param string $handlerClass The fully qualified class name of the handler
     * @return self Returns instance for method chaining
     */
    public function onCancel(string $handlerClass): self;

    /**
     * Starts configuration of a new workflow node.
     *
     * Allows chaining to define the next node in the workflow.
     *
     * @param string $handlerClass The fully qualified class name of the handler
     * @return WorkflowNodeBuilderInterface Returns a node builder for the new node
     */
    public function node(string $handlerClass): WorkflowNodeBuilderInterface;

    /**
     * Builds and returns the workflow configuration.
     *
     * @return WorkflowConfigInterface The completed workflow configuration
     */
    public function build(): WorkflowConfigInterface;
}
