<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use Illuminate\Support\Facades\Validator;

class MemberController extends Controller
{
    // Display a listing of members
    public function index()
    {
        $members = Member::all();
        return response()->json($members);
    }

    // Store a newly created member in the database
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'member_since' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $member = Member::create($validator->validated());

        return response()->json($member, 201);
    }

    // Display the specified member
    public function show($id)
    {
        $member = Member::findOrFail($id);
        return response()->json($member);
    }

    // Update the specified member in the database
    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'member_since' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $member->update($validator->validated());

        return response()->json($member);
    }

    // Remove the specified member from the database
    public function destroy($id)
    {
        $member = Member::findOrFail($id);
        $member->delete();

        return response()->json(['message' => 'Member deleted successfully'], 200);
    }
}