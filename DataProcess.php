<?php
require_once 'JSONFile.php';
require_once 'Database.php';
require_once 'QueryFunctions.php';

class DataProcess
{
    private $jsonReader;
    private $query;
    private $filterData;
    private $unique_data;

    public function __construct($file_name, $db)
    {
        $this->jsonReader = new JSONFile($file_name);
        $this->query = new QueryFunctions($db);
        $this->filterData = array();
    }

    public function insert_json_data()
    {
        $data = $this->jsonReader->get_json_contents();

        if ($data !== null) {
            // Get the existing participation_ids
            $existing_participation_ids = $this->query->get_existing_participation_ids();

            // Filter out the new entries from the json data
            $new_record = array_filter($data, function ($entry) use ($existing_participation_ids) {
                return !in_array($entry['participation_id'], $existing_participation_ids);
            });

            // Insert only the new record into the database
            if (!empty($new_record)) {
                $this->query->insert_data($new_record);
            }
        }
    }

    public function apply_filter()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $this->filterData['employee_name'] = $_POST['employee_name'] ?? '';
            $this->filterData['event_name'] = $_POST['event_name'] ?? '';
            $this->filterData['event_date'] = $_POST['event_date'] ?? '';
        }
    }

    public function get_filtered_data()
    {
        return $this->query->filtered($this->filterData);
    }

    public function get_unique_value($column)
    {
        // Get unique value from column
        return $this->query->get_unique_record($column);
    }

    public function get_total_price(): float
    {
        $filteredData = $this->get_filtered_data();
        $total_price = 0.00;

        foreach ($filteredData as $entry) {
            $total_price += (float)$entry['participation_fee'];
        }

        return $total_price;
    }

    public function render()
    {
        $unique_data = array(
            'employee_name' => $this->get_unique_value('employee_name'),
            'event_name' => $this->get_unique_value('event_name'),
            'event_date' => $this->get_unique_value('event_date')
        );

        // Apply filter and get filtered data
        $this->apply_filter();
        $data = $this->get_filtered_data();

        // Calculate the total price of all filtered entries
        $total_price = $this->get_total_price();

        // Include the HTML view file
        require_once 'display.html';
    }
}
