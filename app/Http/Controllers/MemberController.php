<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Transaction;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::latest()->paginate(15);
        return view('members.index', compact('members'));
    }

    public function create()
    {
        return view('members.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:members,phone',
            'email' => 'nullable|email|max:255',
        ]);

        Member::create($validated);

        return redirect()->route('members.index')->with('success', 'Member berhasil ditambahkan.');
    }

    public function show(Member $member)
    {
        $transactions = $member->transactions()->with('cashier')->latest()->take(20)->get();
        $pointsLog = $member->pointsLog()->with('transaction')->latest()->take(50)->get();
        return view('members.show', compact('member', 'transactions', 'pointsLog'));
    }

    public function edit(Member $member)
    {
        return view('members.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:members,phone,' . $member->id,
            'email' => 'nullable|email|max:255',
        ]);

        $member->update($validated);

        return redirect()->route('members.index')->with('success', 'Member berhasil diperbarui.');
    }

    public function destroy(Member $member)
    {
        $member->delete();
        return redirect()->route('members.index')->with('success', 'Member berhasil dihapus.');
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $members = Member::where('name', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->orderBy('name')
            ->take(10)
            ->get()
            ->map(fn($m) => [
                'id' => $m->id,
                'name' => $m->name,
                'phone' => $m->phone,
                'points' => $m->points,
            ]);

        return response()->json($members);
    }

    public function searchByPhone(Request $request)
    {
        $phone = $request->get('phone', '');
        $member = Member::where('phone', $phone)->first();

        if ($member) {
            return response()->json([
                'found' => true,
                'id' => $member->id,
                'name' => $member->name,
                'phone' => $member->phone,
                'points' => $member->points,
            ]);
        }

        return response()->json(['found' => false]);
    }
}
