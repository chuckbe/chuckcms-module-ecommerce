<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Chuck;

use Chuckbe\Chuckcms\Models\Repeater;
use Chuckbe\ChuckcmsModuleEcommerce\Models\User;
use Chuckbe\Chuckcms\Chuck\UserRepository as ChuckcmsUserRepository;
use ChuckSite;
use Auth;
use Illuminate\Http\Request;

class UserRepository
{
	private $repeater;
    private $chuckcmsUserRepository;

	public function __construct(Repeater $repeater, ChuckcmsUserRepository $chuckcmsUserRepository)
    {
        $this->chuckcmsUserRepository = $chuckcmsUserRepository;
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
            'token' => $this->chuckcmsUserRepository->createToken(),
            'active' => 1,
        ]);
        //event: user.created
        
        $user->assignRole('customer');

        return $user;
    }

}