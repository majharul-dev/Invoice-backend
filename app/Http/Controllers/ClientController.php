<?php



namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        return Client::with('company')->get();
    }

    public function store(Request $request)

    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string',
            'email' => 'required|email|unique:clients,email',
        ]);

        return Client::create($request->all());
    }

    public function show(Client $client)
    {
        return $client->load('company');
    }

    public function update(Request $request, Client $client)
    {
        $client->update($request->all());
        return response()->json(['message' => 'Updated successfully']);
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
