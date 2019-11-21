<?php

namespace Pyrite;

interface Output {
	/** @return hex encoder for this object */
	public function toHex();
}