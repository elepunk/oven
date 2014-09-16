## Oven Boilerplate Generator

Simple boilerplate generator for PHP 5.4 and above. Based on [Indatus/Blacksmith](https://github.com/Indatus/blacksmith) package.

### Installation

Install as global Composer package and add the global Composer bin directory to your PATH.
```composer global require "elepunk/oven=1.2.*"```

### Updating Oven

To update Oven, you can use ```composer global update``` command.

### Available Commands

Command | Description
--- | ---
`oven recipe:configure` | Set default recipes location
`oven recipe:bake` | Generate codes by using recipe folder name

### Usage

To start using Oven, you first need to create a new recipe.

#### Creating New Recipe

Create a new folder and create a new file called ```recipe.json```.

```javascript
{
    "name": "Sample Recipe",
    "ingredients": {
        "controller": {
            "source": "Controller/controller.php",
            "name": "(entity)Controller.php"
        },
        "processor": {
            "source": "Processor/processor.php",
            "name": "(entity).php"
        }
    }
}
```

All files that want to be created need to be specified under ```ingredients```. Source like ```Controller/controller.php``` will be copied to the new destination.

Check out my sample recipe [Oven Orchestra Recipe](https://github.com/elepunk/oven-orchestra).

#### Running Oven

Generating specific item

```oven bake --r="source/to/recipe.json" --d="your/destination/directory" Foobar controller```

Generating the entire ingredients

```oven bake --r="source/to/recipe.json" --d="your/destination/directory" Foobar```

#### Organizing Your Recipes

Set the recipes location

```oven recipe:configure /path-to-your-recipe-files```

Create new recipes under the recipe location. Then generate the items

```oven recipe:bake --d="your/destination/directory" recipe-template-directory-name Foobar controller```

#### Available Options

`--r="source-to-recipe-file"`

This is to specify the location of the recipe file if you are in another directory. Default is the current directory.

`--d="destination"`

This will be the place where new files will be created. Default is the current directory.

`--f`

This will overwrite existing file

#### Available Template Variables

Variable | Description | Output
--- | --- | ---
`(entity)` | Studly cased entity name | Foobar
`(instance)` | Lower cased entity name | foobar
`(namespaces)` | Studly cased namespace | Foo\Bar\Foobar

### Changelogs

#### v1.3.0-dev
* Deprecated ```oven bake``` command
* ```oven recipe:bake``` recipe path can now be override using ```--recipe``` option
* Added ```--ignore-namespace``` option to generate files in the same directory
* Added ```oven recipe:skeleton``` command to create recipe boilerplate
* Make the command options more readable
* Overhaul the codebase to make it more testable
* Added unit tests

#### v1.2.0

* Bump to minimum PHP verion 5.4
* Add global option to set default recipe directory
* Enable oven to bake using template
* Change errors to exceptions
* Added list of files generated after success

#### v1.1.2

* Add force option using --f
* Add instance template variable

#### v1.1.1

* Detect current working directory it is running from

#### v1.1.0

* Fix vendor autoloading issue

#### v1.0.0

* Initial release
