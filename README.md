CSBill
======

[![Build Status](https://travis-ci.org/CSBill/CSBill.png?branch=master)](https://travis-ci.org/CSBill/CSBill)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/CSBill/CSBill/badges/quality-score.png?s=fdd7a5f5080807e95a317b9c0db07e8d5ce8cb63)](https://scrutinizer-ci.com/g/CSBill/CSBill/)
[![Dependency Status](https://www.versioneye.com/user/projects/557ebccc61626613850000cc/badge.svg)](https://www.versioneye.com/user/projects/557ebccc61626613850000cc)

Open-Source General Billing Manager

CSBill is an open-source application that allows you to manage clients and contacts and send invoices and quotes.

Requirements
------------

CSBill requires minimum PHP 7.1.0.

*Note:* The latest version of PHP is always recommended

## Installation

### Docker

Docker makes it really easy to get started as quickly as possible in running CSBill.

The docker image is available at https://hub.docker.com/r/csbill/csbill/ with instructions on how to get started.

### Archived Package

Download the latest release from https://github.com/CSBill/CSBill/releases in either `zip` or `tar.gz` format,
and extract the contents of the archive under your webserver directory. 

### For developers

To install from source, you first need to clone the repository, then you need [composer][2] in order to install all the dependencies.

To clone the repository, issue the following command. Remember to clone the repository to the path you want, that is accessible from your webserver.

```bash
$ git clone https://github.com/CSBill/CSBill.git
```

Then go into the repository directory

```bash
$ cd CSBill
```

Now you need to get composer

```bash
$ curl -s http://getcomposer.org/installer | php
```

When composer is finished downloading, you can install the required dependencies:

```bash
$ php composer.phar install
```

After all the dependencies has been installed, the next step is to install all the web assets

```bash
$ php app/console assets:install --symlink web
```

The final step is to install the Node packages, and compile all the assets.

To install the node packages, you can use Yarn.

To install Yarn, run the following command:

```bash
$ curl -o- -L https://yarnpkg.com/install.sh | bash
```

Then you can run the final step

```bash
$ yarn
$ ./node_modules/.bin/gulp
```

Now you should have a fully working copy of CSBill.

Features
--------

Some of the basic features included in CSBill is:

* Clients and Contacts management
* Create and manage Quotes
* Create and manage Invoices
* Accept payments online
* Tax and discount handling
* RESTful API
* Receive Notifications either via text message, email or through HipChat
* More to come


Contributing
------------

See [CONTRIBUTING](CONTRIBUTING.md)

License
------------

CSBill is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

Please see the [LICENSE](LICENSE) file for the full license.

Demo
------------

[http://demo.csbill.org](http://demo.csbill.org)


[1]: http://symfony.com
[2]: http://getcomposer.org
[3]: http://lesscss.org

Donate
------

[![Paypal](https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=EQLK2P3VBW2LC)
