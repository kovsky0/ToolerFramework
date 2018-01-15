# ToolerFramework
a **minimalistic** PHP framework for rapid Command Line Interface tools development.

## Installation
Tooler can be installed using Composer:

```
composer create-project kovsky0/tooler-framework
```
## The idea
The main idea behind this project was to create a minimalistic framework for rapid CLI tools development.
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
 
 ## Basic usage
 
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

#### $this->input
You can use it in your code by referencing $this->input.
- `$this->input->command` - the name of the command that was called
- `$this->input->flags` - all the -short and --long flags that have been used
- `$this->input->arguments` - all the other arguments that have been used

#### $this->output
You can use it in your code by referencing $this->output.
- `$this->output->write("exemplary text", "white", "red")` - outputs given text to the console, first color is a color of the fotn, second color is the color of the background.
- `$this->output->writeln("exemplary text", "white", "red")` - outputs given text to the console with a newline character at the end.
- `$this->output->wirteln(array("line 1", "line 2", "third line"), "white", "red")` - outputs each elements of the array in a new line
- `$this->output->message("Title: ", "A long text body of the message")` - outputs multiline message, where the text is justified next to the provided title. See an example below:
              TITLE: TEXT TEXT TEXT TEXT
                     TEXT TEXT TEXT TEXT 
- `$this->output->warning("This is a custom warning")` - outputs a message with "WARNING: " as a title, red background, and white font color.
- `$this->output->info("This is a custom info")` - outputs a message with "INFO: " as a title, blue background, and white font color.
- `$this->output->success("This is a custom success message")` - outputs a message with "SUCCESS: " as a title, green background, and white font color.
                     
Available font colors:
* 'black'
* 'dark_grey'
* 'blue'
* 'light_blue'
* 'green'
* 'light_green'
* 'cyan'
* 'light_cyan'
* 'red'
* 'light_red'
* 'purple'
* 'light_purple'
* 'brown'
* 'yellow'
* 'light_gray'
* 'white'

Available background colors:
* 'black'
* 'red'
* 'green'
* 'yellow'
* 'blue'
* 'magenta'
* 'cyan'
* 'light_gray'

#### $this->prompt
You can use it in your code by referencing $this->prompt.
- `$this->prompt->prompt("What is your favourite color?")` - prompts custom text
- `$this->prompt->confirm("Are you sure you want to continue? [y/n]")` - prompts a yes or no question
- `$this->prompt->multipleChoice(array("blue", "yellow", "green", "red"))` - prompts a multiple choice question, by default - only one option can be chosen
- `$this->prompt->multipleChoice(array("blue", "yellow", "green", "red"), null, true)` - prompts a multiple choice question, where user can choose more than one option
- `$this->prompt->getResponse()` - returns the user's input
- `$this->prompt->getConfirmationResponse()` - returns true for anything starting with 'y' or 'Y' and false for everything else
- `$this->prompr->getMultipleChoiceResponse()` - returns user's choices for multiple choice question 



## To-do
- add unit tests
- refactor CommandsFileManager.php
- add interfaces to the helpers
- add tables formatter to the Output.php helper
