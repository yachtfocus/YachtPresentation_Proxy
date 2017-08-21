<?php

namespace YachtFocus\YachtPresentation\Proxy\Gateway;

use JsonSerializable;
use YachtFocus\YachtPresentation\Proxy\Gateway\Document\Element;

class Document implements JsonSerializable
{
    /**
     * @var string
     */
    private $body;

    /**
     * @var Element[]
     */
    private $js;

    /**
     * @var Element[]
     */
    private $css;
    /**
     * @var string
     */
    private $title;

    /**
     * Document constructor.
     *
     * @param string    $body
     * @param string    $title
     * @param Element[] $js
     * @param Element[] $css
     */
    public function __construct($body, $title = '', array $js = [], array $css = [])
    {
        $this->body  = $body;
        $this->title = $title;
        $this->js    = $js;
        $this->css   = $css;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     *
     * @return Document
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return Document
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Element[]
     */
    public function getJs()
    {
        return $this->js;
    }

    /**
     * @param Element[] $js
     *
     * @return Document
     */
    public function setJs(Element ...$js)
    {
        $this->js = $js;

        return $this;
    }

    /**
     * @return Element[]
     */
    public function getCss()
    {
        return $this->css;
    }

    /**
     * @param Element[] $css
     *
     * @return Document
     */
    public function setCss(Element ...$css)
    {
        $this->css = $css;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'title' => $this->title,
            'css'   => $this->css,
            'js'    => $this->js,
            'body'  => $this->body,
        ];
    }
}
