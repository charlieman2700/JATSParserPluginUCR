# JATSParserPlugin for "Universidad de Costa Rica"
The JATSParserPlugin has been adapted from the OJS Plugin to cater specifically to the "Universidad de Costa Rica" magazine's department. This plugin offers seamless conversion of JATS XML files to both HTML and PDF formats, aligning perfectly with the style standards of the esteemed "Universidad de Costa Rica."
## Enhancements
* Added new metadata forms to accommodate additional metadata, enhancing the flexibility of the plugin.
* Undertook a significant refactor of the codebase, which was initially concentrated in a single file, to enhance maintainability and make future modifications easier.
* Modified the PDF generation process to ensure that the output adheres to the precise style standards of the "Universidad de Costa Rica."
## Features 
* JATS XML to HTML conversion.
* JATS XML to PDF conversion.
## How to use?
### Installation for development
1. Navigate to `plugins/generic` folder starting from OJS webroot.
2. `git clone --recursive https://github.com/Vitaliy-1/JATSParserPlugin.git jatsParser`.
3. To install support for JATS to PDF conversion: `cd jatsParser/JATSParser` and `composer install`.  
## Requirements
* PHP 7.3 or higher
* OJS 3.1+
* Lens Galley Plugin must be turned off


