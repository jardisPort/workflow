<?php

declare(strict_types=1);

namespace JardisPort\Workflow;

/**
 * Interface for fluent workflow configuration builders.
 *
 * Provides a fluent API for creating WorkflowConfig instances with
 * chained method calls for defining nodes and their transitions.
 *
 * Usage:
 *   $config = (new WorkflowBuilder())
 *       ->node(PaymentHandler::class)
 *           ->onSuccess(ShippingHandler::class)
 *           ->onFail(NotifyHandler::class)
 *       ->node(ShippingHandler::class)
 *           ->onSuccess(ConfirmHandler::class)
 *       ->build();
 */
interface WorkflowBuilderInterface
{
    /**
     * Starts configuration of a new workflow node.
     *
     * @param string $handlerClass The fully qualified class name of the handler
     * @return WorkflowNodeBuilderInterface Node builder for configuring transitions
     */
    public function node(string $handlerClass): WorkflowNodeBuilderInterface;

    /**
     * Adds a transition to the current node.
     *
     * Called internally by WorkflowNodeBuilder to register transitions.
     *
     * @param string $name The transition name (e.g., 'onSuccess', 'onRetry')
     * @param string $handlerClass The target handler class for this transition
     */
    public function addTransition(string $name, string $handlerClass): void;

    /**
     * Builds and returns the workflow configuration.
     *
     * @return WorkflowConfigInterface The completed workflow configuration
     */
    public function build(): WorkflowConfigInterface;
}
