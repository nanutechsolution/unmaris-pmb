<?php

namespace App\Livewire\Camaba;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

class Pengumuman extends Component
{
    #[Layout('layouts.camaba')]

    public function render()
    {
        $user = Auth::user();
        $pendaftar = $user->pendaftar;

        if (!$pendaftar) {
            return redirect()->route('camaba.formulir');
        }

        return view('camaba.pengumuman', [
            'pendaftar' => $pendaftar
        ]);
    }
}
