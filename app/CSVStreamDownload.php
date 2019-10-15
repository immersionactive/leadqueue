<?php

namespace App;

use Illuminate\Support\Collection;

class CSVStreamDownload
{

    protected $header_row;
    protected $data_rows;
    protected $data_row_callback;

    public function __construct(Collection $data_rows, callable $data_row_callback, ?array $header_row = null)
    {
        $this->data_rows = $data_rows;
        $this->data_row_callback = $data_row_callback;
        $this->header_row = $header_row;
    }

    public function render(): void
    {
        
        if ($this->header_row !== null) {
            echo $this->renderRow($this->header_row);
        }

        foreach ($this->data_rows as $data_row) {
            echo $this->renderRow(
                call_user_func($this->data_row_callback, $data_row)
            );
        }

    }

    /**
     * Render the data as an HTML table, rather than a CSV document.
     * This method is for debugging purposes.
     */
    public function renderTable(): void
    {

        echo '<table border="1">';

        if ($this->header_row !== null) {
            echo $this->renderTableRow($this->header_row, true);
        }

        foreach ($this->data_rows as $data_row) {
            echo $this->renderTableRow(
                call_user_func($this->data_row_callback, $data_row)
            );
        }

        echo '</table>';

    }

    protected function renderRow(array $row): string
    {
        $cells = [];
        foreach ($row as $value) {
            $cells[] = $this->renderCell($value);
        }
        return implode(',', $cells) . "\n";
    }

    protected function renderCell($value): string
    {
        return '"' . str_replace('"', '""', $value) . '"';
    }

    protected function renderTableRow(array $row, bool $is_header = false): string
    {

        $html = '<tr>';

        foreach ($row as $value) {
            $html .= $is_header ? '<th scope="col">' : '<td>';
            $html .= htmlspecialchars($value);
            $html .= $is_header ? '</th>' : '</td>';
        }

        $html .= '</tr>';

        return $html;

    }

}
