<?php
namespace ToolerFramework\Helpers;

/**
 * Defines the helper for the output.
 */
class Output
{
    /**
     * @var array Maps the colors' names to console fonts colors' codes. 
     */
    private $font_colors = array
    (
        'black' => '0;30',
        'dark_grey' => '1;30',
        'blue' => '0;34',
        'light_blue' => '1;34',
        'green' => '0;32',
        'light_green' => '1;32',
        'cyan' => '0;36',
        'light_cyan' => '1;36',
        'red' => '0;31',
        'light_red' => '1;31',
        'purple' => '0;35',
        'light_purple' => '1;35',
        'brown' => '0;33',
        'yellow' => '1;33',
        'light_gray' => '0;37',
        'white' => '1;37'
    );

    /**
     * @var array Maps the colors' names to console backgrounds colors' codes. 
     */
    private $background_colors = array
    (
        'black' => '40',
        'red' => '41',
        'green' => '42',
        'yellow' => '43',
        'blue' => '44',
        'magenta' => '45',
        'cyan' => '46',
        'light_gray' => '47'
    );

    /**
     * Returns a text with added color codes.
     *
     * @param string $text The text to which the colors will be added.
     * @param string $font_color (optional) The color of the font.
     * @param string $background_color (optional) The color of the background.
     *
     * @return string Returns the text with added color codes.
     */
    public function applyColors($text, $font_color = null, $background_color = null)
    {

        $formatted_text = '';

        if ($font_color and array_key_exists(strtolower($font_color), $this->font_colors)) {
            $formatted_text .= "\033[" . $this->font_colors[$font_color] . "m";
        }

        if ($background_color and array_key_exists(strtolower($background_color), $this->background_colors)) {
            $formatted_text .= "\033[" . $this->background_colors[$background_color] . "m";
        }

        $formatted_text .=  $text . "\033[0m";

        return $formatted_text;
    }

    /**
     * Returns a text moved to the center of the line of defined length.
     *
     * If not length is provided, as default it is set to be 80 characters.
     * If the text is longer than the length, nothing will be changed.
     *
     * @param string $text The text to be centered.
     * @param int $length (optional) The length of the line.
     * @param string $char (optional) The character to be used to fill the margins.
     *
     * @return string Returns a string with added margins that center the text.
     */
    public function center($text, $length = 80, $char = " ")
    {
        $text_length = strlen($text);
        if ($text_length > $length) {
            return $text; //should we throw an error here instead?
        }
        $margin_length = ($length / 2) - ($text_length / 2);
        $margin_left_length = floor($margin_length);
        $margin_right_length = ceil($margin_length);

        $margin_left = str_repeat($char, $margin_left_length);
        $margin_right = str_repeat($char, $margin_right_length);

        return $margin_left . $text . $margin_right;
    }

    /**
     * Outputs given text to the console.
     *
     * @param string $text The text which will be outputted.
     * @param string $font_color (optional) The color of the text's font.
     * @param string $background_color (optional) The color of the text's background.
     */
    public function write($text, $font_color = null, $background_color = null)
    {
        if (!$text) {
            return;
        }

        $formatted_text = $text;

        if ($font_color or $background_color) {
            $formatted_text = $this->applyColors($text, $font_color, $background_color);
        }

        echo $formatted_text;
    }

    /**
     * Outputs given text with a newline character at the end.
     *
     * @param string|array $lines The text to be outputted. Either single line 
     *      as a string or multiple lines as an array.
     * @param string $font_color (optional) The color of the text's font.
     * @param string $background_color (optional) The color of the text's background.
     */
    public function writeln($lines, $font_color = null, $background_color = null) 
    {
        $lines = (array) $lines;

        foreach($lines as $line)
        {
            $this->write($line, $font_color, $background_color);
            $this->write("\n");
        }
    }

    /**
     * Outputs an empty line.
     *
     * @param string $color (optional) Background color of the empty line.
     * @param int $length (optional) The length of the empty line.
     * @param string $char (optional) The character to be used to fill the empty line.
     */
    public function emptyLine($color = null, $length = 80, $char = " ")
    {
        $this->writeln(str_repeat($char, $length), null, $color);
    }

    /**
     * Outputs a title block.
     *
     * Outputs and empty line, a line with centered text, and another empty line.
     *
     * @param string $text The text to be formatted into the title block.
     * @param string $font_color (optional) The color of the text's font.
     * @param string $background_color (optional) The color of the text's background.
     * @param int $length (optional) The length of the block.
     * @param string $char (optional) The character to be used to fill the empty spaces.
     */
    public function title($text, $font_color = null, $background_color = null, $length = 80, $char = " ")
    {      
        $this->emptyLine($background_color, $length, $char);
        $this->writeln($this->center($text, $length), $font_color, $background_color);
        $this->emptyLine($background_color, $length, $char);
    }

