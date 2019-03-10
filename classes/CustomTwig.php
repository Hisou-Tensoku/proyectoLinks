<?php

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class CustomTwig extends AbstractExtension {
    
    public function getFilters() {
        $filter1 = new Twig_SimpleFilter('imageFormat', array($this, 'imageFormatFilter'));
        return array(
            'imageFormat' => $filter1,
        );
    }
    
    public function imageFormatFilter($filter) {
        return $filter;
        //return base64_decode($filter);
        //return stream_get_contents($filter);
    }
}