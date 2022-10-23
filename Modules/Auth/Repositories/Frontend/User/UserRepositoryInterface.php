<?php
namespace Modules\Auth\Repositories\Frontend\User;

interface UserRepositoryInterface
{
    public function telecommPanel();
    public function fuelPanel();
    public function passengerPanel();
    public function delivery();
    public function intravenous();
    public function packages();
    public function formats();
    public function chargingPanel();
    public function reservationPanel();
}