<?php

namespace App\Http\Controllers;

use App\Models\Letter;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Html;

class LetterController extends Controller
{
    public function index()
    {
        $letters = Letter::with('user')->latest()->paginate(10);
        return view('letters.index', compact('letters'));
    }

    public function create()
    {
        $lastLetter = Letter::latest()->first();
        $nextNumber = $lastLetter ? (int) explode('/', $lastLetter->letter_number)[0] + 1 : 1;
        $suggestedNumber = sprintf('%03d', $nextNumber) . '/SP/XII/2026';
        return view('letters.create', compact('suggestedNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'letter_number' => 'required|string|max:255|unique:letters,letter_number',
            'date' => 'required|date',
            'attachment_count' => 'nullable|string|max:255',
            'subject' => 'required|string|max:255',
            'recipient_name' => 'required|string|max:255',
            'recipient_place' => 'nullable|string|max:255',
            'sender_name' => 'required|string|max:255',
            'sender_position' => 'required|string|max:255',
            'sender_address' => 'nullable|string',
            'body' => 'required|string',
            'place' => 'nullable|string|max:255',
        ]);

        $validated['user_id'] = auth()->id();

        Letter::create($validated);

        return redirect()->route('letters.index')->with('success', __('Letter created successfully'));
    }

    public function show(Letter $letter)
    {
        $letter->load('user');
        return view('letters.show', compact('letter'));
    }

    public function edit(Letter $letter)
    {
        return view('letters.edit', compact('letter'));
    }

    public function update(Request $request, Letter $letter)
    {
        $validated = $request->validate([
            'letter_number' => 'required|string|max:255|unique:letters,letter_number,' . $letter->id,
            'date' => 'required|date',
            'attachment_count' => 'nullable|string|max:255',
            'subject' => 'required|string|max:255',
            'recipient_name' => 'required|string|max:255',
            'recipient_place' => 'nullable|string|max:255',
            'sender_name' => 'required|string|max:255',
            'sender_position' => 'required|string|max:255',
            'sender_address' => 'nullable|string',
            'body' => 'required|string',
            'place' => 'nullable|string|max:255',
        ]);

        $letter->update($validated);

        return redirect()->route('letters.index')->with('success', __('Letter updated successfully'));
    }

    public function destroy(Letter $letter)
    {
        $letter->delete();
        return redirect()->route('letters.index')->with('success', __('Letter deleted successfully'));
    }

    public function exportWord(Letter $letter)
    {
        $phpWord = new PhpWord();

        $section = $phpWord->addSection([
            'marginLeft' => 1440,  // 1 inch in twips
            'marginRight' => 1440,
            'marginTop' => 1440,
            'marginBottom' => 1440,
        ]);

        $section->addText(
            $letter->letter_number,
            ['bold' => true, 'size' => 10, 'name' => 'Times New Roman'],
            ['align' => 'center']
        );

        $section->addTextBreak();

        Html::addHtml($section, '
            <table style="width:100%;border-collapse:collapse;font-family:\'Times New Roman\',serif;font-size:12pt">
                <tr><td style="width:20%">Nomor</td><td style="width:5%">:</td><td>' . e($letter->letter_number) . '</td></tr>
                <tr><td>Lampiran</td><td>:</td><td>' . e($letter->attachment_count ?? '-') . '</td></tr>
                <tr><td>Perihal</td><td>:</td><td><b>' . e($letter->subject) . '</b></td></tr>
            </table>
        ');

        $section->addTextBreak();
        $section->addText('Kepada Yth.', ['size' => 12, 'name' => 'Times New Roman']);
        $section->addText($letter->recipient_name, ['bold' => true, 'size' => 12, 'name' => 'Times New Roman']);
        if ($letter->recipient_place) {
            $section->addText('di', ['size' => 12, 'name' => 'Times New Roman']);
            $section->addText($letter->recipient_place, ['size' => 12, 'name' => 'Times New Roman']);
        }

        $section->addTextBreak();
        $section->addText('Dengan hormat,', ['size' => 12, 'name' => 'Times New Roman']);
        $section->addTextBreak();

        $section->addText($letter->body, ['size' => 12, 'name' => 'Times New Roman']);

        $section->addTextBreak();
        $section->addText('Demikian surat permohonan ini dibuat, atas perhatian dan kerjasamanya diucapkan terima kasih.', ['size' => 12, 'name' => 'Times New Roman']);

        $section->addTextBreak(2);

        $section->addText(
            ($letter->place ?? '________') . ', ' . \Carbon\Carbon::parse($letter->date)->isoFormat('D MMMM Y'),
            ['size' => 12, 'name' => 'Times New Roman'],
            ['align' => 'right']
        );

        $section->addText('Hormat Kami,', ['size' => 12, 'name' => 'Times New Roman']);
        $section->addTextBreak(3);
        $section->addText($letter->sender_name, ['bold' => true, 'size' => 12, 'name' => 'Times New Roman']);
        $section->addText($letter->sender_position, ['size' => 11, 'name' => 'Times New Roman']);

        $fileName = 'Surat_Permohonan_' . $letter->letter_number . '.docx';
        $tempPath = storage_path('app/temp/' . $fileName);
        if (!is_dir(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $phpWord->save($tempPath, 'Word2007');

        return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
    }
}
