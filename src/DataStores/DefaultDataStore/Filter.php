<?php

namespace DcoAi\PhpJina\DataStores\DefaultDataStore;

class Filter extends DefaultConnection
{
    private array $query = [];

    private bool $andUsed = false;
    private array $andQuery = [];
    /**
     * Adds a new "$and" operator to the query.
     *
     * @return $this
     */
    public function and()
    {
        $this->andUsed = true;
        $this->andQuery['$and'] = [];
        return $this;
    }

    /**
     * Ends the last "$and" operator in the query.
     *
     * @return $this
     */
    public function endAnd()
    {
        $this->andUsed = false;
        $this->query[] = $this->andQuery;
        $this->andQuery = [];
        return $this;
    }

    private bool $orUsed = false;
    private array $orQuery = [];
    /**
     * Adds a new "$or" operator to the query.
     *
     * @return $this
     */
    public function or()
    {
        $this->orUsed = true;
        $this->orQuery['$or'] = [];
        return $this;
    }


    /**
     * Ends the last "$or" operator in the query.
     *
     * @return $this
     */
    public function endOr()
    {
        $this->orUsed = false;
        $this->query[] = $this->orQuery;
        $this->orQuery = [];
        return $this;
    }

    private bool $notUsed = false;
    private array $notQuery = [];
    /**
     * Adds a new "$not" operator to the query.
     *
     * @return $this
     */
    public function not()
    {
        $this->notUsed = true;
        $this->notQuery['$not'] = [];
        return $this;
    }

    /**
     * Ends the last "$not" operator in the query.
     *
     * @return $this
     */
    public function endNot()
    {
        $this->notUsed = false;
        $this->query[] = $this->notQuery;
        $this->notQuery = [];
        return $this;
    }

    /**
     * Set a filter operand on the current query for the given field.
     *
     * @param string $operator The operator to use for the filter.
     * @param string $field The field to filter on.
     * @param mixed $value The value to compare the field against.
     * @return static
     */
    private function setOperand(string $operator, string $field, mixed $value)
    {
        $query = [$field => [$operator => $value]];
        if ($this->andUsed) {
            $this->andQuery['$and'][] = $query;
        } elseif ($this->orUsed) {
            $this->orQuery['$or'][] = $query;
        } elseif ($this->notUsed) {
            $this->notQuery['$not'][] = $query;
        } else   {
            $this->query[] = $query;
        }
        return $this;
    }

    /**
     * Sets the $eq operator for the specified field.
     *
     * @param string $field The name of the field to compare.
     * @param mixed $value The value to compare against.
     *
     * @return static Returns an instance of the class for method chaining.
     */
    public function equal(string $field, mixed $value)
    {
        return $this->setOperand('$eq', $field, $value);
    }

    /**
     * Sets the $ne operator for the specified field.
     *
     * @param string $field The name of the field to compare.
     * @param mixed $value The value to compare against.
     *
     * @return static Returns an instance of the class for method chaining.
     */
    public function notEqual(string $field, mixed $value)
    {
        return $this->setOperand('$ne', $field, $value);
    }

    /**
     * Sets the $gt operator for the specified field.
     *
     * @param string $field The name of the field to compare.
     * @param int|float $value The value to compare against.
     *
     * @return static Returns an instance of the class for method chaining.
     */
    public function greaterThan(string $field, int|float $value)
    {
        return $this->setOperand('$gt', $field, $value);
    }

    /**
     * Sets the $gte operator for the specified field.
     *
     * @param string $field The name of the field to compare.
     * @param int|float $value The value to compare against.
     *
     * @return static Returns an instance of the class for method chaining.
     */
    public function greaterThanEqual(string $field, int|float $value)
    {
        return $this->setOperand('$gte', $field, $value);
    }

    /**
     * Sets the $lt operator for the specified field.
     *
     * @param string $field The name of the field to compare.
     * @param int|float $value The value to compare against.
     *
     * @return static Returns an instance of the class for method chaining.
     */
    public function lessThan(string $field, int|float $value)
    {
        return $this->setOperand('$lt', $field, $value);
    }

    /**
     * Sets the $lte operator for the specified field.
     *
     * @param string $field The name of the field to compare.
     * @param int|float $value The value to compare against.
     *
     * @return static Returns an instance of the class for method chaining.
     */
    public function lessThanEqual(string $field, int|float $value)
    {
        return $this->setOperand('$lte', $field, $value);
    }

    /**
     * Sets the $in operator for the specified field.
     *
     * @param string $field The name of the field to compare.
     * @param array $value The array of values to check against.
     *
     * @return static Returns an instance of the class for method chaining.
     */
    public function in(string $field, array $value)
    {
        return $this->setOperand('$in', $field, $value);
    }

    /**
     * Sets the $nin operator for the specified field.
     *
     * @param string $field The name of the field to compare.
     * @param array $value The array of values to check against.
     *
     * @return static Returns an instance of the class for method chaining.
     */
    public function notIn(string $field, array $value)
    {
        return $this->setOperand('$nin', $field, $value);
    }

    /**
     * Add a $regex operator to the filter.
     *
     * @param string $field The field to filter.
     * @param string $value The value to filter with.
     * @return static
     */
    public function regex(string $field, string $value)
    {
        return $this->setOperand('$regex', $field, $value);
    }

    /**
     * Sets a "$size" operator to match array/dict field that have the specified size.
     *
     * @param string $field
     * @param int $value
     * @return static
     */
    public function size(string $field, int $value)
    {
        return $this->setOperand('$size', $field, $value);
    }

    /**
     * Sets an "$exists" operator to match documents that have the specified field.
     *
     * Predefined fields having a default value (for example empty string, or 0) are considered as not existing;
     * if the expression specifies a field x in tags (tags__x), then the operator tests that x is not None.
     *
     * @param string $field
     * @param mixed $value
     * @return static
     */
    public function exists(string $field, mixed $value)
    {
        return $this->setOperand('$exists', $field, $value);
    }


    /**
     * Returns the generated query
     *
     * @return array
     */
    public function createFilter()
    {
        return $this->query;
    }
}