<?php
class JSONFile
{
    private $file_name;

    public function __construct($file_name)
    {
        $this->file_name = $file_name;
    }

    public function get_json_contents()
    {
        $json_data = file_get_contents($this->file_name);

        if ($json_data === false) {
            echo "Error reading json file.";
            return null;
        }

        $data = json_decode($json_data, true);

        if ($data === null) {
            echo "Error decoding json data.";
            return null;
        }

        return $data;
    }
}
