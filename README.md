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

### Administration

Extension add very simple language list page with activate/deactivate option.

To include the page into your controller you have to add it to `actions()` method. 

```
class LanguageController extends Controller
{
    public function actions()
    {
        return [
            'index' => 'futuretek\language\IndexAction',
        ];
    }
}
```

Development
-----------

Assets are managed by [Compass](http://compass-style.org/)

* While developing run `compass watch` in extension root directory
* To compile assets for final distribution run `compass compile -e production --force` in extension root directory

  