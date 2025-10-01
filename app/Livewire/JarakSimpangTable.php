<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\JarakSimpang;
use App\Models\Simpang;

class JarakSimpangTable extends Component
{
    use WithPagination;

    protected $listeners = ['edit-item' => 'edit'];
    public $search = ''; // 🔍 untuk pencarian
    protected $queryString = ['search']; // biar URL bisa bawa query


    public $jarak_simpang_id;
    public $ID_Simpang;
    public $dari_arah;
    public $ke_arah;
    public $jarak_km;
    public $lebar_jalan;
    public $nama_ruas;
    public $keterangan;
    public $status = 'aktif';
    public $isEdit = false;
    public $showModal = false;

    protected $rules = [
        'ID_Simpang' => 'required|exists:simpang,id',
        'dari_arah' => 'required|string',
        'ke_arah' => 'required|string',
        'lebar_jalan' => 'nullable|numeric|min:0',
        'jarak_km' => 'required|numeric',
        'nama_ruas' => 'nullable|string',
        'keterangan' => 'nullable|string',
        'status' => 'in:aktif,nonaktif',
    ];

    // public function render()
    // {
    //     return view('livewire.jarak-simpang-table', [
    //         'data' => JarakSimpang::with('simpang')->paginate(10),
    //         'simpangs' => Simpang::all(),
    //     ]);
    // }
    public function render()
    {
        $search = $this->search;

        $query = JarakSimpang::with('simpang')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('dari_arah', 'like', "%{$search}%")
                        ->orWhere('ke_arah', 'like', "%{$search}%")
                        ->orWhere('nama_ruas', 'like', "%{$search}%")
                        ->orWhereHas('simpang', function ($rel) use ($search) {
                            $rel->where('Nama_Simpang', 'like', "%{$search}%");
                        });
                });
            });


        // $query = JarakSimpang::with('simpang')
        //     ->when($search, function ($q) use ($search) {
        //         $q->whereHas('simpang', function ($rel) use ($search) {
        //             $rel->where('Nama_Simpang', 'like', "%{$search}%");
        //         });
        //     });


        // dd(JarakSimpang::with('simpang')->first()->simpang->toArray());

        \Log::info('Search value: ' . $this->search);


        return view('livewire.jarak-simpang-table', [
            'data' => $query->paginate(10),
            'simpangs' => Simpang::all(),
        ]);
    }


    public function resetInput()
    {
        $this->jarak_simpang_id = null;
        $this->ID_Simpang = '';
        $this->dari_arah = '';
        $this->ke_arah = '';
        $this->jarak_km = '';
        $this->nama_ruas = '';
        $this->keterangan = '';
        $this->status = 'aktif';
        $this->lebar_jalan = '';
        $this->isEdit = false;
    }
    public function create()
    {
        $this->resetInput();
        $this->showModal = true;
    }

    public function updatingSearch()
    {
        $this->resetPage(); // reset ke halaman 1 kalau search berubah
    }
    
    public function setEdit($id, $ID_Simpang, $dari_arah, $ke_arah, $jarak_km, $lebar_jalan, $nama_ruas, $keterangan, $status)
    {
        $this->id_edit = $id;
        $this->ID_Simpang = $ID_Simpang;
        $this->dari_arah = $dari_arah;
        $this->ke_arah = $ke_arah;
        $this->jarak_km = $jarak_km;
        $this->lebar_jalan = $lebar_jalan;
        $this->nama_ruas = $nama_ruas;
        $this->keterangan = $keterangan;
        $this->status = $status;

        $this->isEdit = true;
        $this->dispatch('openEditDialog'); // buka modal
    }

    public function edit($id)
    {
        $item = JarakSimpang::findOrFail($id);
        $this->jarak_simpang_id = $item->id;
        $this->ID_Simpang = $item->ID_Simpang;
        $this->dari_arah = $item->dari_arah;
        $this->ke_arah = $item->ke_arah;
        $this->jarak_km = $item->jarak_km;
        $this->lebar_jalan = $item->lebar_jalan;
        $this->nama_ruas = $item->nama_ruas;
        $this->keterangan = $item->keterangan;
        $this->status = $item->status;
        $this->isEdit = true;
        $this->showModal = true; // 🚀 tampilkan modal
    }

    public function store()
    {
        $this->validate();
    
        if ($this->isEdit) {
            JarakSimpang::findOrFail($this->jarak_simpang_id)
                ->update($this->only([
                    'ID_Simpang', 'dari_arah', 'ke_arah',
                    'jarak_km', 'lebar_jalan', 'nama_ruas',
                    'keterangan', 'status'
                ]));
    
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Data berhasil diperbarui!'
            ]);
        } else {
            JarakSimpang::create($this->only([
                'ID_Simpang', 'dari_arah', 'ke_arah',
                'jarak_km', 'lebar_jalan', 'nama_ruas',
                'keterangan', 'status'
            ]));
    
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Data berhasil ditambahkan!'
            ]);
        }
    
        $this->resetInput();
        $this->showModal = false;
    }
    
    public function delete($id)
    {
        JarakSimpang::findOrFail($id)->delete();
    
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Data berhasil dihapus!'
        ]);
    }
    
}
