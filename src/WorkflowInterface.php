<?php

declare(strict_types=1);

namespace JardisPort\Workflow;

use Exception;

/**
 * Workflow interface for orchestrating multi-step processes.
 *
 * Executes a series of configured workflow nodes with conditional branching.
 * Handler instantiation is an implementation detail (factory, container, or direct).
 */
interface WorkflowInterface
{
    /**
     * Executes the workflow with the given configuration and parameters.
     *
     * @param WorkflowConfigInterface $workflowConfig The workflow configuration with nodes
     * @param mixed ...$parameters Parameters passed to each handler (e.g., request, context, data)
     * @return array<string, mixed> Results from the workflow execution
     * @throws Exception If workflow execution fails
     */
    public function __invoke(
        WorkflowConfigInterface $workflowConfig,
        mixed ...$parameters
    ): array;
}
