<?php

namespace Spaceboy;

class CompareItem {

    public $originalValue;
    public $newValue;
    public $differenceType;

    public function __construct ($originalValue, $newValue, $differenceType) {
        $this->originalValue    = $originalValue;
        $this->newValue         = $newValue;
        $this->differenceType   = $differenceType;
    }

}

class Compare {

    private static $base = array();
    private static $test = array();
    private static $differences = array();

    public static $isEqual = array('self', 'funcEqual');

    const MISSING_BASE  = 'Missing base array to compare with';
    const MISSING_TEST  = 'Missing test array to find differences';

    const DIFF_EQUAL    = 1;
    const DIFF_CHANGED  = 2;
    const DIFF_NEW      = 4;
    const DIFF_MISSING  = 8;

    /* PUBLIC METHODS */

    public static function setBase (array $base) {
        self::$base = $base;
    }

    public static function getBase () {
        return self::$base;
    }

    public static function setTest (array $test) {
        self::$test = $test;
    }

    public static function getTest () {
        return self::$test;
    }

    public static function compare ($base = null, $test = null) {
        if ($test) {
            self::setTest($test);
            if ($base) {
                self::setBase($base);
            }
        }
        if (!self::$base) {
            throw new \Exception(self::MISSING_BASE);
        }
        if (!self::$test) {
            throw new \Exception(self::MISSING_TEST);
        }
        self::findDifferences();
    }

    public static function getDifferences ($diffType = 14) {
        return array_filter(self::$differences, function ($el) use ($diffType) {
            return ($el->differenceType == ($el->differenceType & $diffType));
        });
    }

    public static function getDifferencesArray ($diffType = 14) {
        return array_map (function ($el) {
            return $el->newValue;
        }, self::getDifferences($diffType));
    }

    /* WORKING METHODS */

    protected static function findDifferences () {
        $diff = array();
        $test = self::$test;
        /* Find equals & missing & chaged items: */
        foreach (self::$base as $key => $val) {
            if (!array_key_exists($key, $test)) {
                $diff[$key] = new CompareItem($val, null, self::DIFF_MISSING);
            } else {
                if (call_user_func(self::$isEqual, $val, $test[$key])) {
                    $diff[$key] = new CompareItem($val, $test[$key], self::DIFF_EQUAL);
                } else {
                    $diff[$key] = new CompareItem($val, $test[$key], self::DIFF_CHANGED);
                }
                unset($test[$key]);
            }
        }
        /* Find new items: */
        foreach ($test as $key => $val) {
            $diff[$key] = new CompareItem(null, $val, self::DIFF_NEW);
        }
        self::$differences = $diff;
    }

    protected static function funcEqual ($a, $b) {
        return $a === $b;
    }

}