    /**
     * Outputs a message block.
     *
     * Outputs either single line message (it is centered than) or multiline message,
     * where the text is justified next to the provided title. See an example below:
     * TITLE: TEXT TEXT TEXT TEXT
     *        TEXT TEXT TEXT TEXT
     *
     * @param string $title The title of the message.
     * @param string $text The text body of the message.
     * @param string $font_color (optional) The color of the text's font.
     * @param string $background_color (optional) The color of the text's background.
     * @param int $length (optional) The length of the block.
     * @param int $margins_length (optional) The length of the margins.
     * @param int $lines_before (optional) Number of lines to output before the text body.
     * @param int $lines_after (optional) Number of lines to output after the text body.
     * @param string $char (optional) The character to be used to fill the empty spaces.
     */
    public function message($title, $text, $font_color = null, $background_color = null, $length = 80, $margins_length = 4, $lines_before = 1, $lines_after = 1, $char = " ")
    {
        
        $max_text_length = $length - strlen($title) - 2 * $margins_length;
        $text_length = strlen($text);
        $lines_number = ceil($text_length / $max_text_length);

        $margin = str_repeat($char, $margins_length);
        $title_margin = str_repeat($char, strlen($title));

        if ($lines_number > 1) {
            // add lines before
            for ($i = 0; $i < $lines_before; $i++)
            {
                $this->emptyLine($background_color, $length, $char);
            }

            //split the text into lines and output them
            $text_lines = preg_split('/\R/', $text);                

            foreach ($text_lines as $text_line_key => $text_line) {
                $lines = str_split($text_line, $max_text_length);
                foreach ($lines as $line_key => $line) {
                    //first element
                    if ($text_line_key === 0 && $line_key === 0) { 
                        //show the title before the line
                        $output = $margin . $title . $line . $margin;
                        $this->writeln($output, $font_color, $background_color);
                    //last element
                    } elseif ($line === end($lines)) { 
                        //color the empty space after the text of last line
                        $line_length = strlen($margin . $title_margin . $line . $margin);  
                        $output = $margin . $title_margin . $line . $margin . str_repeat(" ", $length - $line_length);
                        $this->writeln($output, $font_color, $background_color);
                    //normal line
                    } else {
                        $output = $margin . $title_margin . $line . $margin;
                        $this->writeln($output, $font_color, $background_color);
                    }
                }
            }

            // add lines after
            for ($i = 0; $i < $lines_after; $i++)
            {
                $this->emptyLine($background_color, $length, $char);
            }
        } else {
            $this->title($title . $text, $font_color, $background_color);
        }

    }

    /**
     * Outputs a warning.
     *
     * Outputs a predifined message with "WARNING: " as a title, 
     * red background and white font.
     *
     * @param string $text The text body of the warning.
     * @param int $length (optional) The length of the block.
     * @param int $margins_length (optional) The length of the margins.
     * @param int $lines_before (optional) Number of lines to output before the text body.
     * @param int $lines_after (optional) Number of lines to output after the text body.
     * @param string $char (optional) The character to be used to fill the empty spaces.
     */
    public function warning($text, $length = 80, $margins_length = 4, $lines_before = 1, $lines_after = 1, $char = " ")
    {
        $this->message("WARNING: ", $text, "white", "red", $length = 80, $margins_length = 4, $lines_before = 1, $lines_after = 1, $char = " ");        
    }

    /**
     * Outputs an info block.
     *
     * Outputs a predifined message with "INFO: " as a title, 
     * blue background and white font.
     *
     * @param string $text The text body of the block.
     * @param int $length (optional) The length of the block.
     * @param int $margins_length (optional) The length of the margins.
     * @param int $lines_before (optional) Number of lines to output before the text body.
     * @param int $lines_after (optional) Number of lines to output after the text body.
     * @param string $char (optional) The character to be used to fill the empty spaces.
     */
    public function info($text, $length = 80, $margins_length = 4, $lines_before = 1, $lines_after = 1, $char = " ")
    {
        $this->message("INFO: ", $text, "white", "blue", $length = 80, $margins_length = 4, $lines_before = 1, $lines_after = 1, $char = " ");        
    }

    /**
     * Outputs a success block.
     *
     * Outputs a predifined message with "SUCCESS: " as a title, 
     * green background and white font.
     *
     * @param string $text The text body of the block.
     * @param int $length (optional) The length of the block.
     * @param int $margins_length (optional) The length of the margins.
     * @param int $lines_before (optional) Number of lines to output before the text body.
     * @param int $lines_after (optional) Number of lines to output after the text body.
     * @param string $char (optional) The character to be used to fill the empty spaces.
     */
    public function success($text, $length = 80, $margins_length = 4, $lines_before = 1, $lines_after = 1, $char = " ")
    {
        $this->message("SUCCESS: ", $text, "white", "green", $length = 80, $margins_length = 4, $lines_before = 1, $lines_after = 1, $char = " ");        
    }
}