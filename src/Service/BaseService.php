<?php

namespace Sdtech\FileUploaderLaravel\Service;

abstract class BaseService {

    public function __call($method, $args)
    {
        $a ="_$method";
        return $this->$a(...$args);
    }
}
