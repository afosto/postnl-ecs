<?php

namespace Afosto\Ecs\Helpers;

class ShipmentMethodHelper {

    /**
     * PostNL standard
     */
    const METHOD_STANDARD = '03085';

    /**
     * PostNL home address only & signature when received
     */
    CONST METHOD_HOME_ADDRESS_SIGNATURE = '03089';

    /**
     * PostNL signature when received
     */
    CONST METHOD_SIGNATURE = '03189';

    /**
     * PostNL standard home address only
     */
    CONST METHOD_HOME_ADDRESS_STANDARD = '03385';

    /**
     * Receive before 10 o'clock
     */
    CONST TIMING_BEFORE_10 = '118|007';

    /**
     * Receive before 12 o'clock
     */
    CONST TIMING_BEFORE_12 = '118|008';

    /**
     * Receive in the evening
     */
    CONST TIMING_EVENING = '118|006';

    /**
     * Sunday delivery
     */
    CONST TIMING_SUNDAY = '101|008';

    /**
     * Return a formatted list of methods
     *
     * @return array
     */
    public static function getMethods($filterImplemented = true) {
        $methods = [
            [
                'label'       => 'Standaard',
                'code'        => self::METHOD_STANDARD,
                'implemented' => true,
            ],
            [
                'label'       => 'Thuisadres handtekening',
                'code'        => self::METHOD_HOME_ADDRESS_SIGNATURE,
                'implemented' => false,
            ],
            [
                'label'       => 'Handtekening',
                'code'        => self::METHOD_SIGNATURE,
                'implemented' => false,
            ],
            [
                'label'       => 'Thuisadres standaard',
                'code'        => self::METHOD_HOME_ADDRESS_STANDARD,
                'implemented' => true,
            ],
        ];

        if ($filterImplemented) {
            $methods = array_filter($methods, function ($value) {
                return $value['implemented'];
            });
        }

        return $methods;
    }

    /**
     * Return a formatted list if schedules
     * @return array
     */
    public static function getTiming() {
        return [
            [
                'label'    => 'Standaard',
                'code'     => null,
                'maxHour'  => '18',
                'minHour'  => '8',
                'weekdays' => [1, 2, 3, 4, 5, 6],
            ],
            [
                'label'    => 'Voor 10 uur \'s ochtends',
                'code'     => self::TIMING_BEFORE_10,
                'maxHour'  => '10',
                'minHour'  => '8',
                'weekdays' => [1, 2, 3, 4, 5, 6],
            ],
            [
                'label'    => 'Voor 12 uur \'s ochtends',
                'code'     => self::TIMING_BEFORE_12,
                'maxHour'  => '12',
                'minHour'  => '8',
                'weekdays' => [1, 2, 3, 4, 5, 6],
            ],
            [
                'label'    => 'Avondlevering',
                'code'     => self::TIMING_EVENING,
                'maxHour'  => '24',
                'minHour'  => '18',
                'weekdays' => [1, 2, 3, 4, 5, 6],
            ],
            [
                'label'    => 'Zondaglevering',
                'code'     => self::TIMING_SUNDAY,
                'maxHour'  => '18',
                'minHour'  => '8',
                'weekdays' => [7],
            ],
        ];
    }

    /**
     * Returns the timing option
     *
     * @param integer $weekday
     * @param integer $maxHour
     * @param null    $minHour
     *
     * @return string
     */
    public static function getTimingOption($weekday, $maxHour = null, $minHour = null) {
        $availableMethods = array_filter(self::getTiming(), function ($method) use ($weekday) {
            return (in_array($weekday, $method['weekdays']));
        });

        $beforeNextFilters = $availableMethods;

        //Sort max hours
        usort($availableMethods, function ($method1, $method2) {
            return $method1['maxHour'] > $method2['maxHour'];
        });

        //Filter max hours
        $availableMethods = array_filter($availableMethods, function ($method) use ($maxHour) {
            return $method['maxHour'] > $maxHour;
        });

        //If we still have multiple options: filter for min hour (when given)
        if (count($availableMethods) > 1 && $minHour !== null) {
            $availableMethods = array_filter($availableMethods, function ($method) use ($minHour) {
                return $method['minHour'] >= $minHour;
            });
        }

        if (count($availableMethods) > 0) {
            return current($availableMethods)['code'];
        } else {
            return current($beforeNextFilters)['code'];
        }
    }

}