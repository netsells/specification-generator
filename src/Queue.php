<?php

namespace Juddling\Parserator;

/*
 * There's nothing in the PHP standard lib which covers this
 * These look good: https://medium.com/@rtheunissen/efficient-data-structures-for-php-7-9dda7af674cd
 * But I'd rather not depend on an extension
 */
class Queue
{
    protected $items;

    public function __construct($items = [])
    {
        $this->items = $items;
    }

    public function pop()
    {
        return array_shift($this->items);
    }

    public function push($item)
    {
        return array_push($this->items, $item);
    }

    public function length()
    {
        return count($this->items);
    }
}