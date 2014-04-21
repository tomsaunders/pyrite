<?php

namespace Pyrite;

interface Byteable {
    /** @return length of this object in bytes */
    public function getLength();
} 