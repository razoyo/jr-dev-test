<?php
/**
 * Your code goes here
 */
class FormatFactory
{
    protected $formatKey;
    protected $productArray = array();
    public function create($key, $arr)
    {
        $this->formatKey = $key;
        $this->productArray = $arr;
        //print($this->productArray);
        //var_dump($this->productArray);
        if ($this->formatKey == 'csv') {
            header("Content-Type: text/csv");
            header('Content-disposition: attachment;filename=razoyo.csv');
            $header = array("sku", "name", "price", "short_description");
            $fp = fopen("php://output", "w");
            fputcsv($fp, $header, ",");
            foreach ($this->productArray as $each) {
                $onlyValues = array_values($each);
                fputcsv($fp, $onlyValues);   //fputcsv requires $onlyValues must be array
            }
            fclose($fp);
        }
        if ($this->formatKey == 'xml'){
            header('Content-type: text/xml');
            $dataTransformator = new DataTransformator();
            $domDocument = $dataTransformator->data2domDocument($this->productArray);
            $xml = $domDocument->saveXML();
            echo $xml;
        }
        if ($this->formatKey == 'json') {
            //header('Cache-Control: no-cache, must-revalidate');
            header('Content-type: application/json');
            $arrayToJson = new ArrayToJson();
            //foreach($this->productArray as $each){
            //    $json = $arrayToJson->jsEncode($each);
            //    echo $json;
            //}
            //$myArray[] = array("id" => 1, "data" => 45);
            $json = $arrayToJson->jsEncode($this->productArray);
            echo $json;
            //echo $json;
            //var_dump($this->productArray);
            //echo $this->jsonEncode($this->productArray);
        }
    }
}
//reference: http://stackoverflow.com/questions/5733041/convert-associative-array-to-xml-in-php
class DataTransformator {
    /**
     * Converts the $data to a \DOMDocument.
     * @param array $data
     * @param string $rootElementName
     * @param string $defaultElementName
     * @see MyNamespace\Dom\DataTransformator#data2domNode(...)
     * @return Ambigous <DOMDocument>
     */
    public function data2domDocument(array $data, $rootElementName = 'products', $defaultElementName = 'item') {
        return $this->data2domNode($data, $rootElementName, null, $defaultElementName);
    }
    /**
     * Converts the $data to a \DOMNode.
     * If the $elementContent is a string,
     * a DOMNode with a nested shallow DOMElement
     * will be (created if the argument $node is null and) returned.
     * If the $elementContent is an array,
     * the function will applied on every its element recursively and
     * a DOMNode with a nested DOMElements
     * will be (created if the argument $node is null and) returned.
     * The end result is always a DOMDocument object.
     * The casue is, that a \DOMElement object
     * "is read only. It may be appended to a document,
     * but additional nodes may not be appended to this node
     * until the node is associated with a document."
     * See {@link http://php.net/manual/en/domelement.construct.php here}).
     *
     * @param Ambigous <string, mixed> $elementName Used as element tagname. If it's not a string $defaultElementName is used instead.
     * @param Ambigous <string, array> $elementContent
     * @param Ambigous <\DOMDocument, NULL, \DOMElement> $parentNode The parent node is
     *  either a \DOMDocument (by the method calls from outside of the method)
     *  or a \DOMElement or NULL (by the calls from inside).
     *  Once again: For the calls from outside of the method the argument MUST be either a \DOMDocument object or NULL.
     * @param string $defaultElementName If the key of the array element is a string, it determines the DOM element name / tagname.
     *  For numeric indexes the $defaultElementName is used.
     * @return \DOMDocument
     */
    protected function data2domNode($elementContent, $elementName, DOMNode $parentNode = null, $defaultElementName = 'item') {
        $parentNode = is_null($parentNode) ? new DOMDocument('1.0', 'utf-8') : $parentNode;
        $name = is_string($elementName) ? $elementName : $defaultElementName;
        if (!is_array($elementContent)) {
            $content = htmlspecialchars($elementContent);
            $element = new DOMElement($name, $content);
            $parentNode->appendChild($element);
        } else {
            $element = new DOMElement($name);
            $parentNode->appendChild($element);
            foreach ($elementContent as $key => $value) {
                $elementChild = $this->data2domNode($value, $key, $element);
                $parentNode->appendChild($elementChild);
            }
        }
        return $parentNode;
    }
}
//reference: http://au.php.net/manual/en/function.json-encode.php#82904  eep2004 at ukr dot net
//however not allowed to use built-in function is pooyly thought out. It doesn't make sense.
class ArrayToJson{
    public function jsEncode($val)
    {
        if (is_string($val)) return '"'.addslashes($val).'"';
        if (is_numeric($val)) return $val;
        if ($val === null) return 'null';
        if ($val === true) return 'true';
        if ($val === false) return 'false';
        $assoc = false;
        $i = 0;
        foreach ($val as $k=>$v){
            if ($k !== $i++){
                $assoc = true;
                break;
            }
        }
        $res = array();
        foreach ($val as $k=>$v){
            $v = $this->jsEncode($v);
            if ($assoc){
                $k = '"'.addslashes($k).'"';
                $v = $k.':'.$v;
            }
            $res[] = $v;
        }
        $res = implode(',', $res);
        return ($assoc)? '{'.$res.'}' : '['.$res.']';
    }
}
