<?php

abstract class CRM_Webhook_Processor_Base
{
  abstract public function input();

  abstract public function output($result);
}
