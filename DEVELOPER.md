# Developer Notes

### Extend the config

The handler class has to be inherited from the base handler.
Implement the `hook_civicrm_webhookOptionValues` hook in your application and extend the config with your classes.

```php
/**
 * Implements hook_civicrm_webhookOptionValues().
 */
function myextension_civicrm_webhookOptionValues(&$config) {
    $config["processors"]["My_Processor_Class_Name"] = "My processor class label";
    $config["handlers"]["My_Handler_Class_Name"] = "My handler class label";
}
```
