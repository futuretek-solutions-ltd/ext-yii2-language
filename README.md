Yii2 Language support
=====================

Usage
-----

### Data

Database will be populated via migration automatically.

### Model

Use model ```futuretek\language\models\Language``` wherever you want

### Language selector

Language selector can be used in any view. 
Language will be automatically switched in Yii application and saved into cookie.

There are two selectors available:

* ```LanguageSelector::dropDown()``` - display drop-down with all enabled languages 
* ```LanguageSelector::flagList()``` - display list of all enabled language flags

Changelog
---------

### 1.0.0
* Initial version 
