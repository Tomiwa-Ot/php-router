<?php

final class Response
{

    public function json($data, $statusCode)
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        http_response_code($statusCode);
    }

    public function xml($data = array(), $statusCode)
    {
        header('Content-Type: application/xml');
        $xml = new SimpleXMLElement('<root/>');
        array_flip($data);
        array_walk_recursive($data, array($xml, 'addChild'));
        echo $xml->asXML();
        http_response_code($statusCode);
    }

    public function render($view, $data = array())
    {
        if(count($data)) extract($data);
        require __DIR__ . '/../View/layout/' . $view;
    }
}