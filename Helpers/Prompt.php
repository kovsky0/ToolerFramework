<?php
namespace ToolerFramework\Helpers;

use Exception;

/**
 * Defines the helper for prompting.
 */
class Prompt
{
    /**
     * Outputs a basic prompt.
     *
     * Outputs the provided text and starts a predifined new line
     * for getting the response from the user.
     *
     * @param string $text The text to be outputted. 
     */
    public function prompt($text) 
    {
        echo "$text\n";
        echo "    > ";
    }

    /**
     * Outputs a prompt with mutliple choices to choose from.
     *
     * Displays provided options to choose from and basic instructions.
     * Multiple answers are supported, but as a default user can choose only one option.
     *
     * @param array $choices The array with available options to choose from
     * @param string $text (optional) The text to be displayed before the instructions.
     * @param boolean $multiple_answers_allowed (optional) 
     */
    public function multipleChoice(array $choices, $text = '', $multiple_answers_allowed = false)
    {
        $instructions = $text . "\n";
        $instructions .= "Select your answer from the following options:\n"; 

        foreach ($choices as $key => $choice) {
            $index = $key + 1;
            $instructions .= "[$index] " . $choice . "\n";
        }

        $instructions .= "\nType a number from 1 to " . count($choices) . " to select your answer.\n";
        if ($multiple_answers_allowed) {
            $instructions .= "You can choose more than one answer. Seperate the answers with a comma, for example: 1,2,4\n";
        }

        $this->prompt($instructions);        
    }

    /**
     * Outputs a confirmation prompt.
     *
     * @param string $text (optional) The question to which user's confirmation is needed.
     */
    public function confirmation($text = 'Are you sure you want to continue? [y/n]')
    {
        $this->prompt($text);
    }

    /**
     * Returns user's answer to prompted message.
     *
     * @param boolean $trim (optional) Wheather to trim the answer.
     *
     * @return string The answer to prompted message.
     */
    public function getResponse($trim = true)
    {
        $handle = fopen ("php://stdin","r");
        $line = fgets($handle);
        return ($trim ? trim($line) : $line);
    }

    /**
     * Returns user's answer to multiple choice question.
     *
     * @param boolean $multiple_answers_allowed Wheather multiple answers are allowed.
     *
     * @return string The answer to prompted question.
     */
    public function getMultipleChoiceResponse($multiple_answers_allowed = false)
    {
        $response = $this->getResponse();

        if (!$multiple_answers_allowed and !is_numeric($response)) {
            throw new Exception("The answer needs to be a number");
        }

        if ($response == 0) {
            throw new Exception("The answer is not valid");
        }

        if ($multiple_answers_allowed) {
            $answer = explode(",", $response);

            if (!$answer) {
                throw new Exception("You need to choose at least one answer");
            }

        } else {
            $answer = $response;
        }

        return $answer;
    }

    /**
     * Returns user's answer to the request for confirmation.
     *
     * Any answer starting with 'y' or 'Y' will return true,
     * everything else will return false.
     *
     * @return boolean User's answer to the request for confirmation.
     */
    public function getConfirmationResponse()
    {
        $response = $this->getResponse();

        //Anything that starts with "y" is ok (case-insensitive)
        return (mb_strtolower($response[0]) === 'y');
    }    
}