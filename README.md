# PHP-compare

Simple tool for array comparison in PHP.

## Properties and methods:

### Compare::setBase (array $array)
Sets base (original) array.

### Compare::setTest (array $array)
Sets tested (different) array.

### Compare::getBase ()
Returns base (original) array.

### Compare::getTest ()
Returns tested (different) array.

### Compare::compare ([array $baseArray[, array $testArray]])
Compares arrays. Returnes void, may throw an \Exception.

### Compare::getDifferences ([$diffType = Compare::DIFF\_CHANGED + Compare::DIFF\_NEW + Compare::DIFF\_MISSING])
Returns array of CompareItem objects; each of object represents an item from base or test array which fits selected type of difference. Difference types can be combined (for example Compare::DIFF\_CHANGED + Compare::DIFF\_NEW, Compare::DIFF\_NEW + Compare::DIFF\_MISSING etc.).

### Compare::getDifferencesArray ([$diffType = Compare::DIFF\_CHANGED + Compare::DIFF\_NEW + Compare::DIFF\_MISSING])
Returns array of items from base or test array which fits selected type of difference. Difference types can be combined (for example Compare::DIFF\_CHANGED + Compare::DIFF\_NEW, Compare::DIFF\_NEW + Compare::DIFF\_MISSING etc.).

### Compare::$isEqual
Function for comparison of items. Can be set to fit your need. Original method is Compare::funcEqual ($a, $b) which returns true if both $a and $b are identical ($a is equal to $b and they are of the same type). By redefinition of this method you can change comparison type.

Example:

    Compare::$isEqual = function ($a, $b) {
        return $a == $b;
    };


### Difference types (constants):
* Compare::DIFF\_EQUAL:

  No difference between base (original) and tested array.

* Compare::DIFF\_CHANGED

  Item exists in both base (original) and tested array, it's value differs.

* Compare::DIFF\_NEW

  Item exists in tested array only.

* Compare::DIFF\_MISSING

  Item exists in base (original) array only.


## CompareItem object
A very simple object which represents array item

### public properties:
* originalValue

  Value from base (original) array (null for new items)

* newValue

  Value from test array (new for missing (deleted) items

* differenceType

  Type of difference (see Difference types)
