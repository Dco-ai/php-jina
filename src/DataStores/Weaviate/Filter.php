<?php

namespace DcoAi\PhpJina\DataStores\Weaviate;

class Filter extends WeaviateConnection
{
    private bool $andOrUsed = false;
    private array $filterDataTypes;

    public function __construct($conf) {
        parent::__construct($conf);
        $this->filterDataTypes = $this->setFilterDataTypes();
    }

    /**
     * Determines whether a given class should be excluded from the filter data types.
     *
     * @param string $class The name of the class to check.
     * @return bool True if the class should be excluded, false otherwise.
     */
    private function isExcludedClass(string $class): bool
    {
        $excludedSuffixes = [
            'subindexrc',
            'Meta',
            'subindexrcMeta'
        ];
        foreach ($excludedSuffixes as $suffix) {
            if (str_ends_with($class, $suffix)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Extracts the filter data types from a set of Weaviate classes.
     *
     * @param array $classes The set of classes to extract filter data types from.
     * @return array An associative array of property names and their corresponding data types.
     */
    private function extractFilterDataTypes(array $classes): array
    {
        $filterDataTypes = [];
        foreach ($classes as $class) {
            if ($this->isExcludedClass($class->class)) {
                continue;
            }
            foreach ($class->properties as $property) {
                if (str_starts_with($property->name, "_")) {
                    continue;
                }
                $filterDataTypes[$property->name] = "value" . ucfirst(end($property->dataType));
            }
        }
        return $filterDataTypes;
    }

    /**
     * Gets the Weaviate schema and returns the filter data types.
     *
     * @return array An associative array of property names and their corresponding data types.
     */
    private function setFilterDataTypes(): array
    {
        $classes = $this->retrieveWeaviateSchema()->classes;
        return $this->extractFilterDataTypes($classes);
    }

    private array $query = [];

    /**
     * Adds a new "And" operator to the query.
     *
     * @return $this
     */
    public function and(): static
    {
        $this->andOrUsed = true;
        $this->query[] = [
            'operator' => 'And',
            'operands' => [],
        ];
        return $this;
    }

    /**
     * Ends the last "And" operator in the query.
     *
     * @return $this
     */
    public function endAnd(): static
    {
        $this->andOrUsed = false;
        return $this;
    }

    /**
     * Adds a new "Or" operator to the query.
     *
     * @return $this
     */
    public function or(): static
    {
        $this->andOrUsed = true;
        $this->query[] = [
            'operator' => 'Or',
            'operands' => [],
        ];
        return $this;
    }


    /**
     * Ends the last "Or" operator in the query.
     *
     * @return $this
     */
    public function endOr(): static
    {
        $this->andOrUsed = false;
        return $this;
    }

    /**
     * Set an operand in the Jina query.
     *
     * @param string $operator The operator to use in the operand.
     * @param array|string $path The path to the property in the object to be filtered. Might a string or an array of strings.
     * @param mixed $value The value to compare the property against.
     *
     * @return $this Returns the current instance of the Jina object for method chaining.
     */
    private function setOperand(string $operator, array|string $path, mixed $value): static
    {
        if (!is_string($path)) {
            $valueType = $this->filterDataTypes[end($path)] ?? 'valueString';
        } else {
            $valueType = $this->filterDataTypes[$path] ?? 'valueString';
            $path = [$path];
        }

        $operand =  [
            'path' => $path,
            'operator' => $operator,
            $valueType => $value,
        ];
        if ($this->andOrUsed) {
            $this->query[count($this->query)-1]['operands'][] = $operand;
        } else {
            $this->query[] = $operand;
        }
        return $this;
    }

    /**
     * Sets the "Not" operator and corresponding operands in the query
     *
     * @param array|string $path
     * @param mixed $value
     * @return $this
     */
    public function not(array|string $path, mixed $value): static
    {
        return $this->setOperand("Not", $path, $value);
    }

    /**
     * Sets the "Equal" operator and corresponding operands in the query
     *
     * @param array|string $path
     * @param mixed $value
     * @return $this
     */
    public function equal(array|string $path, mixed $value): static
    {
        return $this->setOperand("Equal", $path, $value);
    }

    /**
     * Sets the "NotEqual" operator and corresponding operands in the query
     *
     * @param array|string $path
     * @param mixed $value
     * @return $this
     */
    public function notEqual(array|string $path, mixed $value): static
    {
        return $this->setOperand("NotEqual", $path, $value);
    }

    /**
     * Sets the "GreaterThan" operator and corresponding operands in the query
     *
     * @param array|string $path
     * @param mixed $value
     * @return $this
     */
    public function greaterThan(array|string $path, mixed $value): static
    {
        return $this->setOperand("GreaterThan", $path, $value);
    }

    /**
     * Sets the "GreaterThanEqual" operator and corresponding operands in the query
     *
     * @param array|string $path
     * @param mixed $value
     * @return $this
     */
    public function greaterThanEqual(array|string $path, mixed $value): static
    {
        return $this->setOperand("GreaterThanEqual", $path, $value);
    }

    /**
     * Sets the "LessThan" operator and corresponding operands in the query
     *
     * @param array|string $path
     * @param mixed $value
     * @return $this
     */
    public function lessThan(array|string $path, mixed $value): static
    {
        return $this->setOperand("LessThan", $path, $value);
    }

    /**
     * Sets the "LessThanEqual" operator and corresponding operands in the query
     *
     * @param array|string $path
     * @param mixed $value
     * @return $this
     */
    public function lessThanEqual(array|string $path, mixed $value): static
    {
        return $this->setOperand("LessThanEqual", $path, $value);
    }

    /**
     * Sets the "Like" operator and corresponding operands in the query
     *
     * @param array|string $path
     * @param mixed $value
     * @return $this
     */
    public function like(array|string $path, mixed $value): static
    {
        return $this->setOperand("Like", $path, $value);
    }

    /**
     * Sets the "WithinGeoRange" operator and corresponding operands in the query
     *
     * @param array|string $path
     * @param mixed $value
     * @return $this
     */
    public function withinGeoRange(array|string $path, mixed $value): static
    {
        return $this->setOperand("WithinGeoRange", $path, $value);
    }

    /**
     * Sets the "IsNull" operator and corresponding operands in the query
     *
     * @param array|string $path
     * @param mixed $value
     * @return $this
     */
    public function isNull(array|string $path, mixed $value): static
    {
        return $this->setOperand("IsNull", $path, $value);
    }

    /**
     * Returns the generated query
     *
     * @return array
     */
    public function createFilter(): array
    {
        return $this->query;
    }
}