# Libadmin Localization - administrate translations
---------------------------------------------------

## 1. Location and file format

Translations are handled by the use of **gettext catalog (.po)** files.
All translation files are stored inside the module's `/language` folder.
For each language there has to be created one language file, named by the code
of that respective language.

**Example:** `/language\de_DE.po` for german german, `/language\en_US.po` for american english.


## 2. Use Poedit (http://www.poedit.net/)

Poedit is a cross-plattform editor for gettext catalogs, that allows to conveniently edit
translations by automatically collecting translatable labels from a module.

Install on Ubuntu : ```sudo apt-get install poedit  ```


## 3. How to create language files with Poedit


For Poedit to be able to find our templates, you have to add the pattern
for `.phtml` files to the configuration of Poedit's Parser:

### 1. From the menu go to: **File > Properties**
* Change into the tab **Parser**
* Select the **PHP parser** and click the **Edit** button
* Add `*.phtml` to the semikolon-separated list of file endings

### 2. Choose from the menu: **File > New > Catalog**,
 	inside the initially openend tab **Translation properties** fill in (at least):

* Project name: Name of your module name, e.g. "Libadmin"
* Language:		The language code, e.g. "de_DE"

### 3. Change into the tab "source paths"
  	Poedit looks for templates to be parsed for labels inside these paths.
  	Source paths are defined relatively to the .po file itself,
  	as Poedit searches downwards, add the parent directory:

* Click the "New element" button in the "Paths" form,
* Enter the path: ..

### 4. All names of viewhelper methods that handle translations must be known
 	to Poedit for parsing the templates and collecting your labels, therefor:

* Change into the tab "Sources keywords"
* Click the button "New item" of the keywords form
* Enter item title: translate

### 5. The catalog of editable labels now can be updated by clicking the **Update** option from the main menu bar.
	
	
## 4. Administration of generic labels

To administer "generic" labels which are not discoverable by PoEdit because of their code-pattern of occurence
(e.g.: `<?= $this->translate(....)`) you can either add and edit those manually to the gettext catalog, or add
them into a pseudo-template. This is a template, which is not actively used for displayed content of the module
and only used as a means for transmitting labels. 
The included pseudo-template can be found at: `\view\libadmin\global\gettext-labels.phtml`