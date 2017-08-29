# YachtPresentation Proxy

## Connect to a remote YachtPresentation

This small library facilitates the communication between a YachtPresentation and your local PHP website. 

## Sample code

```php
<?php
    use YachtFocus\YachtPresentation\Proxy\Gateway\Document\Element\Type as ElementType;
    use YachtFocus\YachtPresentation\Proxy\Gateway\Proxy as YachtFocusProxy;

    $proxy    = new YachtFocusProxy('http://somewhere.com/yachtpresentation/proxy.php');
    $document = $proxy->getDocument(
        'nl',
        session_id(),
        $_REQUEST + [
            'REQUEST_URI' => $_SERVER['REQUEST_URI'],
        ]
    );
    
    $jsonDecoded = json_decode($document->getBody());
    if (json_last_error() === JSON_ERROR_NONE) {
        header('Content-type: application/json');
        echo $document->getBody();
        exit;
    }
    
    echo $document->getTitle() ?: 'Mijn Eigen Title';
    
    foreach ($document->getCss() as $cssElement) {
        if ($cssElement->getType()->equalTo(ElementType::INLINE())) {
            echo '<style>' . $cssElement->getValue() . '</style>';
        } elseif ($cssElement->getType()->equalTo(ElementType::URL())) {
            echo '<link rel="stylesheet" type="text/css" href="' . $cssElement->getValue() . '">';
        }
    }

    echo $document->getBody();

    foreach ($document->getJs() as $jsElement) {
        if ($jsElement->getType()->equalTo(ElementType::INLINE())) {
            echo '<script type="text/javascript">' . $jsElement->getValue() . '</script>';
        } elseif ($jsElement->getType()->equalTo(ElementType::URL())) {
            echo '<script type="text/javascript" src="' . $jsElement->getValue() . '"></script>';
        }
    }
```