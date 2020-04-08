<?php

namespace SubstitutionPlugin\Provider;

class ConstantProvider implements AutoloadDependentProviderInterface
{
    /** @var string */
    private $constantName;

    /**
     * @param string $constantName
     */
    public function __construct($constantName)
    {
        $this->constantName = $constantName;
    }

    /**
     * @inheritDoc
     */
    public function mustAutoload()
    {
        return !defined($this->constantName);
    }

    /**
     * @inheritDoc
     */
    public function getValue()
    {
        if (!defined($this->constantName)) {
            throw new \InvalidArgumentException('Value is not a constant: ' . $this->constantName);
        }

        return constant($this->constantName);
    }
}
