<?php


namespace App\Helper;

/**
 * Class DateHelper
 * @package App\Helper
 */
class DateHelper
{
    /**
     * @param \DateTime $date1
     * @param \DateTime $date2
     * @return string
     */
    public function getTimeElapsed(\DateTime $date1, \DateTime $date2)
    {
        $diff = $date1->diff($date2);

        $timeElapsed = (int)$diff->format('%y');
        if($timeElapsed == 1){
            return "{$timeElapsed} année";
        }elseif($timeElapsed > 1){
            return "{$timeElapsed} années";
        }

        $timeElapsed = (int)$diff->format('%m');
        if($timeElapsed == 1){
            return "{$timeElapsed} mois";
        }elseif($timeElapsed > 1){
            return "{$timeElapsed} mois";
        }

        $timeElapsed = (int)$diff->format('%a');
        if($timeElapsed == 1){
            return "{$timeElapsed} jour";
        }elseif($timeElapsed > 1){
            return "{$timeElapsed} jours";
        }

        $timeElapsed = (int)$diff->format('%h');
        if($timeElapsed == 1){
            return "{$timeElapsed} heure";
        }elseif($timeElapsed > 1){
            return "{$timeElapsed} heures";
        }

        $timeElapsed = (int)$diff->format('%i');
        if($timeElapsed == 1){
            return "{$timeElapsed} minute";
        }elseif($timeElapsed > 1){
            return "{$timeElapsed} minutes";
        }

        $timeElapsed = (int)$diff->format('%s');
        if($timeElapsed == 1){
            return "{$timeElapsed} seconde";
        }elseif($timeElapsed > 1){
            return "{$timeElapsed} secondes";
        }

        return "à l'instant";
    }

}