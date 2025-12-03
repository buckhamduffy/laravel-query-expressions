<?php

namespace BuckhamDuffy\Expressions\Function\Json;

use Illuminate\Database\Grammar;
use Illuminate\Contracts\Database\Query\Expression;
use BuckhamDuffy\Expressions\Concerns\IdentifiesDriver;
use BuckhamDuffy\Expressions\Concerns\StringizeExpression;

class JsonExtract implements Expression
{
    use IdentifiesDriver;
    use StringizeExpression;

    /**
     * @param string $key - eg. $.key.name or $.key[0].name
     */
    public function __construct(
        private Expression|string $json,
        private string $key,
        private bool $unquote = true
    )
    {
    }

    public function getValue(Grammar $grammar)
    {
        $json = $this->stringize($grammar, $this->json);
        $path = $this->key;

        $driver = $this->identify($grammar);

        if (\in_array($driver, ['mariadb', 'mysql'])) {
            $expr = \sprintf("JSON_EXTRACT(%s, '%s')", $json, $path);

            return $this->unquote ? \sprintf('JSON_UNQUOTE(%s)', $expr) : $expr;
        }

        if ($driver === 'sqlsrv') {
            // Choose VALUE (scalar) vs QUERY (object/array). If you can't know, default to VALUE when unquote=true.
            return $this->unquote ? \sprintf("JSON_VALUE(%s, '%s')", $json, $path) : \sprintf("JSON_QUERY(%s, '%s')", $json, $path);
        }

        if ($driver === 'sqlite') {
            return \sprintf("json_extract(%s, '%s')", $json, $path);
        }

        /**
         * Convert '$.a.b[0]' -> '{a,b,0}' for #>/#>>.
         * @var list<string> $parts
         */
        $parts = preg_split('~(?<=])\.|\.~', ltrim($path, '$.'), -1, \PREG_SPLIT_NO_EMPTY);
        // strip [n] to n
        $parts = array_map(fn ($p) => preg_replace('~\[(\d+)\]~', '$1', $p), $parts);
        $pgPath = '{' . implode(',', array_map(fn ($p) => $p, $parts)) . '}';

        return $this->unquote ? \sprintf("%s #>> '%s'", $json, $pgPath) : \sprintf("%s #> '%s'", $json, $pgPath);
    }
}
