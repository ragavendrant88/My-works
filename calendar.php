<?php

/* Week ID */
define('SUN', '0');
define('MON', '1');
define('TUE', '2');
define('WED', '3');
define('THU', '4');
define('FRI', '5');
define('SAT', '6');

/* Week Font color */
define('SUN_COLOR' , '#FF0000');
define('SAT_COLOR' , '#0000FF');
define('WEEK_COLOR', '#000000');
define('CAL_BGCOLOR', '#FFFFFF');
define('NOW_BGCOLOR', '#FFFFE0');
/**
 * Calendar class  
 */
class CreateCalendar extends EnhanceCommand
{

    /* Target year */
    var $year = 1;

    /* Target month */
    var $month = 1;

    /* Target Date */
    var $day = 1;

    /* Time stamp */
    var $timestamp = 0;

    /* Week start date */
    var $start = 0;

    /* End date */
    var $last = 0;

    /* Number of weeks */
    var $weeks = 0;

    /* Calendar data */
    var $calendar = array();

    /* Holiday data */
    var $holiday = array();

    /**
     * Constructor
     *
     * @access    public
     * @param     string    $year     year
     * @param     string    $month    month
     * @return    void
     */
    public function CreateCalendar($year, $month)
    {
        parent::EnhanceCommand();
        $this->year      = $year;                                                   // year
        $this->month     = $month;                                                  // month
        $this->timestamp = mktime(0, 0, 0, $this->month, $this->day, $this->year);  // Time stamp
        $this->start     = intval(date('w', $this->timestamp));  // Week start date
        $this->last      = intval(date('t', $this->timestamp));  // End date
        $this->weeks     = intval((($this->start + $this->last) / 7) + 0.9);  // Number of weeks
    }

    /**
     * Calendar generation
     *
     * @access    public
     * @return    array     Calendar data
     */
    public function getCalendar()
    {
        $day     = 1;
        $curdate = date('Ymd');
        $start   = $this->start;
        for ($row=0; $row<$this->weeks; $row++) {
            $this->calendar[$row] = array('', '', '', '', '', '', '');
            for ($col = $start; $col<7 && $day<=$this->last; $col++) {
                $this->calendar[$row][$col]['bgcolor'] = CAL_BGCOLOR;
                $date = date('Ymd', mktime(0, 0, 0, $this->month, $day, $this->year));
                if ($curdate === $date) {
                    $this->calendar[$row][$col]['bgcolor'] = NOW_BGCOLOR;
                }
                $this->calendar[$row][$col]['day']   = $day++;
                $this->calendar[$row][$col]['week']  = $this->get_week_name($col);
                $this->calendar[$row][$col]['color'] = $this->get_font_color($col);
            }
            $start = 0;
        }
        return ($this->calendar);
    }

    /**
     * Set the font color for each day of the week
     *
     * @access    public
     * @return    string    $w    Week no
     */
    private function get_font_color($w)
    {
        $font = '';

        switch ($w) {
            case SUN:
                $font = SUN_COLOR;
                break;
            case SAT:
                $font = SAT_COLOR;
                break;
            default :
                $font = WEEK_COLOR;
                break;
        }

        return $font;
    }

    /**
     * Set the name of each day of the week
     *
     * @access    public
     * @return    string    $w    Week no
     */
    private function get_week_name($w)
    {
        $name = '';

        switch ($w) {
            case SUN:
                $name = 'SUN';
                break;
            case MON:
                $name = 'MON';
                break;
            case TUE:
                $name = 'TUE';
                break;
            case WED:
                $name = 'WED';
                break;
            case THU:
                $name = 'THU';
                break;
            case FRI:
                $name = 'FRI';
                break;
            case SAT:
                $name = 'SAT';
                break;
        }

        return $name;
    }
}
?>
