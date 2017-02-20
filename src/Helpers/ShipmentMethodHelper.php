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
    public static function getMethods() {
        return [
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
    }

    /**
     * Return a formatted list if schedules
     * @return array
     */
    public static function getTiming() {
        return [
            [
                'label'       => 'Voor 10 uur \'s ochtends',
                'code'        => self::TIMING_BEFORE_10,
                'implemented' => true,
            ],
            [
                'label'       => 'Voor 12 uur \'s ochtends',
                'code'        => self::TIMING_BEFORE_12,
                'implemented' => true,
            ],
            [
                'label'       => 'Avondlevering',
                'code'        => self::TIMING_EVENING,
                'implemented' => true,
            ],
            [
                'label'       => 'Zondaglevering',
                'code'        => self::TIMING_SUNDAY,
                'implemented' => true,
            ],
        ];
    }

}