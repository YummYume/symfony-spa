<?php

namespace App\Entity\Traits;

trait ArrayAccessTrait
{
  public function offsetExists($offset): bool
  {
    return property_exists($this, $offset);
  }

  public function offsetGet($offset)
  {
    return $this->$offset;
  }

  public function offsetSet($offset, $value): void
  {
    $this->$offset = $value;
  }

  public function offsetUnset($offset): void
  {
    unset($this->$offset);
  }
}
