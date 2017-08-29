<?php

namespace YachtFocus\YachtPresentation\Proxy\Gateway;

use YachtFocus\YachtPresentation\Proxy\Gateway\Document\Element;
use YachtFocus\YachtPresentation\Proxy\Gateway\Document\Element\Type;

class Proxy
{
    /**
     * @var string
     */
    private $endpoint;

    /**
     * Proxy constructor.
     *
     * @param string $endpoint
     */
    public function __construct($endpoint)
    {
        $this->endpoint = $endpoint;
    }

    /**
     * @param string $locale
     * @param string $sessionId
     * @param array  $request
     *
     * @return Document
     */
    public function getDocument($locale, $sessionId, array $request)
    {
        $request['PHPSESSID'] = $sessionId;
        $request['LOCALE']    = $locale;
        $url                  = $this->endpoint;

        $opts = [
            'http' =>
                [
                    'method'  => 'POST',
                    'header'  => 'Content-type: application/x-www-form-urlencoded',
                    'content' => http_build_query($request),
                ],
        ];

        $context = stream_context_create($opts);

        $remoteContent = file_get_contents($url, false, $context);
        if (!$remoteContent) {
            $document    = 'Failed loading';
            $documentJs  = [];
            $documentCss = [];
        } else {
            $document    = json_decode($remoteContent);
            $documentJs  = [];
            $documentCss = [];

            if (json_last_error()) {
                return new Document(
                    $remoteContent,
                    $documentJs,
                    $documentCss
                );
            }
        }

        foreach ($document->js as $js) {
            $documentJs[] = new Element(Type::getByKey($js->type), $js->value);
        }

        foreach ($document->css as $css) {
            $documentCss[] = new Element(Type::getByKey($css->type), $css->value);
        }

        return new Document(
            $document->body,
            $document->title,
            $documentJs,
            $documentCss
        );
    }
}
