<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Student;

use Livewire\WithPagination;

class Students extends Component
{
    use WithPagination;

    public $ids;
    public $firstname;
    public $lastname;
    public $email;
    public $phone;

    public function render()
    {
        $students = Student::orderBy('id', 'DESC')->paginate(5);
        return view('livewire.students', ['students' => $students]);
    }

    public function resetInputFields()
    {

        $this->firstname = '';
        $this->lastname = '';
        $this->email = '';
        $this->phone = '';
    }

    public function store()
    {
        $validatedData = $this->validate([

            'firstname' => 'required', 
            'lastname' => 'required',
            'email' => 'required|email', 
            'phone' => 'required'

        ]);
        Student::create($validatedData);
        session()->flash('message', '¡Estudiante creado con Exito!');
        $this->resetInputFields();
        $this->emit('studentAdded');
    }

    public function edit($id)
    {
        $student = Student::where('id', $id)->first();
        $this->ids = $student->id;
        $this->firstname = $student->firstname;
        $this->lastname = $student->lastname;
        $this->email = $student->email;
        $this->phone = $student->phone;
    }

    public function update()
    {
        $this->validate([

            'firstname' => 'required', 
            'lastname' => 'required',
            'email' => 'required|email', 
            'phone' => 'required'
        ]);

        if ($this->ids) {
            $student = Student::find($this->ids);
            $student->update([

                'firstname' => $this->firstname, 
                'lastname' => $this->lastname, 
                'email' => $this->email,
                'phone' => $this->phone
            ]);

            session()->flash('message', 'Se Actualizo el Estudiante con Exito');
            $this->resetInputFields();
            $this->emit('studentUpdated');
        }
    }
    public function delete()
    {   
        if($this->ids)
        {
            $student = Student::find($this->ids);
            $student->delete([

                'firstname' => $this->firstname, 
                'lastname' => $this->lastname, 
                'email' => $this->email,
                'phone' => $this->phone
            ]);

            session()->flash('message', 'Se Eliminado el Estudiante con Exito');
        }
    }
}
