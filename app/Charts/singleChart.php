<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Highcharts\Chart;

class singleChart extends Chart
{
    /**
     * Initializes the chart.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->labels(['One', 'Two', 'Three', 'Four', 'Five']);
        $this->height(500);
        $this->width(800);
    }
}
