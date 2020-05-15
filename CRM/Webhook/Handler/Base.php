<?php

class CRM_Webhook_Handler_Base
{
  protected CRM_Webhook_Processor_Base $processor;
  protected $data;

  public function __construct(CRM_Webhook_Processor_Base $processor, $data=null)
  {
    $this->processor=$processor;
    $this->data=$data;
  }

  public function handle()
  {
    $majom=$this->data;

  }

}
