<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Employee as ModelsEmployee;
use Livewire\Component;
use Livewire\WithPagination;

class Employee extends Component
{
    use WithPagination;

    protected $paginationTheme='bootstrap';

    public $first;
    public $last;
    public $category_id;
    public $page=10;
    public $updateData= false;
    public $employee_id;
    public $result;
    public $searchCategory;
    public $showData = false;
    public $sortColumn = 'created_at';
    public $sortType = 'asc';


    public function updatePage(){
        $this->resetPage();
    }

    public function store(){
        $rules = [
            'first'=>'required',
            'last'=>'required',
            'category_id'=>'required'

        ];
        $pesan=[
            'first.required'=>'First name wajib di isi',
            'last.required'=>'Last name wajib di isi',
            'category_id.required'=>'category name wajib di isi'

        ];
        $validate = $this->validate($rules,$pesan);
        ModelsEmployee::create($validate);
        session()->flash('message', 'Data berhasil di input');
        $this->clear();
    }
    public function show($id){
        $employee = ModelsEmployee::find($id);

        $this->first=$employee->first;
        $this->last=$employee->last;
        $this->category_id=$employee->category_id;

        $this->showData = true;
        $this->employee_id = $id;
    }
    public function edit($id){
        $employee = ModelsEmployee::find($id);

        $this->first=$employee->first;
        $this->last=$employee->last;
        $this->category_id=$employee->category_id;

        $this->updateData = true;
        $this->employee_id = $id;

    }
    public function update(){
        $rules = [
        'first'=>'required',
        'last'=>'required',
        'category_id'=>'required'

        ];
        $pesan=[
        'first.required'=>'First name wajib di isi',
        'last.required'=>'Last name wajib di isi',
        'category_id.required'=>'category name wajib di isi'

        ];
        $validation = $this->validate($rules,$pesan);
        ModelsEmployee::find($this->employee_id)->update($validation);

        session()->flash('message', 'Data berhasil di update');

        $this->clear();
    }
    public function clear(){
        $this->first = '';
        $this->last = '';
        $this->category_id = '';

        $this->updateData = false;
        $this->showData = false;
        $this->employee_id='';
    }
    public function delete(){

        $id = $this->employee_id;
        $employee = ModelsEmployee::find($id);
        $employee->delete();
        session()->flash('message', 'Data berhasil di Hapus');
        $this->clear();
    }
    public function delete_confirmation($id){
        $this->employee_id = $id;
    }
    public function sort($columnName){
        $this->sortColumn = $columnName;
        $this->sortType = $this->sortType == 'asc'?'desc':'asc';
    }
    public function render()
    {
        $employeeQuery = ModelsEmployee::with('category')->orderBy($this->sortColumn, $this->sortType);

        if ($this->result != null) {
        $employeeQuery->where(function ($query) {
        $query->where('first', 'like', '%' . $this->result . '%')
        ->orWhere('last', 'like', '%' . $this->result . '%');
        });
        }

        if ($this->searchCategory != null) {
        $employeeQuery->whereHas('category', function ($query) {
            $query->where('name', 'like', '%' . $this->searchCategory . '%');
        });
        }

        $employee = $employeeQuery->paginate($this->page);
        $category = Category::all();

        return view('livewire.employee', compact('employee', 'category'));
    }
}
// <?php

// namespace App\Livewire;

// use App\Models\Employee;
// use Livewire\Component;
// use Livewire\WithPagination;

// class Employee extends Component
// {
//     use WithPagination;

//     protected $paginationTheme = 'bootstrap';
//     public $first;
//     public $last;
//     public $page = 2;
//     public $updateData = false;
//     public $employee_id;
//     public $result;

//     public function updatePage()
//     {
//         $this->resetPage();
//     }

//     public function store()
//     {
//         $this->validate([
//             'first' => 'required',
//             'last' => 'required',
//         ]);

//         Employee::create([
//             'first' => $this->first,
//             'last' => $this->last,
//         ]);

//         session()->flash('message', 'Data berhasil di input');
//         $this->clear();
//     }

//     public function edit($id)
//     {
//         $employee = Employee::find($id);

//         $this->first = $employee->first;
//         $this->last = $employee->last;

//         $this->updateData = true;
//         $this->employee_id = $id;
//     }

//     public function update()
//     {
//         $this->validate([
//             'first' => 'required',
//             'last' => 'required',
//         ]);

//         Employee::find($this->employee_id)->update([
//             'first' => $this->first,
//             'last' => $this->last,
//         ]);

//         session()->flash('message', 'Data berhasil di update');
//         $this->clear();
//     }

//     public function clear()
//     {
//         $this->first = '';
//         $this->last = '';
//         $this->updateData = false;
//         $this->employee_id = '';
//     }

//     public function delete($id)
//     {
//         $employee = Employee::find($id);
//         $employee->delete();
//     }

//     public function render()
//     {
//         $employeeQuery = Employee::query();

//         if ($this->result != null) {
//             $employeeQuery->where('first', 'like', '%' . $this->result . '%')
//                           ->orWhere('last', 'like', '%' . $this->result . '%');
//         }

//         $employee = $employeeQuery->orderBy('created_at', 'asc')->paginate($this->page);

//         return view('livewire.employee', compact('employee'));
//     }
// }

