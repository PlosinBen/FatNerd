<?php

function parseCameCase(string $studlyCapsWords, string $separator = '_'): string
{
    $studlyCapsWords = $separator . str_replace($separator, ' ', strtolower($studlyCapsWords));

    return ltrim(str_replace(' ', '', ucwords($studlyCapsWords)), $separator);
}

function parseSnakeCase(string $camelCaps, string $separator = '_'): string
{
    return strtolower(preg_replace('/([a-z])([A-Z])/', '$1' . $separator . '$2', $camelCaps));
}
