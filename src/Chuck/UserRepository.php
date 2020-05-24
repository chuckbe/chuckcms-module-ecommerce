<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Chuck;

use Chuckbe\Chuckcms\Models\Repeater;
use Chuckbe\ChuckcmsModuleEcommerce\Models\User;
use ChuckSite;
use Auth;
use Illuminate\Http\Request;

class UserRepository
{
	private $repeater;

	public function __construct(Repeater $repeater)
    {
        $this->repeater = $repeater;
    }

    /**
     * Get all the attributes
     *
     * @var string
     **/
    public function updateName(string $name)
    {
        $user = Auth::user();

        $user->name = $name;
        $user->update();
    }

    /**
     * Create a new user
     *
     * @var string
     **/
    public function create($request)
    {
        $user = new User();

        $user = User::create([
            'name' => $request->get('customer_surname') . ' ' . $request->get('customer_name'),
            'email' => $request->get('customer_email'),
            'password' => bcrypt($request->get('customer_password')),
            'token' => $this->userRepository->createToken(),
            'active' => 1,
        ]);
        //event: user.created
        
        $user->assignRole('customer');

        return $user;
    }

}