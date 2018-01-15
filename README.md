# ToolerFramework
a **minimalistic** PHP framework for rapid Command Line Interface tools development.

## Installation
Tooler can be installed using Composer:

```
composer create-project kovsky0/ToolerFramework --prefer-dist
```
## The idea
The main idea behind this project was to create an extremely minimalistic micro-framework for rapid CLI tools development.
It gives you a simple directory structure and access to basic helpers for: parsing the input, formatting the output, and prompting user for additional input.

## Quick Start (in three simple steps) 

### Step 1: Build a new command
After creating the project you can build your commands through 'tooler build' command:
```
php tooler build two-plus-two
```
It will automatically create the following files: 
 - ./two-plus-two which you can use to run your command with 'php two-plus-two'
 - ./Commands/TwoPlusTwo/TwoPlusTwoCommand.php which you can use to specify what your command should do
 
!!! Imporant: for the consistant composition, please use a dash ("-") to seperate the words.
 
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
 ```
 
 ## Documentation
 
 ### Available commands
 You can see tooler's available commands by typing:
 ```
 php tooler help
 ```
 
 There are following commands available:
```
php tooler build <new-command-name>
```
Builds new command.

```
php tooler rename <old-command-name> <new-command-name>
```
Renames the command.

```
php tooler remove <command-name>
```
Removes the command and all files associated with it.

### Available helpers
There are 3 helpers available to you (if your command extends "StandardCommand", which is true by default).

#### Input
You can use it in your code by referencing $this->input.
- $this->command - the name of the command that was called
- $this->flags - all the -short and --long flags that have been used
- $this->arguments - all the other arguments that have been used

 

## To-do
- add unit tests
- refactor CommandsFileManager.php
- add interfaces to the helpers
- add tables formatter to the Output.php helper
