<?php

namespace Stenfrank\SoapDIAN\Traits;

/**
 * Trait methods magic
 */
trait TraitMagic
{
    /**
     * Attributes
     * @var array
     */
    protected $attributes;
    
    /**
     * Set
     * @param  any $name
     * @param  any $value
     */
    public function __set($name, $value) {
        $this->attributes[$name] = $value;
    }
    
    /**
     * Get
     * @param any $name
     * @return any
     */
    public function __get($name) {
        if (array_key_exists($name, $this->attributes)) return $this->attributes[$name];
        
        return;
    }
}
