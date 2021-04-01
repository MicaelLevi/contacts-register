<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Person;
use App\Models\Contacts;

class HomeController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function store(Request $request)
    {

        $person = new Person;

        //Dados da pessoa
        $person->name = $request->name;
        $person->cpf = $request->cpf;

        $person->save();

        //Contatos da pessoa
        foreach ($request->contacts as $contact) {
            if (isset($contact['contact']) && isset($contact['description'])) {
                $dataContacts = new Contacts();
                $dataContacts->people_id = $person->id;
                $dataContacts->number = $contact['contact'];
                $dataContacts->description = $contact['description'];

                $dataContacts->save();
            }
        }

        return response()->json(['success' => true]);
    }

    public function retrieve()
    {
        return Person::with('contacts')->get();
    }
}
