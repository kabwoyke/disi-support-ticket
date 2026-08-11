<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Models\User;

new class extends Component
{
    //

    #[Validate('required|email')]
    public $email = "";
    #[Validate('required')]
    public $password = "";


    public function login(){

        $creds = $this->validate();

        if(Auth::attempt($creds)){
            session()->regenerate();

            return redirect()->route("create-ticket");
        };

        $this->addError('email', 'The provided credentials do not match our records.');
    }
};
?>

<div>
    <div>
        <div id="image-logo-container" class="flex flex-col h-screen justify-center items-center">
            <img src="/disi-logo.avif" alt="disi-logo" class="w-48 h-auto">
            <div>
                <form wire:submit='login'>

                <fieldset class="fieldset">
                    <label class="label" for="email">Email</label>
                    <input type="email" id="email" class="input" wire:model.live='email' placeholder="name@gmail.com" />
                </fieldset>


                    @error('email')
                        <p class="error text-sm  text-red-500">{{ $message }}</p>
                    @enderror


                <fieldset class="fieldset">
                    <label class="label" for="password">Password</label>
                    <input type="password" id="password"  wire:model='password' class="input" placeholder="****" />
                </fieldset>

                @error('password')
                        <p class="error text-sm text-red-500">{{ $message }}</p>
                @enderror

                <button class="btn btn-primary w-full mt-4">Login</button>
                <a href="" class="text-sm font-normal">Don`t have an account?Signup</a>
                 </form>
            </div>
        </div>
    </div>
</div>
