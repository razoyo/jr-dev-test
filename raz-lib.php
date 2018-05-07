<?php

/**
 * Razoyo code goes here
 */

class ProductOutput
{
    protected $products = array();
    
    protected $format;

    public function format()
    {
        echo $this->format->start();
        foreach ($this->products as $product) {
            echo $this->format->formatProduct($product);
        }
        echo $this->format->finish();
    }

    public function setProducts(array $products)
    {
        $this->products = $products;
    }

    public function setFormat(FormatInterface $format)
    {
        $this->format = $format;
    }
}

interface FormatInterface
{
    public function start();

    public function formatProduct(array $product);

    public function finish();
}
