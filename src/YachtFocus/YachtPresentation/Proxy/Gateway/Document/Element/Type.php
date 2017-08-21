<?php

namespace YachtFocus\YachtPresentation\Proxy\Gateway\Document\Element;

use YachtFocus\Library\Enum;

/**
 * @method static Type INLINE()
 * @method static Type URL()
 */
class Type extends Enum
{
    const INLINE = 'Inline string';
    const URL    = 'Url to include';
}
