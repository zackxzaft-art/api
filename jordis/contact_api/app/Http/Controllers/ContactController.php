<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $perPage = min($request->integer('per_page', 5), 50);

    $contacts = Contact::with('user')
        ->where('user_id', $request->user()->id)
        ->paginate($perPage);

    return response()->json([
        'message' => 'Contactos obtenidos correctamente',
        'data' => $contacts,
    ]);
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('contacts', 'phone_number')
                    ->where('user_id', $request->user()->id),
            ],
        ]);

        $data['user_id'] = $request->user()->id;

        $contact = Contact::create($data);

        return response()->json([
            'message' => 'Contacto creado correctamente',
            'data' => $contact,
        ]);
    }
    /**
     * Display the specified resource.
     */
public function show(Request $request, Contact $contact)
{
    if ($contact->user_id !== $request->user()->id) {
        abort(403);
    }

    return response()->json([
        'message' => 'Contacto obtenido correctamente',
        'data' => $contact,
    ]);
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contact $contact)
    {
        if ($contact->user_id !== $request->user()->id) {
            abort(403);
        }

        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'phone_number' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('contacts', 'phone_number')
                    ->where('user_id', $request->user()->id)
                    ->ignore($contact->id),
            ],
        ]);

        $contact->update($data);

        return response()->json([
            'message' => 'Contacto actualizado correctamente',
            'data' => $contact,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Contact $contact)
    {

    }
}
