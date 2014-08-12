## Oven Boilerplate Generator

Simple boilerplate generator. Based on [Indatus/Blacksmith](https://github.com/Indatus/blacksmith) package.

### Installation

Install as global Composer package and add the global Composer bin directory to your PATH.
```composer global require "elepunk/oven=1.1.*"```

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

#### Running Oven

Generating specific item

```oven bake --r="source/to/recipe.json" --d="your/destination/directory" Foobar controller```

Generating the entire ingredients

```oven bake --r="source/to/recipe.json" --d="your/destination/directory" Foobar```

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
