# webhook-admin

[![CI](https://github.com/reflexive-communications/webhook-admin/actions/workflows/main.yml/badge.svg)](https://github.com/reflexive-communications/webhook-admin/actions/workflows/main.yml)

**THIS IS A LEGACY EXTENSION. CURRENTLY UNDER FEATURE FREEZE!**

This extension provides a public API endpoint as listener for webhooks and an administrator interface for setting up handler applications for processing the incoming messages.
Currently it can process JSON, XML and standard URL encoded form data messages.
This extension provides an example handler that saves the request details to database but you need to implement your own handlers.
For details check the [Developer Notes](DEVELOPER.md).

The extension is licensed under [AGPL-3.0](LICENSE.txt).

## Requirements

- PHP v7.3+
- CiviCRM v5.76+
- rc-base

## Installation

Sysadmins and developers may clone the [Git](https://en.wikipedia.org/wiki/Git) repo for this extension and install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
git clone git@github.com:reflexive-communications/webhook-admin.git
cv en webhook
```

## Getting Started

Go to **Administer > Webhooks**.
Click "Add New Webhook" button and fill out the form. The selector has to be unique.
If you have logs in the log database the "Check Logs" button is visible next to the new hook button.

After setup you can call new endpoint: this endpoint is `https://example.com/webhook-admin/external/listener.php`.
A selector value has to be added as listener GET parameter.
If the selector is `my-hook-handler`, then the endpoint of the listener application will be `https://example.com/webhook-admin/external/listener.php?listener=my-hook-listener`

## Known Issues

The extension does not provide upgrader process for v1 -> v2 migration. The supported way is the following:

- Uninstall v1
- Install v2

The v1 configurations will be lost in this process.
