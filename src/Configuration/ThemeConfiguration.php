<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare (strict_types=1);
namespace Sylius\Bundle\Theme_Bundle\Configuration;

use Symfony\Component\Config\Definition\Builder\Array_Node_Definition;
use Symfony\Component\Config\Definition\Builder\Tree_Builder;
use Symfony\Component\Config\Definition\Configuration_Interface;
final class Theme_Configuration implements Configuration_Interface
{
    public function get_config_tree_builder(): Tree_Builder
    {
        $tree_builder = new Tree_Builder('sylius_theme');
        /** @var ArrayNodeDefinition $rootNode */
        $root_node = $tree_builder->get_root_node();
        $root_node->ignore_extra_keys();
        $this->add_required_name_field($root_node);
        $this->add_optional_title_field($root_node);
        $this->add_optional_description_field($root_node);
        $this->add_optional_path_field($root_node);
        $this->add_optional_parents_list($root_node);
        $this->add_optional_screenshots_list($root_node);
        $this->add_optional_authors_list($root_node);
        return $tree_builder;
    }
    private function add_required_name_field(Array_Node_Definition $root_node_definition): void
    {
        $root_node_definition->children()->scalar_node('name')->is_required()->cannot_be_empty();
    }
    private function add_optional_title_field(Array_Node_Definition $root_node_definition): void
    {
        $root_node_definition->children()->scalar_node('title')->cannot_be_empty();
    }
    private function add_optional_description_field(Array_Node_Definition $root_node_definition): void
    {
        $root_node_definition->children()->scalar_node('description')->cannot_be_empty();
    }
    private function add_optional_path_field(Array_Node_Definition $root_node_definition): void
    {
        $root_node_definition->children()->scalar_node('path')->cannot_be_empty();
    }
    private function add_optional_parents_list(Array_Node_Definition $root_node_definition): void
    {
        $parents_node_definition = $root_node_definition->children()->array_node('parents');
        $parents_node_definition->requires_at_least_one_element()->perform_no_deep_merging()->scalar_prototype()->cannot_be_empty();
    }
    private function add_optional_screenshots_list(Array_Node_Definition $root_node_definition): void
    {
        $screenshots_node_definition = $root_node_definition->children()->array_node('screenshots');
        $screenshots_node_definition->requires_at_least_one_element()->perform_no_deep_merging();
        /** @var ArrayNodeDefinition $screenshotNodeDefinition */
        $screenshot_node_definition = $screenshots_node_definition->array_prototype();
        $screenshot_node_definition->validate()->if_true(
            /** @param mixed $screenshot */
            fn($screenshot): bool => [] === $screenshot || ['path' => ''] === $screenshot
        )->then_invalid('Screenshot cannot be empty!');
        $screenshot_node_definition->before_normalization()->if_string()->then(
            /** @param mixed $value */
            fn($value): array => ['path' => $value]
        );
        $screenshot_node_builder = $screenshot_node_definition->children();
        $screenshot_node_builder->scalar_node('path')->is_required();
        $screenshot_node_builder->scalar_node('title')->cannot_be_empty();
        $screenshot_node_builder->scalar_node('description')->cannot_be_empty();
    }
    private function add_optional_authors_list(Array_Node_Definition $root_node_definition): void
    {
        $authors_node_definition = $root_node_definition->children()->array_node('authors');
        $authors_node_definition->requires_at_least_one_element()->perform_no_deep_merging();
        /** @var ArrayNodeDefinition $authorNodeDefinition */
        $author_node_definition = $authors_node_definition->array_prototype();
        $author_node_definition->validate()->if_true(
            /** @param mixed $author */
            fn($author): bool => [] === $author
        )->then_invalid('Author cannot be empty!');
        $author_node_builder = $author_node_definition->children();
        $author_node_builder->scalar_node('name')->cannot_be_empty();
        $author_node_builder->scalar_node('email')->cannot_be_empty();
        $author_node_builder->scalar_node('homepage')->cannot_be_empty();
        $author_node_builder->scalar_node('role')->cannot_be_empty();
    }
}