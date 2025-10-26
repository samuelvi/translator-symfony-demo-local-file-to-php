Spreadsheet Translator Symfony Demo Application - Use Case
======================================================================================

Introduction
------------

Lightweight Symfony Demo Application for the Spreadsheet Translator functionallity.
The demo brings a command that takes a local spreadhseet file and creates a translation file per locale in Php format.

Installation
------------

composer create-project samuelvi/translator-symfony-demo-local-file-to-php

This will install the demo application into your computer

The source demo spreadsheet file is located at app/Resources/doc/homepage.xlsx


Running the demo
---------

type in you terminal: bin/console atico:demo:translator --sheet-name=common

This command will generate the translation files that will be stored into app/translations folder.

The generated files will be:

```
  app
  |
  └───Resources
     │
     └──translations
         │  demo_common.en_GB.php
         │  demo_common.es_ES.php   
         │  demo_common.fr_FR.php

```      
                              
demo_common.en_GB.php will contain:

```php
  <?php
  return array (
    'homepage_title' => 'Spreadsheet translator',
    'homepage_subtitle' => 'Translator of web pages from spreadsheets',
  );
```

Notes
-----


composer.json will include the following Spreadsheet Translator dependencies:
```
  "samuelvi/spreadsheet-translator-core": "^8.0",
  "samuelvi/spreadsheet-translator-symfony-bundle": "8.4.1",
  "samuelvi/spreadsheet-translator-provider-localfile": "^8.1",
  "samuelvi/spreadsheet-translator-reader-xlsx": "^8.1",
  "samuelvi/spreadsheet-translator-exporter-php": "^8.1",
```

Related
------------

Symfony Bundle:
- <a href="https://github.com/samuelvi/spreadsheet-translator-symfony-bundle">Symfony Bundle</a>

Symfony Demos:

- <a href="https://github.com/samuelvi/translator-symfony-demo-local-file-to-php">Symfony Demo. Takes a local file and creates translation files per locale in php format</a>
- <a href="https://github.com/samuelvi/translator-symfony-demo-google-to-yml">Symfony Demo. Takes a google drive spreadsheet and creates translation files per locale in yml format</a>
- <a href="https://github.com/samuelvi/translator-symfony-demo-onedrive-to-xliff">Symfony Demo. Takes a microsoft one drive spreadsheet and creates translation files per locale in xliff format</a>


Requirements
------------

  * PHP >=8.4
  * Symfony >=7.3


Run Rector
----------

Rector is configured to upgrade code to PHP 8.4 and Symfony 7.3 standards.

**Dry-run mode (check changes without applying):**
```bash
bin/rector process --dry-run
```

**Apply changes:**
```bash
bin/rector process
```

**Using Docker:**
```bash
make shell
bin/rector process --dry-run  # Check changes first
bin/rector process            # Apply changes
```


Contributing
------------

We welcome contributions to this project, including pull requests and issues (and discussions on existing issues).

If you'd like to contribute code but aren't sure what, the issues list is a good place to start. If you're a first-time code contributor, you may find Github's guide to <a href="https://guides.github.com/activities/forking/">forking projects</a> helpful.

All contributors (whether contributing code, involved in issue discussions, or involved in any other way) must abide by our code of conduct.


License
-------

Spreadsheet Translator Symfony Bundle is licensed under the MIT License. See the LICENSE file for full details.

