<?php


namespace Omnipay\GoCardlessV2\Message;
use GoCardlessPro\Core\Paginator;
use Omnipay\Common\Message\AbstractResponse as BaseAbstractResponse;


abstract class AbstractSearchResponse extends BaseAbstractResponse implements \Iterator
{
    /**
     * @var Paginator
     */
    protected $data;

    public function isSuccessful()
    {   // failures are errors and never get here!
        return true;
    }

 public function current (){
        return $this->data->current();
 }
 public function key ( ){
        return $this->data->key();
 }
 public function next (){
        $this->data->next();
 }
 public function rewind (){
        $this->data->rewind();
 }
 public function valid (){
        return $this->data->valid();
 }
}
