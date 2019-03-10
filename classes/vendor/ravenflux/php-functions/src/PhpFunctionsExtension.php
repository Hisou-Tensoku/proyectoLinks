<?php

namespace RavenFlux\Twig;

use Twig_Extension;
use Twig_SimpleFilter;
use Twig_SimpleFunction;
use function array_keys;
use function array_map;
use function current;
use function is_array;
use function is_numeric;
use function key;

class PhpFunctionsExtension extends Twig_Extension
{
    /**
     * @var array
     */
    private $functions;

    /**
     * @var array
     */
    private $filters;

    /**
     * @param null|array $functions
     * @param null|array $filters
     */
    public function __construct($functions = null, $filters = null)
    {
        $this->functions = $functions ?: [];
        $this->filters = $filters ?: [];
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        $callbacks = $this->getCallbacks($this->filters);

        return array_map(function ($function, $callback) {
            return new Twig_SimpleFilter($function, $callback);
        }, array_keys($callbacks), $callbacks);
    }

    /**
     * @param array $callables
     *
     * @return array
     */
    private function getCallbacks(array $callables = []): array
    {
        $result = [];
        foreach ($callables as $function) {
            if (is_array($function) && !is_numeric(key($function))) {
                $callback = current($function);
                $function = key($function);
            } else {
                $callback = $function;
            }
            $result[$function] = $callback;
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getFunctions(): array
    {
        $callbacks = $this->getCallbacks($this->functions);

        return array_map(function ($function, $callback) {
            return new Twig_SimpleFunction($function, $callback);
        }, array_keys($callbacks), $callbacks);
    }
}
