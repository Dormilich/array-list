# array-list

A simple immutable array iterator based on the native `ArrayIterator` class.

![Minimum PHP Version](https://img.shields.io/badge/php-≥%205.6-8892BF.svg)
![Build Status](https://img.shields.io/travis/Dormilich/array-list/master.svg)
![License](https://img.shields.io/github/license/dormilich/array-list.svg)

## Installation

Install via Composer:
```
composer require dormilich/array-list
```

## Supported methods

- `append()` → array_push()
- `asort()` → asort()
- `contains()` → in_array()
- `countValues()` → array_count_values()
- `filter()`¹ → array_filter()
- `join()` → implode()
- `keys()` → array_keys()
- `ksort()` → ksort()
- `map()`¹ → array_map()
- `natcasesort()` → natcasesort()
- `natsort()` → natsort()
- `prepend()` → array_unshift()
- `reduce()`¹ → array_reduce()
- `reject()`¹ (the inverse of array_filter())
- `reverse()` → array_reverse()
- `shuffle()` → shuffle()
- `uasort()` → uasort()
- `uksort()` → uksort()
- `unique()` → array_unique()
- `values()` → array_values()

*1 - uses key & value of the array element in the callback function*

## Supported functions

- count()
- json_encode()
- serialize()
