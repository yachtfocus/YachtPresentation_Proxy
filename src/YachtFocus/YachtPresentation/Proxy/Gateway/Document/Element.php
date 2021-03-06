<?php

namespace YachtFocus\YachtPresentation\Proxy\Gateway\Document;

use JsonSerializable;
use YachtFocus\YachtPresentation\Proxy\Gateway\Document\Element\Type;

class Element implements JsonSerializable
{
    /**
     * @var Type
     */
    private $type;

    /**
     * @var string
     */
    private $value;

    /**
     * Element constructor.
     *
     * @param Type   $type
     * @param string $value
     */
    public function __construct(Type $type, $value)
    {
        $this->type  = $type;
        $this->value = $value;
    }

    /**
     * @return Type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return [
            'type'  => $this->type,
            'value' => $this->value,
        ];
    }
}
