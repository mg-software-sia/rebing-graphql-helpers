<?php

namespace MgSoftware\GraphQLHelpers;

class CleanRelationSelectedFields
{
    /**
     * Cleans out selects from nested relations
     * @param array $relations
     * @return array
     */
    public static function clean(array $relations): array
    {
        return array_map(function ($relation) {
            $reflection = new \ReflectionFunction($relation);
            $variables = $reflection->getStaticVariables();
            return function ($query) use ($variables) {
                $customQuery = $variables['customQuery'];
                if ($customQuery) {
                    $query = $customQuery($variables['requestedFields']['args'], $query, $variables['ctx']);
                }
                $cleaned = static::clean($variables['with']);
                $query->with($cleaned);
            };
        }, $relations);
    }
}
