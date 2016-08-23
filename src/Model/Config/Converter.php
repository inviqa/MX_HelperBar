<?php
namespace MX\HelperBar\Model\Config;

class Converter implements \Magento\Framework\Config\ConverterInterface
{
    /**
     * {@inheritdoc}
     */
    public function convert($source)
    {
        $result = [];
        /** @var \DOMNode $commandNode */
        foreach ($source->documentElement->childNodes as $commandNode) {
            if ($commandNode->nodeType != XML_ELEMENT_NODE) {
                continue;
            }
            $commandId = $commandNode->attributes->getNamedItem('name')->nodeValue;
            foreach ($commandNode->childNodes as $childNode) {
                /** @var \DOMNode $childNode */
                $result[$commandId][$childNode->nodeName] = $childNode->nodeValue;
            }

        }
        return $result;
    }

}
