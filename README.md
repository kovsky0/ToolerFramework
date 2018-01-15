# ToolerFramework
a **minimalistic** PHP framework for rapid Command Line Interface tools development.

## Installation
Tooler can be installed using Composer:

```
composer create-project kovsky0/ToolerFramework --prefer-dist
```
## The idea
The main idea behind this project was to create an extremely minimalistic and simple micro-framework for rapid CLI tools development.
It gives you a simple directory structure and (optional) access to basic helpers for: parsing the input, formatting the output, and prompting user for additional input.

## Quick Start (in three simple steps) 

### Step 1: Build a new command
After creating the project you can build your commands through 'tooler build' command:
```
php tooler build two-plus-two
```
It will automatically create the following files: 
 - ./two-plus-two which you can use to run your command with 'php two-plus-two'
 - ./Commands/TwoPlusTwo/TwoPlusTwoCommand.php which you can use to specify what your command should do
 
 ### Step 2: Define what the command should do
 Add your logic to the 'execute' method in the following file:
 ```
 ./Commands/TwoPlusTwo/TwoPlusTwoCommand.php
 ```
 For example: 
 ```
<?php
namespace ToolerFramework\Commands\TwoPlusTwo;

use ToolerFramework\Commands\StandardCommand;

class TwoPlusTwoCommand extends StandardCommand
{
    public function execute()
    {
        echo 2+2;
    }
}
 ```
 
 ### Step 3: Run your command
 Run you command by typing:
 ```
 php two-plus-two
 ``
 
 

## To-do
- add unit tests
- refactor CommandsFileManager.php
- add interfaces to the helpers
- add tables formatter to the Output.php helper
