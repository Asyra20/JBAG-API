<?php

namespace Database\Seeders;

use App\Models\AkunGame;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class AkunGameSeeder extends Seeder
{
    public function run()
    {
        $akunGames = [
            [
                'penjual_id' => 1,
                'game_id' => 3,
                'judul' => 'AKUN SULTAN BERSKIN LEGEND FRANCO + COLLECTOR LING + COLLECTOR BENEDETA + RECALL GG',
                'deskripsi' => 'DIJAMIN SAT SET SAAT PAKE LING',
                'gambar' => 'public/images/akun-game/akun-mole-ling-murah.png',
                'harga' => 150000,
                'status_akun' => 'terjual',
            ],
            [
                'penjual_id' => 1,
                'game_id' => 2,
                'judul' => 'Akun Game Kedua',
                'deskripsi' => '
!!! YUK KEPOIN

Nickname : AwwSelvee
Level 67

senjata evogun 4 :
1. Shotguns Sterling conqueror Level Max
2. MP5 Platinum Deviniti Level Max
3. M4AI Infernal Draco Level 6
4. GROZA Bang ! Poblaster Level 2

Senjata favorit :
1. Katana infernoshok ( legend )
2. SHOTGUNS 2 Trompet
3. Thomson Hybrid Explosion
4. Tinju ada 1


BANDEL LEGEND
1. RAMPAGE UNITED

BANDEL BOYAH PASS LEGEND
1. ELECTRI CIY
2. FUMES ON FIRE
3. FHISHING FRENZY
4. THE BIOTROOPERS
5. CHOMIC CHAOS
6. SINTHE TRIKE
7. GALACTIC ODYSSEY
8. RIS OF THE PUPPETS


keterangan :
Diamonds : 0
Rank cs : GOLD 2
Rank Br : MASTER 2
Vault baju : 327
Vault celana : 240 ( Celana angel ada 2)
Vault sandal : 244
Vault topeng : 116
Vault wajah : 64 ( joker on )
Vault rambut : 212
Vault bandel : 4
Emot : 77
Emot loby : 4
Emot perubahan : 1
Tas : 85
parasut : 45
Peti mati : 76
Skyboard : 68
Skin kendaraan : 75
Total skin senjata : -
',
                'gambar' => '/images/akun-game/akun-epep-1-tahun.png',
                'harga' => 200000,
                'status_akun' => 'tersedia',
            ],
            [
                'penjual_id' => 2,
                'game_id' => 3,
                'judul' => 'AKUN ML POLOSAN MURAH USER LANCE WR GG SKIN DAWNING STARS LANCELOT, STUN BRODY, EPIC ESTES DRAGON, SPESIAL URANUS, SUN, MARTIS, STARLIGHT ALDOUS DLL',
                'deskripsi' => 'Tier Epic III
Emblem Max I (Assasins)
WR Lance 80% an Event Change Name ON
Note: Tier diatas adalah tier pada saat season 29 ya, jika sdh berganti season tier akan turun.',
                'gambar' => 'public/images/akun-game/akun-mole-lance-hero.png',
                'harga' => 250000,
                'status_akun' => 'tersedia',
            ],
        ];

        foreach ($akunGames as $akunGame) {
            // $slug = $this->createSlug($akunGame['deskripsi']);
            // $gambarName = $slug . '.jpg'; // Misal menggunakan ekstensi jpg
            // $gambarUrl = url('storage/images/akun-game/' . $gambarName);

            AkunGame::create([
                'penjual_id' => $akunGame['penjual_id'],
                'game_id' => $akunGame['game_id'],
                'judul' => $akunGame['judul'],
                'deskripsi' => $akunGame['deskripsi'],
                'gambar' => $akunGame['gambar'],
                'harga' => $akunGame['harga'],
                'status_akun' => $akunGame['status_akun'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }

    // private function createSlug($deskripsi)
    // {
    //     $words = array_slice(explode(' ', $deskripsi), 0, 10);
    //     return Str::slug(implode(' ', $words)) . '-' . time();
    // }
}
