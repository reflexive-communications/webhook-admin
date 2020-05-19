# hu.es-progress.webhook

This extension creates additional webhook endpoints where CiviCRM listens for incoming request and processes them according to the needs.

A PayPal webhook handler and a general handler included as an example.

This extension is designed to be easily extended to handle any further webhooks, though some coding is required to implement these.

Currently it can process JSON and standard url-encoded-form data.

The extension is licensed under [AGPL-3.0](LICENSE.txt).

## Requirements

* PHP v7.0+
* CiviCRM (5.22, 5.25) (might work below, not tested)

## Installation (Web UI)

This extension has not yet been published for installation via the web UI.

## Installation (CLI, Zip)

Sysadmins and developers may download the `.zip` file for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
cd <extension-dir>
cv dl hu.es-progress.webhook@https://github.com/semseysandor/hu.es-progress.webhook/archive/master.zip
```

## Installation (CLI, Git)

Sysadmins and developers may clone the [Git](https://en.wikipedia.org/wiki/Git) repo for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
git clone https://github.com/semseysandor/hu.es-progress.webhook.git
cv en webhook
```

## Usage

There will be new endpoints for receiving webhooks:

www.example.com/civicrm/extension/webhook/paypal

or

YOUR_SITE/civicrm/extension/webhook/civi

## Known Issues

No GUI
