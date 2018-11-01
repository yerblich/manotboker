<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;

class ClientChart extends Chart
{
    /**
     * Initializes the chart.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->labels(['Marble', 'Returns', 'Three', 'Four', 'Five']);
        $this->height(500);
        $this->width(800);
    }
}
