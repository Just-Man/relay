<?php
/**
 * Created by PhpStorm.
 * User: just
 * Date: 31.10.16
 * Time: 12:12
 */

namespace App\Model;


class RelayModel
{
    public function getModel()
    {
        return [
            'id' => (int) 0,
            'title' => (string) "",
            'status' => (boolean) false,
            'noRepeat' =>
                [
                    'noRepeat' => (boolean) false,
                    'fromDAte'  => "validateDate",
                    'toDate'  => "validateDate",
                ],
            'cyclically' =>
                [
                    'cyclically' => (boolean) false,
                    'onTime' => (int) 0,
                    'timesPerDay' => (int) 0,
                ],
            'weekly' =>
                [
                    'weekly' => (boolean) false,
                    'mon' =>
                        [
                            'mon' => (boolean) false,
                            'monOnTime' => "validateDate",
                            'monOffTime' => "validateDate",
                        ],
                    'tue' =>
                        [
                            'tue' => (boolean) false,
                            'tueOnTime'  => "validateDate",
                            'tueOffTime'  => "validateDate",
                        ],
                    'wed' =>
                        [
                            'wed' => (boolean) false,
                            'wedOnTime' => "validateDate",
                            'wedOffTime' => "validateDate",
                        ],
                    'thu' =>
                        [
                            'thu' => (boolean) false,
                            'thuOnTime' => "validateDate",
                            'thuOffTime' => "validateDate",
                        ],
                    'fri' =>
                        [
                            'fri' => (boolean) false,
                            'friOnTime' => "validateDate",
                            'friOffTime' => "validateDate",
                        ],
                    'sat' =>
                        [
                            'sat' => (boolean) false,
                            'satOnTime' => "validateDate",
                            'satOffTime' => "validateDate",
                        ],
                    'sun' =>
                        [
                            'sun' => (boolean) false,
                            'sunOnTime' => "validateDate",
                            'sunOffTime' => "validateDate",
                        ],
                ],
        ];
    }
}