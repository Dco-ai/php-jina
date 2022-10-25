<?php

namespace DcoAi\PhpJina\Connection;


class ConnectJina
{
    private $data;
    private $url;
    private $endpoints = [];
    public function __construct($conf) {
        $this->url = $conf["url"].":".$conf["port"];
        $this->endpoints = $conf["endpoints"];
    }
    public function callAPI($endpoint, $da){
        $this->data = $this->cleanDocArray($da->documentArray);
        $method = $this->endpoints[$endpoint];
        $url = $this->url.$endpoint;
        $data = $this->data;
        $curl = curl_init();
        switch ($method){
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }
        // OPTIONS:
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        // EXECUTE:
        $result = curl_exec($curl);
        if(!$result){die("Connection Failure");}
        curl_close($curl);
        return json_decode($result);
    }
    private function cleanDocArray($da): \stdClass
    {
        $newDa = new \stdClass();
        $newDa->parameters = $da->parameters;
        $newDa->data = [];
        foreach ($da->data as $document) {
            $doc = new \stdClass();
            foreach ($document->document as $k => $v) {
                $tmp = (array) $v;
                if (!empty($v) && !empty($tmp)) {
                    $doc->{$k} = $v;
                }
            }
            $newDa->data[] = $doc;
        }
        return $newDa;
    }

}