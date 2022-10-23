<?php
namespace Modules\Auth\Repositories\Frontend\User;

use App\Repositories\EloquentRepository;
use Modules\Auth\Repositories\Frontend\User\UserRepositoryInterface;

class UserRepository extends EloquentRepository implements UserRepositoryInterface
{
    /**
     * for driver.
     */
    public function telecommPanel(){

    }
    public function fuelPanel(){

    }
    public function passengerPanel(){

    }
    /**
     * for traveler.
     */
    public function delivery(){

    }
    public function intravenous(){

    }
    public function packages(){

    }
    public function formats(){

    }
        /**
     * for delegate.
     */
    public function chargingPanel(){

    }
    public function reservationPanel(){

    }
    
}