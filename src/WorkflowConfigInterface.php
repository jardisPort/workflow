<?php

declare(strict_types=1);

namespace JardisPort\Workflow;

/**
 * Workflow configuration interface for defining workflow nodes.
 *
 * Provides methods for building workflow configurations with nodes
 * that support conditional branching based on handler results.
 */
interface WorkflowConfigInterface
{
    /**
     * Adds a node to the workflow configuration.
     *
     * @param string $handlerClass The handler class to execute
     * @param array<string, string|null> $transitions Map of transition names to handler classes
     */
    public function addNode(
        string $handlerClass,
        array $transitions = []
    ): self;

    /**
     * Returns all configured nodes with their transitions.
     *
     * @return array<int, array{handler: string, transitions: array<string, string|null>}>
     */
    public function getNodes(): array;

    /**
     * Returns the transitions array for a specific handler.
     *
     * @return array<string, string|null>|null Transitions or null if handler not found
     */
    public function getTransitions(string $handlerClass): ?array;
}
