<?php

if (!function_exists('graphql_encode')) {
    function graphql_encode(array $data): string
    {
        foreach ($data as $key => &$value) {
            $value = is_array($value)
                ? graphql_encode($value)
                : '"' . $value . '"';

            $value = implode(': ', [$key, $value]);
        }

        return '{' . implode(',', $data) . '}';
    }
}
