<?php

$validate = new Validation();
class Validation extends Controller
{

    public function duration($duration)
    {
        if($duration == '30' || $duration == '60' || $duration == '90' || $duration == '180' || $duration == '365'){
            return true;
        } else {
            return false;
        }
    }

    // get interval factor vor an interval for example: 30 days, 365 days
    public function getIntervalFactor($interval){
        return number_format($interval / 30, 0);
    }

    public function is_domain_name($domain_name)
    {
        if((preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domain_name) && preg_match("/^.{1,253}$/", $domain_name) && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain_name)) == true){
            if (strpos($domain_name,'.') !== false) {
                return true;
            }
            return false;
        }
        return false;
    }
}